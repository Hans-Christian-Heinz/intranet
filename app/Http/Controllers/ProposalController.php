<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProposalRequest;
use App\Project;
use App\Proposal;
use App\Traits\SavesSections;
use App\Version;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use function foo\func;

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

    /*
     * Beachte: beim Ändern eines Dokuments wird eine neue Instanz erzeugt; die alte Instanz (der alte Datensatz) bleibt unverändert.
     * Beachte: In Zukunf wird eine neue Version-Instanz angelegt, mit der alle unveränderten Abschnitte assoziiert werden;
     * nur veränderte Abschnitte werden neu angelegt.
     *
     * @param StoreProposalRequest $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
    public function storeOld(StoreProposalRequest $request, Project $project) {
        $this->authorize('store', $project->proposal);

        $sectionsOld = $project->proposal->sections;

        $proposal = new Proposal();
        $proposal->changedBy()->associate(app()->user);
        $proposal->project()->associate($project);
        $proposal->save();
        foreach($sectionsOld as $old) {
            $this->saveSection($request, $proposal, $old);
        }

        //$proposal->save();
        $project->proposal()->associate($proposal);
        //$project->save();

        //save changes to the project itself
        $project->update([
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return redirect(route('abschlussprojekt.antrag.index', $project))->with('status', 'Der Antrag wurde erfolgreich gespeichert.');
    }*/
}
