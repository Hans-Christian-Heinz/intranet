<?php

namespace App\Http\Controllers;

use App\Project;
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

    public function create(Project $project) {
        $proposal = $project->proposal()->create([]);
        $proposal->changedBy()->associate(app()->user);

        return view('abschlussprojekt.antrag.index', [
            'proposal' => $proposal,
        ]);
    }
}
