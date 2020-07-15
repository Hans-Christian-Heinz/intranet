<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Project;
use App\Traits\SavesSections;
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
        ]);
    }

    /**
     * Erstelle eine neue Dokumentation für das übergebene Projekt
     *
     * @param Project $project
     */
    public function create(Project $project) {
        $documentation = new Documentation();
        $documentation->changedBy()->associate(app()->user);
        $documentation->project()->associate($project);
        $documentation->save();
        $project->documentation()->associate($documentation);
        $project->save();
        $documentation->makeSections(Documentation::SECTIONS);

        return view('abschlussprojekt.antrag.index', [
            'proposal' => $documentation,
        ])->with('status', 'Der Antrag wurde erfolgreich angelegt.');
    }

    /**
     * Beachte: beim Ändern eines Dokuments wird eine neue Instanz erzeugt; die alte Instanz (der alte Datensatz) bleibt unverändert.
     *
     * @param Request $request
     * @param Project $project
     */
    public function store(Request $request, Project $project) {
        //TODO
    }
}
