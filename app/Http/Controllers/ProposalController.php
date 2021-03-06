<?php

namespace App\Http\Controllers;

use App\Helpers\General\IncrementCounter;
use App\Http\Requests\PdfRequest;
use App\Http\Requests\StoreProposalRequest;
use App\Notifications\CustomNotification;
use App\Project;
use App\Proposal;
use App\Traits\ControllsDocuments;
use App\Traits\SavesSections;
use App\Version;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    use SavesSections, ControllsDocuments;

    public function index(Project $project) {
        $proposal = $project->proposal()->with('comments')->first();
        $this->authorize('index', $proposal);

        if (! $proposal) {
            return redirect(route('abschlussprojekt.antrag.create', $project));
        }

        return view('abschlussprojekt.antrag.index', [
            'proposal' => $proposal,
            'version' => $proposal->latestVersion(),
            'disable' => app()->user->isNot($proposal->lockedBy),
        ]);
    }

    /**
     * Erstelle einen neuen Antrag für das übergebene Projekt
     *
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Project $project) {
        $proposal = new Proposal();
        $proposal->project()->associate($project);
        $proposal->save();
        $project->proposal()->associate($proposal);
        $project->save();
        $version = new Version();
        $version->user()->associate(app()->user);
        $proposal->versions()->save($version);
        $proposal->makeSections(Proposal::SECTIONS, $version);

        if (request()->is('admin*')) {
            $route = 'admin.abschlussprojekt.antrag.index';
        }
        else {
            $route = 'abschlussprojekt.antrag.index';
        }
        return redirect(route($route, $project))->with('status', 'Der Antrag wurde erfolgreich angeleget.');
    }

    /**
     * Beachte: In Zukunft wird eine neue Version-Instanz angelegt, mit der alle unveränderten Abschnitte assoziiert werden;
     * nur veränderte Abschnitte werden neu angelegt.
     * Wird im Moment nicht verwendet.
     *
     * @param StoreProposalRequest $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreProposalRequest $request, Project $project) {
        $this->authorize('store', $project->proposal);

        $proposal = $project->proposal;
        $versionOld = $proposal->latestVersion();
        $sectionsOld = $versionOld->sections->filter(function ($value, $key) use ($proposal) {
            return $value->proposal_id == $proposal->id;
        });

        $versionNew = new Version();
        $versionNew->user()->associate(app()->user);
        $versionNew->proposal()->associate($proposal);
        $versionNew->save();
        foreach($sectionsOld as $old) {
            if (!$this->saveSection($request, $proposal, $versionNew, $versionOld, $old)) {
                return redirect()->back()->with('danger', 'Der Abschnitt ' . $old->heading
                    . ' ist gesperrt und darf nicht mehr verändert werden. Alle Änderungen wurden verworfen.');
            }
        }

        //update timestamps:
        $proposal->touch();

        if (app()->user->isNot($project->user)) {
            $project->user->notify(new CustomNotification(app()->user->full_name, 'Änderungen am Projektantrag',
                'An Ihrem Projektantrag wurden vom Absender Änderungen vorgenommen.'));
        }

        //return redirect(route('abschlussprojekt.antrag.index', $project))->with('status', 'Der Antrag wurde erfolgreich gespeichert.');
        return redirect()->back()->with('status', 'Der Antrag wurde erfolgreich gespeichert.');
    }

    /**
     * Ähnlich wie store, geändert, um einen Fehler bei simultanen bzw. sehr zeitnahen post-requests zu verhindern.
     * Zunächst werden über die Methode saveSectionsNew (Trait SavesSections) neue Section-Instanzen gespeichert,
     * solange der bestehende Inhalt nicht mit dem entsprechenden Feld der Request übereinstimmt.
     * Erhalten wird (von saveSectionsNew) ein Array des Formats [$sectionId => ['sequence' => $sequence], ...], der
     * verwendet wird, um einer neuen Version-Instanz die aktuellsten Section-Instanzen zuzuordnen.
     * Die Änderung bzgl. store ist, dass Die Version-Instanz erst erstellt wird, wenn alle Section-Datensätze vorliegen,
     * sodass diese unmittelbar assoziiert werden können.
     *
     * @param StoreProposalRequest $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeNew(StoreProposalRequest $request, Project $project) {
        $this->authorize('store', $project->proposal);

        $proposal = $project->proposal;
        $versionOld = $proposal->latestVersion();

        $sectionsHelp = $this->saveSectionsNew($request, $proposal, $versionOld);

        if($sectionsHelp) {
            $versionNew = new Version();
            $versionNew->user()->associate(app()->user);
            $versionNew->proposal()->associate($proposal);
            $versionNew->save();
            $versionNew->sections()->attach($sectionsHelp);

            //update timestamps:
            $proposal->touch();

            if (app()->user->isNot($project->user)) {
                $project->user->notify(new CustomNotification(app()->user->full_name, 'Änderungen am Projektantrag',
                    'An Ihrem Projektantrag wurden vom Absender Änderungen vorgenommen.'));
            }

            //return redirect(route('abschlussprojekt.antrag.index', $project))->with('status', 'Der Antrag wurde erfolgreich gespeichert.');
            return redirect()->back()->with('status', 'Der Antrag wurde erfolgreich gespeichert.');
        }
        else {
            $this->deleteUnusedSections();
            return redirect()->back()->with('error', 'Beim Speichern des Antrags ist ein Fehler aufgetreten.');
        }
    }

    public function lock(Project $project, Proposal $proposal) {
        $this->authorize('lock', $proposal);
        return $this->lockDocument($proposal);
    }

    public function release(Project $project, Proposal $proposal) {
        $this->authorize('lock', $proposal);
        return $this->releaseDocument($proposal);
    }

    /**
     * @param PdfRequest $request
     * @param Project $project
     * @return string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Mpdf\MpdfException
     */
    public function pdf(PdfRequest $request, Project $project) {
        if ($request->vorschau_pdf) {
            return $this->vorschau_pdf($request);
        }

        $proposal = $project->proposal;
        $this->authorize('pdf', $proposal);

        $title = 'Projektantrag ' . $project->user->full_name;

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

        $mpdf->DefHTMLFooterByName('footer',
'<table style="width: 100%; border: none; border-top: 1px solid black;">
    <tr style="border: none;">
        <td style="border: none;">' . $project->user->full_name . '</td>
        <td style="border:none; text-align: right;">{PAGENO}/{nbpg}</td>
    </tr>
</table>');

        $mpdf->SetTitle($title);

        $mpdf->WriteHTML(view('pdf.antrag', [
            'project' => $proposal->project()->with('user')->with('supervisor')->first(),
            'proposal' => $proposal,
            'format' => $request->all(),
            'version' => $proposal->latestVersion(),
            'table_nr' => new IncrementCounter(),
            'inhalt_counter' => new IncrementCounter(),
        ])->render());

        return $mpdf->Output($title . '.pdf', 'I');
    }
}
