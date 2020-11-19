<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChooseSupervisorRequest;
use App\Project;
use Illuminate\Http\Request;
use App\User;

class AdminProjectController extends Controller
{
    /**
     * Zeige eine Liste aller Abschlussprojekte, aufgeteilt nach Abschlussjahr
     */
    public function index() {
        $all_projects = Project::with('supervisor')->get()->sortBy(function ($value) {
            return $value->user->full_name;
        });
        $all_projects = $all_projects->reject(function($project) {
            return $project->user->isAdmin;
        });
        $projects = [];
        //Teile die Projekte nach dem Abschlussjahr auf.
        foreach($all_projects as $p) {
            $jahr = (string)$p->pruefungs_jahr;
            if (! array_key_exists($jahr, $projects)) {
                $projects[$jahr] = ['Anwendungsentwicklung' => collect(), 'Systemintegration' => collect()];
            }
            $fachrichtung = $p->user->fachrichtung;
            //Sollte eigentlich nicht vorkommen
            if ($fachrichtung != 'Ausbilder') {
                $projects[$jahr][$fachrichtung]->push($p);
            }
        }

        ksort($projects);

        $admins = User::where('fachrichtung', 'Ausbilder')->get();

        return view('admin.abschlussprojekt.index', compact('projects', 'admins'));
    }

    public function betreuer(ChooseSupervisorRequest $request, Project $project) {
        $project->supervisor()->associate(User::find($request->supervisor_id));
        $project->save();

        return redirect()->back()->with('status', 'Dem Projekt von ' . $project->user->full_name
            . ' wurde ein neuer Betreuer zugeteilt.');
    }
}
