<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

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

        return view('admin.abschlussprojekt.index', compact('projects'));
    }
}
