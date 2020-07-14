<?php

namespace App\Http\Controllers;

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

    public function create(Project $project) {
        $proposal = $project->proposal()->create([]);
        $proposal->changedBy()->associate(app()->user);
        $proposal->save();
        $proposal->makeSections();

        return view('abschlussprojekt.antrag.index', [
            'proposal' => $proposal,
        ]);
    }

    public function store(Request $request, Project $project) {
        //TODO validation
        $proposal = $project->proposal;
        foreach($proposal->sections as $section) {
            $name = $section->name;
            $section->text = $request->$name;
        }

        $proposal->save();

        return view('abschlussprojekt.antrag.index', [
            'proposal' => $proposal,
        ]);
    }
}
