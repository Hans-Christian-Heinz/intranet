<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSectionRequest;
use App\Http\Requests\EditSectionRequest;
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

    /**
     * @param Project $project
     * @param Section $section
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Project $project, Section $section) {
        $this->authorize('delete', $section);

        $documentation = $project->documentation;
        $versionOld = $documentation->latestVersion();
        //Erstelle zunächst eine neue Version
        $version = new Version();
        $version->user()->associate(app()->user);
        $documentation->versions()->save($version);


        //$sections sind nun genau die Abschnitte, die nicht zu dem zu löschenden gehören und nicht der zu löschende sind.
        $sections = $versionOld->sections->reject(function ($value, $key) use ($section) {
            $s = $value;
            while(isset($s->section)) {
                if ($s->section->is($section)) {
                    return true;
                }
                $s = $s->section;
            }
            return $value->is($section);
        });

        $sequence = 0;
        foreach ($sections as $s) {
            //Falls der Abschnitt $s auf der gleichen Ebene ist wie der zu löschende muss ggf. die Position angepasst werden
            if ($s->getParent()->is($section->getParent())) {
                $version->sections()->save($s, ['sequence' => $sequence]);
                $sequence++;
            }
            else {
                $version->sections()->save($s, ['sequence' => $s->pivot->sequence]);
            }
        }

        return redirect()->back()->with('status', 'Der Abschnitt wurde erfolgreich gelöscht.');
    }

    /**
     * @param EditSectionRequest $request
     * @param Project $project
     * @param Section $section
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(EditSectionRequest $request, Project $project, Section $section) {
        $this->authorize('edit', $section);

        $documentation = $project->documentation;
        $versionOld = $documentation->latestVersion();
        //Erstelle zunächst eine neue Version
        $version = new Version();
        $version->user()->associate(app()->user);
        $documentation->versions()->save($version);


        //$sections sind nun genau die Abschnitte, die nicht der zu bearbeitende sind.
        $sections = $versionOld->sections->reject(function ($value, $key) use ($section) {
            return $value->is($section);
        });

        //Erstelle eine neue Section-Instanz; sie wird in der neuen Version die zu bearbeitende ersetzen
        $sectionNew = new Section([
            'name' => $request->name,
            'heading' => $request->heading,
            'tpl' => $request->tpl,
            'text' => $section->text,
        ]);

        $sectionNew->sections()->saveMany($section->sections);
        $section->getParent()->sections()->save($sectionNew);
        //Hilfsvariable zum Anpassen der Reihenfolge:
        $reihenfolge = 0;
        foreach($sections as $s) {
            //Reihenfolge anpassen: Stelle sicher, dass die Reihenfolge (sequence) bei 0 startet und sich immer um genau 1 erhöht.
            //Beachte: Die Abschnitte sind bereits geordnet; es muss nur die Position übersprungen werden, an der
            //der bearbeitete Abschnitt einzufügen ist.
            if ($s->getParent()->is($section->getParent())) {
                if ($reihenfolge == $request->sequence) {
                    $reihenfolge++;
                }
                $version->sections()->save($s, ['sequence' => $reihenfolge,]);
                $reihenfolge++;
            }
            else {
                $version->sections()->save($s, ['sequence' => $s->pivot->sequence,]);
            }
        }
        $version->sections()->save($sectionNew, ['sequence' => $request->sequence,]);

        return redirect()->back()->with('status', 'Der Abshcnitt wurde erfolgreich bearbeitet.');
    }
}
