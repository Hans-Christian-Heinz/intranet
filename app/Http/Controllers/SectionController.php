<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Http\Requests\CreateSectionRequest;
use App\Http\Requests\EditSectionRequest;
use App\Notifications\CustomNotification;
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
        if ($request->section_id == 0) {
            return $this->createDocumentationSection($request, $documentation, $versionOld);
        }
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
        $version->sections()->save($subsection, ['sequence' => $section->getSections($versionOld)->count()]);
        $section->sections()->save($subsection);

        if (app()->user->isNot($project->user)) {
            $project->user->notify(new CustomNotification(app()->user->full_name, 'Änderungen an der Projektdokumentation',
                'An Ihrer Projektdokumentation wurden vom Absender Änderungen vorgenommen.'));
        }

        return redirect()->back()->with('status', 'Es wurde erfolgreich ein neuer Abschnitt erstellt.');
    }

    /**
     * Hilfsmethode, um einen Abschnitt, der direkt der Dokumentation zugeordnet ist, zu erstellen.
     *
     * @param CreateSectionRequest $request
     * @param Documentation $documentation
     * @param Version $versionOld
     * @return \Illuminate\Http\RedirectResponse
     */
    private function createDocumentationSection(CreateSectionRequest $request, Documentation $documentation, Version $versionOld) {
        /*if (! (app()->user->isAdmin() || $documentation->getUser()->is(app()->user))) {
            return redirect()->back()->with('danger', 'Sie sind nicht berechtigt, einen neuen Abschnitt für diese Dokumentation zu erstellen.');
        }*/
        $this->authorize('createForDoc', new Section());

        //Erstelle zunächst eine neue Version
        $version = new Version();
        $version->user()->associate(app()->user);
        $documentation->versions()->save($version);

        //Füge der neuen Version alle alten Abschnitte hinzu
        foreach($versionOld->sections as $help) {
            $version->sections()->save($help, ['sequence' => $help->pivot->sequence,]);
        }

        //Nun: Erstelle den neuen Abschnitt und füge ihn dm Elternabschnitt und der neuen Version hinzu
        $subsection = new Section([
            'name' => $request->name,
            'heading' => $request->heading,
            'tpl' => $request->tpl,
        ]);
        $version->sections()->save($subsection, ['sequence' => $documentation->getSections($versionOld)->count()]);
        $documentation->sections()->save($subsection);

        return redirect()->back()->with('status', 'Es wurde erfolgreich ein neuer Abschnitt erstellt.');
    }

    /**
     * @param Project $project
     * @param Section $section
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Project $project, Section $section) {
        $this->authorize('create', $section);

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

        if (app()->user->isNot($project->user)) {
            $project->user->notify(new CustomNotification(app()->user->full_name, 'Änderungen an der Projektdokumentation',
                'An Ihrer Projektdokumentation wurden vom Absender Änderungen vorgenommen.'));
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
        $this->authorize('create', $section);

        $documentation = $project->documentation;
        $versionOld = $documentation->latestVersion();
        //Erstelle zunächst eine neue Version
        $version = new Version();
        $version->user()->associate(app()->user);
        $documentation->versions()->save($version);

        //$sections sind nun genau die Abschnitte, die nicht der zu bearbeitende oder dessen Unterabschnitte sind.
        $sections = $versionOld->sections->reject(function ($value) use ($section) {
            $reject = $value->is($section);
            $help = $value;
            while(! is_null($help->section)) {
                if ($help->section->is($section)) {
                    $reject = true;
                    break;
                }
                $help = $help->section;
            }

            return $reject;
        });
        //Hilfsmethode zum Speichern des zu bearbeitenden Abschnitts und seiner Unterabschnitte
        $this->helpEditSection($section, $versionOld, $version, $section->getParent(), $request);

        //Hilfsvariable zum Anpassen der Reihenfolge:
        $reihenfolge = 0;
        foreach($sections as $s) {
            //Reihenfolge anpassen: Stelle sicher, dass die Reihenfolge (sequence) bei 0 startet und sich immer um genau 1 erhöht.
            //Beachte: Die Abschnitte sind bereits geordnet; es muss nur die Position übersprungen werden, an der
            //der bearbeitete Abschnitt eingefügt ist.
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

        if (app()->user->isNot($project->user)) {
            $project->user->notify(new CustomNotification(app()->user->full_name, 'Änderungen an der Projektdokumentation',
                'An Ihrer Projektdokumentation wurden vom Absender Änderungen vorgenommen.'));
        }

        return redirect()->back()->with('status', 'Der Abschnitt wurde erfolgreich bearbeitet.');
    }

    /**
     * Hilfsmethode zum Bearbeiten von Abschnitten: Es werden der bearbeitete Abschnitt und alle Unterabschnitte gespeichert.
     *
     * @param Section $section
     * @param Version $versionOld
     * @param Version $versionNew
     * @param $parent
     * @param null $request
     */
    private function helpEditSection(Section $section, Version $versionOld, Version $versionNew, $parent, $request = null) {
        //$request ist dann null, wenn ein Unterabschnitt kopiert werden soll.
        //Unterabschnitte werden kopiert, da der zu verändernde Abschnitt kopiert und gespeichert wird: Sie bekommen also einen neuen Überabschnitt.
        if (is_null($request)) {
            $sequence = $section->pivot->sequence;
            $newSect = new Section();
            $newSect->name = $section->name;
            $newSect->heading = $section->heading;
            $newSect->tpl = $section->tpl;
            $newSect->text = $section->text;
        }
        //Lege einen neuen ABschnitt an, der statt des zu verändernden gespeichert wird
        else {
            $sequence = $request->sequence;
            $newSect = new Section();
            $newSect->name = $request->name;
            $newSect->heading = $request->heading;
            $newSect->tpl = $request->tpl;
            $newSect->text = $section->text;
        }
        $parent->sections()->save($newSect);
        $versionNew->sections()->save($newSect, ['sequence' => $sequence]);
        foreach ($section->images as $img) {
            $newSect->images()->save($img, ['sequence' => $img->pivot->sequence]);
        }
        //Rufe die Methode rekursiv für alle Unterabschnitte auf, um sie für die neue Version zu kopieren.
        foreach($versionOld->sections()->where('sections.section_id', $section->id)->get() as $sect) {
            $this->helpEditSection($sect, $versionOld, $versionNew, $newSect);
        }
    }

    /**
     * Sperre einen Abschnitt oder gebe einen gesperrten Abschnitt frei.
     * Ausbilder können einen Abschnitt als endgültig festlegen.
     *
     * @param Project $project
     * @param Section $section
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function lock(Project $project, Section $section) {
        $this->authorize('lock', $section);

        $document = $section->getUltimateParent();
        if ($section->is_locked) {
            $section->lock(false);
            //Gehe alle Abschnitte der Dokumentation durch; wenn keine gesperrt sind, lasse Änderungen durch den Veränderungsverlauf zu.
            //Beachte nur die aktuellste Version, da ein gesperrter Abschnitt in der aktuellsten Version vorhanden sein muss.
            if ($document->sections()->where('is_locked', 1)->count() == 0) {
                $document->vc_locked = false;
                $document->save();
            }

            return redirect()->back()->with('status', 'Der Abschnitt wurde erfolgreich freigegeben. Er darf wieder geändert werden.');
        }
        else {
            $section->lock(true);
            $document->vc_locked = true;
            $section->save();
            $document->save();

            return redirect()->back()->with('status', 'Der Abschnitt wurde erfolgreich gesperrt. Er darf nicht mehr geändert werden.');
        }
    }
}
