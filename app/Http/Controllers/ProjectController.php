<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index() {
        if (!app()->user->project) {
            app()->user->project()->create();
            app()->user->save();

            return redirect(route('abschlussprojekt.index'));
        }
        return view('abschlussprojekt.index', [
            'project' => app()->user->project,
        ]);
    }

    /*
    public function create() {
        $project = app()->user->project()->create();
        /*$project = new Project();
        $project->user()->associate(app()->user);
        $project->save();
        app()->user->project()->associate($project);
        app()->user->save();*
        return redirect(route('abschlussprojekt.index'), $project)->with('status', 'Das Projekt wurde erfolgreich erstellt.');
    }*/
}
