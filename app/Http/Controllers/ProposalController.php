<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProposalRequest;
use App\Phase;
use App\Project;
use App\Proposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
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
        $proposal->makeSections();

        return view('abschlussprojekt.antrag.index', [
            'proposal' => $proposal,
        ]);
    }

    /**
     * Beachte: beim Ändern eines Dokuments wird eine neue Instanz erzeugt; die alte Instanz (der alte Datensatz) bleibt unverändert.
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(StoreProposalRequest $request, Project $project) {
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

        return view('abschlussprojekt.antrag.index', [
            'proposal' => $proposal,
        ]);
    }

    /**
     * Hilfsmethode zum Speichern eines Abschnitts.
     * Beachte: Beim Speichern wird ein neuer Eintrag in der Datenbank angelegt; der alte Eintrag bleibt unverändert.
     *
     * @param Request $request
     * @param $parent
     * @param $old
     */
    private function saveSection(Request $request, $parent, $old) {
        $section = $old->replicate();
        $name = $old->name;
        $section->text = $request->$name;
        $parent->sections()->save($section);
        //ggf Unterabschnitte
        foreach ($old->sections as $child) {
            $this->saveSection($request, $section, $child);
        }
    }
}
