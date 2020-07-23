<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSectionRequest;
use App\Project;
use App\Section;
use App\Version;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * @param CreateSectionRequest $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(CreateSectionRequest $request, Project $project) {
        $documentation = $project->documentation;
        $versionOld = $documentation->latestVersion();
        $sectionOld = $versionOld->sections->where('id', $request->section_id)->shift();
        $this->authorize('create', $sectionOld);

        //Erstelle zunächst eine neue Version
        $version = new Version();
        $version->user()->associate(app()->user);
        $documentation->versions()->save($version);

        //Falls der Abschnitt keine Unterabschnitte anzeigen kann, ändere sein Template
        if(strpos($sectionOld->tpl, 'parent_section') === false) {
            $section = $sectionOld->replicate();
            $section->tpl = 'parent_section';
            $sectionOld->getParent()->sections()->save($section);
            $section->sections()->saveMany($sectionOld->sections);
        }
        else {
            $section = $sectionOld;
        }

        //Füge der neuen Version alle alten Abschnitte hinzu
        $sectionsHelp = $versionOld->sections->reject(function ($value, $key) use ($request) {
            return $value->id == $request->section_id;
        });
        foreach($sectionsHelp as $help) {
            $version->sections()->save($help, ['sequence' => $help->pivot->sequence,]);
        }
        $version->sections()->save($section, ['sequence' => $sectionOld->pivot->sequence,]);

        //Nun: Erstelle den neuen Abschnitt und füge ihn dm Elternabschnitt und der neuen Version hinzu
        $subsection = new Section([
            'name' => $request->name,
            'heading' => $request->heading,
            'tpl' => $request->tpl,
        ]);
        $version->sections()->save($subsection, ['sequence' => $section->sections()->count()]);
        $section->sections()->save($subsection);

        return redirect()->back()->with('status', 'Es wurde erfolgreich ein neuer Abschnitt erstellt.');
    }
}
