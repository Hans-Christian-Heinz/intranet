<?php

namespace App\Http\Controllers;

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


        return redirect(route('abschlussprojekt.antrag.index', $project))->with('status', 'Der Antrag wurde erfolgreich angeleget.');
    }

    /**
     * Beachte: In Zukunft wird eine neue Version-Instanz angelegt, mit der alle unveränderten Abschnitte assoziiert werden;
     * nur veränderte Abschnitte werden neu angelegt.
     *
     * @param StoreProposalRequest $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreProposalRequest $request, Project $project) {
        $this->authorize('store', $project->proposal);

        $proposal = $project->proposal;
        $versionOld = $proposal->latestVersion();
        $sectionsOld = $proposal->getCurrentSections();

        $versionNew = new Version();
        $versionNew->user()->associate(app()->user);
        $versionNew->proposal()->associate($proposal);
        $versionNew->save();
        foreach($sectionsOld as $old) {
            $this->saveSection($request, $proposal, $versionNew, $versionOld, $old);
        }

        return redirect(route('abschlussprojekt.antrag.index', $project))->with('status', 'Der Antrag wurde erfolgreich gespeichert.');
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

        $version = Version::find($request->id);
        if (! $version || ! $proposal->is($version->proposal)) {
            return redirect(route('abschlussprojekt.antrag.index', $project))
                ->with('danger', 'Die Version konnte nicht übernommen werden.');
        }
        else {
            $version->touch();
            return redirect(route('abschlussprojekt.antrag.index', $project))
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

        if ($proposal->versions()->count() < 2) {
            return redirect(route('abschlussprojekt.antrag.index', $project))
                ->with('danger', 'Das Dokument hat nur eine Version. Sie kann nicht gelöscht werden.');
        }

        $version = Version::find($request->id);
        if (! $version || ! $proposal->is($version->proposal)) {
            return redirect(route('abschlussprojekt.antrag.index', $project))
                ->with('danger', 'Die Version konnte nicht gelöscht werden.');
        }
        else {
            //In der delete-Methode von Version werden, wenn nötig, Abschnitte gelöscht.
            $version->delete();

            return redirect(route('abschlussprojekt.antrag.index', $project))
                ->with('status', 'Die Version wurde erfolgreich gelöscht.');
        }
    }
}
