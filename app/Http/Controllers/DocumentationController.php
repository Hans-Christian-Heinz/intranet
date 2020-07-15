<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Http\Requests\StoreDocumentationRequest;
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

        return redirect(route('abschlussprojekt.dokumentation.index', $project))->with('status', 'Die Dokumentation wurde erfolgreich angeleget.');
    }

    /**
     * Beachte: beim Ändern eines Dokuments wird eine neue Instanz erzeugt; die alte Instanz (der alte Datensatz) bleibt unverändert.
     *
     * @param StoreDocumentationRequest $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(StoreDocumentationRequest $request, Project $project) {
        $this->authorize('store', $project->documentation);

        $sectionsOld = $project->documentation->sections;

        $documentation = new Documentation();
        $documentation->changedBy()->associate(app()->user);
        $documentation->project()->associate($project);
        $documentation->save();
        foreach($sectionsOld as $old) {
            $this->saveSection($request, $documentation, $old);
        }

        $documentation->update([
            'shortTitle' => $request->shortTitle,
            'longTitle' => $request->longTitle,
        ]);

        //$documentation->save();
        $project->documentation()->associate($documentation);
        $project->save();

        return redirect(route('abschlussprojekt.dokumentation.index', $project))->with('status', 'Die Dokumentation wurde erfolgreich gespeichert.');
    }
}
