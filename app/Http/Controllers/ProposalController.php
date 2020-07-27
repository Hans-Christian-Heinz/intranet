<?php

namespace App\Http\Controllers;

use App\Http\Requests\PdfRequest;
use App\Http\Requests\StoreProposalRequest;
use App\Project;
use App\Proposal;
use App\Traits\SavesSections;
use App\Version;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    use SavesSections;

    public function index(Project $project) {
        if (! $project->proposal) {
            return redirect(route('abschlussprojekt.antrag.create', $project));
        }
        return view('abschlussprojekt.antrag.index', [
            'proposal' => $project->proposal,
            'version' => $project->proposal->latestVersion(),
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
            $this->saveSection($request, $proposal, $versionNew, $versionOld, $old);
        }

        //return redirect(route('abschlussprojekt.antrag.index', $project))->with('status', 'Der Antrag wurde erfolgreich gespeichert.');
        return redirect()->back()->with('status', 'Der Antrag wurde erfolgreich gespeichert.');
    }

    /**
     * Anzeigen aller Versionen des Antrags in einer Tabelle
     *
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function history(Project $project) {
        $proposal = $project->proposal;
        $this->authorize('history', $proposal);

        $versions = $proposal->versions()->with('user')->orderBy('updated_at', 'DESC')->get();
        return view('abschlussprojekt.antrag.history', [
            'proposal' => $proposal,
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
        $proposal = $project->proposal;
        $this->authorize('history', $proposal);

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
        foreach ($proposal->getAllSections($versionen[0])->diff($proposal->getAllSections($versionen[1])) as $sect) {
            $diff_sect->push($sect->name);
            while(! is_null($sect->section)) {
                $sect = $sect->section;
                $diff_sect->push($sect->name);
            }
        }
        foreach ($proposal->getAllSections($versionen[1])->diff($proposal->getAllSections($versionen[0])) as $sect) {
            $diff_sect->push($sect->name);
            while(! is_null($sect->section)) {
                $sect = $sect->section;
                $diff_sect->push($sect->name);
            }
        }
        $diff_sect = $diff_sect->unique();

        return view('abschlussprojekt.antrag.vergleich', [
            'proposal' => $proposal,
            'versionen' => $versionen,
            'diff_sect' => $diff_sect,
        ]);
    }

    /**
     * Übernehme eine per Formular übergebene Vesion als die aktuelle Version eines Projektantrags.
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function useVersion(Request $request, Project $project) {
        $proposal = $project->proposal;
        $this->authorize('history', $proposal);

        $request->validate([
            'id' => 'required|int|min:1',
        ]);

        $version = Version::with('proposal')->find($request->id);

        if (request()->is('admin*')) {
            $route = 'admin.abschlussprojekt.antrag.index';
        }
        else {
            $route = 'abschlussprojekt.antrag.index';
        }
        if (! $version || ! $proposal->is($version->proposal)) {
            return redirect(route($route, $project))
                ->with('danger', 'Die Version konnte nicht übernommen werden.');
        }
        else {
            $version->touch();
            return redirect(route($route, $project))
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
        $proposal = $project->proposal;
        $this->authorize('history', $proposal);

        $request->validate([
            'id' => 'required|int|min:1',
        ]);

        if (request()->is('admin*')) {
            $route = 'admin.abschlussprojekt.antrag.index';
        }
        else {
            $route = 'abschlussprojekt.antrag.index';
        }
        if ($proposal->versions()->count() < 2) {
            return redirect(route($route, $project))
                ->with('danger', 'Das Dokument hat nur eine Version. Sie kann nicht gelöscht werden.');
        }

        $version = Version::find($request->id);
        if (! $version || ! $proposal->is($version->proposal)) {
            return redirect(route($route, $project))
                ->with('danger', 'Die Version konnte nicht gelöscht werden.');
        }
        else {
            //In der delete-Methode von Version werden, wenn nötig, Abschnitte gelöscht.
            $version->delete();

            return redirect(route($route, $project))
                ->with('status', 'Die Version wurde erfolgreich gelöscht.');
        }
    }

    /**
     * @param PdfRequest $request
     * @param Project $project
     * @return string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Mpdf\MpdfException
     */
    public function pdf(PdfRequest $request, Project $project) {
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
        ])->render());

        return $mpdf->Output($title . '.pdf', 'I');
    }
}
