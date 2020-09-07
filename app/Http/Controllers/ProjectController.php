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
}
