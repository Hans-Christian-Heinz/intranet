<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Http\Requests\AddImageRequest;
use App\Http\Requests\PdfRequest;
use App\Http\Requests\StoreDocumentationRequest;
use App\Image;
use App\Notifications\CustomNotification;
use App\Project;
use App\Traits\ControllsDocuments;
use App\Traits\SavesSections;
use App\Version;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    use SavesSections, ControllsDocuments;

    public function index(Project $project) {
        $documentation = $project->documentation()->with('comments')->first();
        if (! $documentation) {
            return redirect(route('abschlussprojekt.dokumentation.create', $project));
        }
        return view('abschlussprojekt.dokumentation.index', [
            'documentation' => $documentation,
            'version' => $documentation->latestVersion(),
            'disable' => app()->user->isNot($documentation->lockedBy),
        ]);
    }

    /**
     * Erstelle eine neue Dokumentation für das übergebene Projekt
     *
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Project $project) {
        $documentation = new Documentation();
        $documentation->project()->associate($project);
        $documentation->save();
        $project->documentation()->associate($documentation);
        $project->save();
        $version = new Version();
        $version->user()->associate(app()->user);
        $documentation->versions()->save($version);
        $documentation->makeSections(Documentation::SECTIONS, $version);

        if (request()->is('admin*')) {
            $route = 'admin.abschlussprojekt.dokumentation.index';
        }
        else {
            $route = 'abschlussprojekt.dokumentation.index';
        }
        return redirect(route($route, $project))->with('status', 'Die Dokumentation wurde erfolgreich angeleget.');
    }

    /**
     * Beachte: beim Ändern eines Dokuments wird eine neue Instanz erzeugt; die alte Instanz (der alte Datensatz) bleibt unverändert.
     *
     * @param StoreDocumentationRequest $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreDocumentationRequest $request, Project $project) {
        $this->authorize('store', $project->documentation);

        $documentation = $project->documentation;
        $versionOld = $documentation->latestVersion();
        $sectionsOld = $documentation->getCurrentSections();

        $versionNew = new Version();
        $versionNew->user()->associate(app()->user);
        $versionNew->documentation()->associate($documentation);
        $versionNew->save();
        foreach($sectionsOld as $old) {
            $this->saveSection($request, $documentation, $versionNew, $versionOld, $old);
        }

        //update timestamps:
        $documentation->touch();

        if (app()->user->isNot($documentation->user)) {
            $project->user->notify(new CustomNotification(app()->user->full_name, 'Änderungen an der Projektdokumentation',
                'An Ihrer Projektdokumentation wurden vom Absender Änderungen vorgenommen.'));
        }

        //return redirect(route('abschlussprojekt.dokumentation.index', $project))->with('status', 'Die Dokumentation wurde erfolgreich gespeichert.');
        return redirect()->back()->with('status', 'Die Dokumentation wurde erfolgreich gespeichert.');
    }

    public function lock(Project $project, Documentation $documentation) {
        $this->authorize('lock', $documentation);
        return $this->lockDocument($documentation);
    }

    public function release(Project $project, Documentation $documentation) {
        $this->authorize('lock', $documentation);
        return $this->releaseDocument($documentation);
    }

    /**
     * @param PdfRequest $request
     * @param Project $project
     * @return string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Mpdf\MpdfException
     */
    public function pdf(PdfRequest $request, Project $project) {
        $documentation = $project->documentation;
        $version = $documentation->latestVersion();
        $this->authorize('pdf', $documentation);

        $title = 'Projektdokumentation ' . $project->user->full_name;

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',

            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 20,
            'margin_bottom' => 20,

            'setAutoTopMargin' => 'stretch',
            'autoMarginPadding' => 5,

            'fontDir' => array_merge($fontDirs, [base_path() . '/resources/fonts']),
            'fontdata' => $fontData + [
                    'opensans' => [
                        'R' => 'OpenSans-Regular.ttf',
                        'B' => 'OpenSans-Bold.ttf'
                    ]
                ],
            'default_font_size' => $request->textgroesse,
            'default_font' => 'opensans',

            'tempDir' => sys_get_temp_dir(),
        ]);

        $mpdf->DefHTMLHeaderByName('header',
            '<div style="border-bottom: 1px solid black;"><b>' .
            $documentation->shortTitle . '</b><br/>' . $documentation->longTitle .
            '</div>');

        $mpdf->DefHTMLFooterByName('footer',
            '<table style="width: 100%; border: none; border-top: 1px solid black;">
    <tr style="border: none;">
        <td style="border: none;">' . $project->user->full_name . '</td>
        <td style="border:none; text-align: right;">{PAGENO}/{nbpg}</td>
    </tr>
</table>');

        $mpdf->SetTitle($title);

        $mpdf->WriteHTML(view('pdf.dokumentation', [
            'project' => $documentation->project()->with('user')->with('supervisor')->first(),
            'documentation' => $documentation,
            'format' => $request->all(),
            'version' => $version,
            'kostenstellen' => $documentation->getKostenstellen($version),
            'kostenstellen_gesamt' => $documentation->getKostenstellenGesamt($version),
            'zeitplanung' => $documentation->zeitplanung,
        ])->render());

        return $mpdf->Output($title . '.pdf', 'I');
    }

    /**
     * FÜge einem Abschnitt der Dokumentation ein Bild hinzu.
     *
     * @param AddImageRequest $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function addImage(AddImageRequest $request, Project $project) {
        $this->authorize('addImage', $project->documentation);

        //Lege zunächst eine neue Version an
        $documentation = $project->documentation;
        //Hilfsmethode, die eine neue Version anlegt und den zu bearbeitenden Abschnitt kopiert.
        $data = $this->copySection($request, $documentation);
        $sectionOld = $data['sectionOld'];
        $section = $data['section'];

        //Ich habe mit saveMany mit dem Pivot immer Probelme bekommen;deshalb wird jetzt jeder Datensatz individuell gespeichert
        foreach ($sectionOld->images as $image) {
            $section->images()->save($image, ['sequence' => $image->pivot->sequence,]);
        }

        //Erstelle nun einen neuen Datensatz für images und ordne ihn dem neuen Abschnitt hinzu
        $imageSize = getimagesize(asset('storage/' . $request->path));
        $img = new Image([
            'footnote' => $request->footnote,
            'path' => $request->path,
            'width' => $imageSize[0],
            'height' => $imageSize[1],
        ]);
        $section->images()->save($img, ['sequence' => $section->images()->count(),]);

        if (app()->user->isNot($documentation->user)) {
            $project->user->notify(new CustomNotification(app()->user->full_name, 'Änderungen an der Projektdokumentation',
                'An Ihrer Projektdokumentation wurden vom Absender Änderungen vorgenommen.'));
        }

        return redirect()->back()->with('status', 'Dem Abschnitt wurde erfolgreich ein Bild zugeordnet.');
    }

    /**
     * Entferne eine Image-Instanz von einem Abschnitt
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function detachImage(Request $request, Project $project) {
        $documentation = $project->documentation;
        $this->authorize('addImage', $documentation);

        $request->validate([
            'img_id' => 'required|int|min:1',
            'section_id' => 'required|int|min:1',
        ]);

        //Hilfsmethode, die eine neue Version anlegt und den zu bearbeitenden Abschnitt kopiert.
        $data = $this->copySection($request, $documentation);
        $sectionOld = $data['sectionOld'];
        $section = $data['section'];

        //Füge dem neuen Abschnitt nun alle Bilder bis auf das zu entfernende hinzu.
        $toDelete = $sectionOld->images()->find($request->img_id);
        foreach ($sectionOld->images as $image) {
            if($image->is($toDelete)) {
                continue;
            }
            //Passe ggf die Reihenfolge an
            if ($image->pivot->sequence > $toDelete->pivot->sequence) {
                $section->images()->save($image, ['sequence' => $image->pivot->sequence - 1,]);
            }
            else {
                $section->images()->save($image, ['sequence' => $image->pivot->sequence,]);
            }
        }

        if (app()->user->isNot($documentation->user)) {
            $project->user->notify(new CustomNotification(app()->user->full_name, 'Änderungen an der Projektdokumentation',
                'An Ihrer Projektdokumentation wurden vom Absender Änderungen vorgenommen.'));
        }

        return redirect()->back()->with('status', 'Das Bild wurde erfolgreich von diesem Abschnitt entfernt.');
    }

    /**
     * Bearbeite eine Bild-Instanz
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateImage(Request $request, Project $project) {
        $documentation = $project->documentation;
        $this->authorize('addImage', $documentation);

        //Maximale sequence / position / reihenfolge wird später überprüft
        $request->validate([
            'img_id' => 'required|int|min:1',
            'section_id' => 'required|int|min:1',
            'footnote' => 'nullable|string|max:255',
            'sequence' => 'required|int|min:0',
            'width' => 'required|int|min:100|max:1500',
            'height' => 'required|int|min:100|max:1500',
        ]);

        //Hilfsmethode, die eine neue Version anlegt und den zu bearbeitenden Abschnitt kopiert.
        $data = $this->copySection($request, $documentation);
        $sectionOld = $data['sectionOld'];
        $section = $data['section'];

        //Validiere die Position (sequence)
        if ($request->sequence >= $sectionOld->images->count()) {
            $sequence = $sectionOld->images->count() - 1;
        }
        else {
            $sequence = $request->sequence;
        }

        //Kopiere nun das zu verändernde Bild und verändere es
        $imageOld = $sectionOld->images()->find($request->img_id);
        $image = $imageOld->replicate();
        $image->footnote = $request->footnote;
        $image->height = $request->height;
        $image->width = $request->width;

        //Beachte: Die Bilder werden automatisch beim Auslesen nach sequence geordnet
        foreach ($sectionOld->images as $i) {
            if ($i->is($imageOld)) {
                continue;
            }
            $section->images()->save($i, ['sequence' => $i->pivot->sequence,]);
        }
        $section->images()->save($image, ['sequence' => $imageOld->pivot->sequence]);

        //Nun ist noch die Reihenfolge von $image anzupassen (auf $sequence)
        //Das Bild wird nach hinten verschoben
        if ($sequence > $image->pivot->sequence) {
            //Die Bilder zwischen alter und neuer Reihenfolge müssen um 1 nach vorne / oben verschoben werden
            $section->images->filter(function ($value, $key) use ($sequence, $image, $section) {
                if ($value->pivot->sequence <= $sequence && $value->pivot->sequence > $image->pivot->sequence) {
                    $section->images()->updateExistingPivot($value, ['sequence' => $value->pivot->sequence - 1]);
                }
            });
            $section->images()->updateExistingPivot($image, ['sequence' => $sequence]);
        }
        //Das Bild wird nach vorn verschoben
        elseif ($sequence < $image->pivot->sequence) {
            //Die Bilder zwischen alter und neuer Reihenfolge müssen um 1 nach hinten / unten verschoben werden
            $section->images->filter(function ($value, $key) use ($sequence, $image, $section) {
                if ($value->pivot->sequence >= $sequence && $value->pivot->sequence < $image->pivot->sequence) {
                    $section->images()->updateExistingPivot($value, ['sequence' => $value->pivot->sequence + 1]);
                }
            });
            $section->images()->updateExistingPivot($image, ['sequence' => $sequence]);
        }

        if (app()->user->isNot($documentation->user)) {
            $project->user->notify(new CustomNotification(app()->user->full_name, 'Änderungen an der Projektdokumentation',
                'An Ihrer Projektdokumentation wurden vom Absender Änderungen vorgenommen.'));
        }

        return redirect()->back()->with('status', 'Das Bild wurde erfolgreich bearbeitet.');
    }

    /**
     * Hilfsmethode
     * Kopiere den Abschnitt, dem ein Bild hinzugefügt werden soll, in dem ein Bild bearbeitet werden soll, aus dem ein
     * Bild entfernt werden soll und erstelle eine neue Version der Dokumentation
     *
     * @param Request|AddImageRequest $request
     * @param Documentation $documentation
     * @return array
     */
    private function copySection($request, Documentation $documentation) {
        //Lege zunächst eine neue Version an
        $versionOld = $documentation->latestVersion();
        $version = $versionOld->replicate();
        $version->save();

        //Kopiere nun den Abschnitt, von dem ein Bild zu entfernen ist
        //Entferne den Original-Abschnitt von der neuen Version und füge die Kopie hinzu
        $sectionOld = $versionOld->sections->where('id', $request->section_id)->shift();
        $section = $sectionOld->replicate();
        $sectionsHelp = $versionOld->sections->reject(function ($value, $key) use ($request) {
            return $value->id == $request->section_id;
        });
        foreach($sectionsHelp as $help) {
            $version->sections()->save($help, ['sequence' => $help->pivot->sequence,]);
        }
        //$version->sections()->saveMany($sectionsHelp);
        $version->sections()->save($section, ['sequence' => $sectionOld->pivot->sequence,]);

        return compact('versionOld', 'version', 'sectionOld', 'section');
    }
}
