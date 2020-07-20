<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Http\Requests\StoreDocumentationRequest;
use App\Project;
use App\Traits\SavesSections;
use App\Version;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    use SavesSections;

    public function index(Project $project) {
        if (! $project->documentation) {
            return redirect(route('abschlussprojekt.dokumentation.create', $project));
        }
        return view('abschlussprojekt.dokumentation.index', [
            'documentation' => $project->documentation,
            'version' => $project->documentation->latestVersion(),
        ]);
    }

    /**
     * Erstelle eine neue Dokumentation für das übergebene Projekt
     *
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Project $project) {
        $documentation = new Documentation();
        $documentation->project()->associate($project);
        $documentation->save();
        $project->documentation()->associate($documentation);
        $project->save();
        $version = new Version();
        $version->user()->associate(app()->user);
        $documentation->versions()->save($version);
        $documentation->makeSections(Documentation::SECTIONS, $version);

        return redirect(route('abschlussprojekt.dokumentation.index', $project))->with('status', 'Die Dokumentation wurde erfolgreich angeleget.');
    }

    /**
     * Beachte: beim Ändern eines Dokuments wird eine neue Instanz erzeugt; die alte Instanz (der alte Datensatz) bleibt unverändert.
     *
     * @param StoreDocumentationRequest $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreDocumentationRequest $request, Project $project) {
        $this->authorize('store', $project->documentation);

        $documentation = $project->documentation;
        $versionOld = $documentation->latestVersion();
        $sectionsOld = $documentation->getCurrentSections();

        $versionNew = new Version();
        $versionNew->user()->associate(app()->user);
        $versionNew->documentation()->associate($documentation);
        $versionNew->save();
        foreach($sectionsOld as $old) {
            $this->saveSection($request, $documentation, $versionNew, $versionOld, $old);
        }

        return redirect(route('abschlussprojekt.dokumentation.index', $project))->with('status', 'Die Dokumentation wurde erfolgreich gespeichert.');
    }

    /**
     * Anzeigen aller Versionen der Dokumentation in einer Tabelle
     *
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function history(Project $project) {
        $documentation = $project->documentation;
        $this->authorize('history', $documentation);

        $versions = $documentation->versions()->with('user')->orderBy('updated_at', 'DESC')->get();
        return view('abschlussprojekt.dokumentation.history', [
            'documentation' => $documentation,
            'versions' => $versions,
        ]);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function vergleich(Request $request, Project $project) {
        $documentation = $project->documentation;
        $this->authorize('history', $documentation);

        //Es müssen genau zwei Versionen ausgewählt werden
        $request->validate([
            'vergleichen' => 'required|array|size:2',
        ]);

        $versionen = [];
        foreach ($request->vergleichen as $v_id) {
            array_push($versionen, Version::with('user')->find($v_id));
        }

        //Suche nach Unterschieden: Suche nach Section-Instanzen, die zu einer der beiden Versionen gehören, nicht zu beiden
        //Suche in beiden Richtungen nach Unterschieden, falls zwei Versionen verschiedene Abschnitte haben
        $diff_sect = collect([]);
        foreach ($documentation->getAllSections($versionen[0])->diff($documentation->getAllSections($versionen[1])) as $sect) {
            $diff_sect->push($sect->name);
            while(! is_null($sect->section)) {
                $sect = $sect->section;
                $diff_sect->push($sect->name);
            }
        }
        foreach ($documentation->getAllSections($versionen[1])->diff($documentation->getAllSections($versionen[0])) as $sect) {
            $diff_sect->push($sect->name);
            while(! is_null($sect->section)) {
                $sect = $sect->section;
                $diff_sect->push($sect->name);
            }
        }
        $diff_sect = $diff_sect->unique();

        return view('abschlussprojekt.dokumentation.vergleich', [
            'documentation' => $documentation,
            'versionen' => $versionen,
            'diff_sect' => $diff_sect,
        ]);
    }

    /**
     * Übernehme eine per Formular übergebene Vesion als die aktuelle Version einer Projektdokumentation.
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function useVersion(Request $request, Project $project) {
        $documentation = $project->documentation;
        $this->authorize('history', $documentation);

        $request->validate([
            'id' => 'required|int|min:1',
        ]);

        $version = Version::find($request->id);
        if (! $version || ! $documentation->is($version->documentation)) {
            return redirect(route('abschlussprojekt.dokumentation.index', $project))
                ->with('danger', 'Die Version konnte nicht übernommen werden.');
        }
        else {
            $version->touch();
            return redirect(route('abschlussprojekt.dokumentation.index', $project))
                ->with('status', 'Die Version wurde erfolgreich übernommen.');
        }
    }

    /**
     * Lösche eine Version des Dokuments und alle Abschnitte, die nur zu der zu löschenden Version gehören
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteVersion(Request $request, Project $project) {
        $documentation = $project->documentation;
        $this->authorize('history', $documentation);

        $request->validate([
            'id' => 'required|int|min:1',
        ]);

        if ($documentation->versions()->count() < 2) {
            return redirect(route('abschlussprojekt.dokumentation.index', $project))
                ->with('danger', 'Das Dokument hat nur eine Version. Sie kann nicht gelöscht werden.');
        }

        $version = Version::find($request->id);
        if (! $version || ! $documentation->is($version->documentation)) {
            return redirect(route('abschlussprojekt.dokumentation.index', $project))
                ->with('danger', 'Die Version konnte nicht gelöscht werden.');
        }
        else {
            //In der delete-Methode von Version werden, wenn nötig, Abschnitte gelöscht.
            $version->delete();

            return redirect(route('abschlussprojekt.dokumentation.index', $project))
                ->with('status', 'Die Version wurde erfolgreich gelöscht.');
        }
    }
}
