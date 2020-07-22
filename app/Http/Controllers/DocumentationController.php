<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Http\Requests\AddImageRequest;
use App\Http\Requests\PdfRequest;
use App\Http\Requests\StoreDocumentationRequest;
use App\Image;
use App\Project;
use App\Section;
use App\Traits\SavesSections;
use App\Version;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    use SavesSections;

    public function index(Project $project) {
        if (! $project->documentation) {
            return redirect(route('abschlussprojekt.dokumentation.create', $project));
        }
        return view('abschlussprojekt.dokumentation.index', [
            'documentation' => $project->documentation,
            'version' => $project->documentation->latestVersion(),
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

        return redirect(route('abschlussprojekt.dokumentation.index', $project))->with('status', 'Die Dokumentation wurde erfolgreich angeleget.');
    }

    /**
     * Beachte: beim Ändern eines Dokuments wird eine neue Instanz erzeugt; die alte Instanz (der alte Datensatz) bleibt unverändert.
     *
     * @param StoreDocumentationRequest $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
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

        return redirect(route('abschlussprojekt.dokumentation.index', $project))->with('status', 'Die Dokumentation wurde erfolgreich gespeichert.');
    }

    /**
     * Anzeigen aller Versionen der Dokumentation in einer Tabelle
     *
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function history(Project $project) {
        $documentation = $project->documentation;
        $this->authorize('history', $documentation);

        $versions = $documentation->versions()->with('user')->orderBy('updated_at', 'DESC')->get();
        return view('abschlussprojekt.dokumentation.history', [
            'documentation' => $documentation,
            'versions' => $versions,
        ]);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function vergleich(Request $request, Project $project) {
        $documentation = $project->documentation;
        $this->authorize('history', $documentation);

        //Es müssen genau zwei Versionen ausgewählt werden
        $request->validate([
            'vergleichen' => 'required|array|size:2',
        ]);

        $versionen = [];
        foreach ($request->vergleichen as $v_id) {
            array_push($versionen, Version::with('user')->find($v_id));
        }

        //Suche nach Unterschieden: Suche nach Section-Instanzen, die zu einer der beiden Versionen gehören, nicht zu beiden
        //Suche in beiden Richtungen nach Unterschieden, falls zwei Versionen verschiedene Abschnitte haben
        $diff_sect = collect([]);
        foreach ($documentation->getAllSections($versionen[0])->diff($documentation->getAllSections($versionen[1])) as $sect) {
            $diff_sect->push($sect->name);
            while(! is_null($sect->section)) {
                $sect = $sect->section;
                $diff_sect->push($sect->name);
            }
        }
        foreach ($documentation->getAllSections($versionen[1])->diff($documentation->getAllSections($versionen[0])) as $sect) {
            $diff_sect->push($sect->name);
            while(! is_null($sect->section)) {
                $sect = $sect->section;
                $diff_sect->push($sect->name);
            }
        }
        $diff_sect = $diff_sect->unique();

        return view('abschlussprojekt.dokumentation.vergleich', [
            'documentation' => $documentation,
            'versionen' => $versionen,
            'diff_sect' => $diff_sect,
        ]);
    }

    /**
     * Übernehme eine per Formular übergebene Vesion als die aktuelle Version einer Projektdokumentation.
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function useVersion(Request $request, Project $project) {
        $documentation = $project->documentation;
        $this->authorize('history', $documentation);

        $request->validate([
            'id' => 'required|int|min:1',
        ]);

        $version = Version::find($request->id);
        if (! $version || ! $documentation->is($version->documentation)) {
            return redirect(route('abschlussprojekt.dokumentation.index', $project))
                ->with('danger', 'Die Version konnte nicht übernommen werden.');
        }
        else {
            $version->touch();
            return redirect(route('abschlussprojekt.dokumentation.index', $project))
                ->with('status', 'Die Version wurde erfolgreich übernommen.');
        }
    }

    /**
     * Lösche eine Version des Dokuments und alle Abschnitte, die nur zu der zu löschenden Version gehören
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteVersion(Request $request, Project $project) {
        $documentation = $project->documentation;
        $this->authorize('history', $documentation);

        $request->validate([
            'id' => 'required|int|min:1',
        ]);

        if ($documentation->versions()->count() < 2) {
            return redirect(route('abschlussprojekt.dokumentation.index', $project))
                ->with('danger', 'Das Dokument hat nur eine Version. Sie kann nicht gelöscht werden.');
        }

        $version = Version::find($request->id);
        if (! $version || ! $documentation->is($version->documentation)) {
            return redirect(route('abschlussprojekt.dokumentation.index', $project))
                ->with('danger', 'Die Version konnte nicht gelöscht werden.');
        }
        else {
            //In der delete-Methode von Version werden, wenn nötig, Abschnitte gelöscht.
            $version->delete();

            return redirect(route('abschlussprojekt.dokumentation.index', $project))
                ->with('status', 'Die Version wurde erfolgreich gelöscht.');
        }
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
        $versionOld = $documentation->latestVersion();
        $version = $versionOld->replicate();
        $version->save();

        //Kopiere nun den Abschnitt, dem ein Bild hinzuzufügen ist
        //Entferne den Original-Abschnitt von der neuen Version und füge die Kopie hinzu
        $sectionOld = Section::find($request->img_section);
        $section = $sectionOld->replicate();
        $sectionsHelp = $versionOld->sections->reject(function ($value, $key) use ($request) {
            return $value->id == $request->img_section;
        });
        $version->sections()->saveMany($sectionsHelp);
        $version->sections()->save($section);
        $section->images()->saveMany($sectionOld->images);

        //Erstelle nun einen neuen Datensatz für images und ordne ihn dem neuen Abschnitt hinzu
        $img = new Image([
            'footnote' => $request->footnote,
            'path' => $request->path,
            'sequence' => $section->images()->count(),
            'height' => 200,
            'width' => 300,
        ]);
        $section->images()->save($img);

        return redirect()->back()->with('status', 'Dem Abschnitt wurde erfolgreich ein Bild zugeordnet.');
    }

    public function detachImage(Request $request, Project $project) {
        $documentation = $project->documentation;
        $this->authorize('addImage', $documentation);

        $request->validate([
            'img_id' => 'required|int|min:1',
            'section_id' => 'required|int|min:1',
        ]);

        //Lege zunächst eine neue Version an
        $versionOld = $documentation->latestVersion();
        $version = $versionOld->replicate();
        $version->save();

        //Kopiere nun den Abschnitt, von dem ein Bild zu entfernen ist
        //Entferne den Original-Abschnitt von der neuen Version und füge die Kopie hinzu
        $sectionOld = Section::find($request->section_id);
        $section = $sectionOld->replicate();
        $sectionsHelp = $versionOld->sections->reject(function ($value, $key) use ($request) {
            return $value->id == $request->section_id;
        });
        $version->sections()->saveMany($sectionsHelp);
        $version->sections()->save($section);

        //Füge dem neuen Abschnitt nun alle Bilder bis auf das zu entfernende hinzu.
        $toDelete = Image::find($request->img_id);
        $imagesHelp = $sectionOld->images->reject(function ($value, $key) use ($toDelete) {
            //Passe die Reihenfolge an
            if ($value->sequence > $toDelete->sequence) {
                $value->sequence = $value->sequence - 1;
            }
            return $value->id == $toDelete->id;
        });
        $section->images()->saveMany($imagesHelp);

        return redirect()->back()->with('status', 'Das Bild wurde erfolgreich von diesem Abschnitt entfernt.');
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
}
