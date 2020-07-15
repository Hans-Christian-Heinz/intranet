<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProposalRequest;
use App\Phase;
use App\Project;
use App\Proposal;
use App\Traits\SavesSections;
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
        $proposal->changedBy()->associate(app()->user);
        $proposal->project()->associate($project);
        $proposal->save();
        $project->proposal()->associate($proposal);
        $project->save();
        $proposal->makeSections(Proposal::SECTIONS);

        return redirect(route('abschlussprojekt.antrag.index', $project))->with('status', 'Der Antrag wurde erfolgreich angeleget.');
    }

    /**
     * Beachte: beim Ändern eines Dokuments wird eine neue Instanz erzeugt; die alte Instanz (der alte Datensatz) bleibt unverändert.
     *
     * @param StoreProposalRequest $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(StoreProposalRequest $request, Project $project) {
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
    }
}
