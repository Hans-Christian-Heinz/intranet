<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index() {
        return view('abschlussprojekt.index', [
            'project' => app()->user->project,
        ]);
    }

    public function create(Request $request) {
        $values = $request->validate([
            'topic' => 'string|required|max:255',
        ]);

        app()->user->project()->create($values);
        return redirect(route('abschlussprojekt.index'))->with('status', 'Das Thema wurde erfolgreich gewählt.');
    }

    public function update(Request $request, Project $project) {
        $this->authorize('update', $project);

        $values = $request->validate([
            'topic' => 'string|required|max:255',
            'start' => 'nullable|date|after:today',
            'end' => 'nullable|date|after:start',
        ]);

        $project->update($values);
        return redirect(route('abschlussprojekt.index'))->with('status', 'Das Thema wurde erfolgreich geändert.');

    }
}
