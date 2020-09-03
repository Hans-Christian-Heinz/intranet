<?php

namespace App\Http\Controllers;

use App\Project;
use App\Version;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    /**
     * Gebe alle Versionen des Dokuments in einer Liste aus.
     *
     * @param Project $project
     * @param string $doc_type
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Project $project, string $doc_type) {
        $document = $this->getDocument($project, $doc_type);
        $versions = $document->versions()->with('user')->without('sections')->orderBy('updated_at', 'DESC')->get();

        $this->authorize('vergleich', $versions[0]);

        if (is_null($document)) {
            return redirect(route('abschlussprojekt.index'))->with('danger', 'Es wurde kein gültiges Dokument gewählt.');
        }

        return view('abschlussprojekt.versionen.index', compact('document', 'versions', 'doc_type'));
    }

    /**
     * Vergleiche 2 Versionen eines Dokuments
     *
     * @param Request $request
     * @param Project $project
     * @param string $doc_type
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function vergleich(Request $request, Project $project, string $doc_type) {
        $document = $this->getDocument($project, $doc_type);

        //Es müssen genau zwei Versionen ausgewählt werden
        $request->validate([
            'vergleichen' => 'required|array|size:2',
        ]);

        $versionen = [];
        foreach ($request->vergleichen as $v_id) {
            array_push($versionen, Version::with('user')->find($v_id));
        }
        $this->authorize('vergleich', $versionen[0]);

        //Suche nach Unterschieden: Suche nach Section-Instanzen, die zu einer der beiden Versionen gehören, nicht zu beiden
        //Suche in beiden Richtungen nach Unterschieden, falls zwei Versionen verschiedene Abschnitte haben
        $diff_sect = collect([]);
        foreach ($document->getAllSections($versionen[0])->diff($document->getAllSections($versionen[1])) as $sect) {
            $diff_sect->push($sect->name);
            while(! is_null($sect->section)) {
                $sect = $sect->section;
                $diff_sect->push($sect->name);
            }
        }
        foreach ($document->getAllSections($versionen[1])->diff($document->getAllSections($versionen[0])) as $sect) {
            $diff_sect->push($sect->name);
            while(! is_null($sect->section)) {
                $sect = $sect->section;
                $diff_sect->push($sect->name);
            }
        }
        $diff_sect = $diff_sect->unique();

        return view('abschlussprojekt.versionen.vergleich', compact('document', 'versionen', 'diff_sect', 'doc_type'));
    }

    /**
     * Definiere eine Version eines Dokuments als die aktuelle Version
     *
     * @param Request $request
     * @param Project $project
     * @param string $doc_type
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function useVersion(Request $request, Project $project, string $doc_type) {
        $document = $this->getDocument($project, $doc_type);

        $request->validate([
            'id' => 'required|int|min:1',
        ]);

        $version = Version::with(['proposal', 'documentation'])->without('sections')->find($request->id);
        $this->authorize('use', $version);

        if (request()->is('admin*')) {
            $route = 'admin.abschlussprojekt.' . $doc_type . '.index';
        }
        else {
            $route = 'abschlussprojekt.' . $doc_type . '.index';
        }

        if ($doc_type == 'antrag') {
            $compare = $version->proposal;
        }
        else {
            $compare = $version->documentation;
        }
        if (! $version || ! $document->is($compare)) {
            return redirect(route($route, $project))
                ->with('danger', 'Die Version konnte nicht übernommen werden.');
        }
        else {
            $version->touch();
            return redirect(route($route, $project))
                ->with('status', 'Die Version wurde erfolgreich übernommen.');
        }
    }

    /**
     * Lösche eine Version eines Dokuments
     *
     * @param Request $request
     * @param Project $project
     * @param string $doc_type
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteVersion(Request $request, Project $project, string $doc_type) {
        $document = $this->getDocument($project, $doc_type);

        $request->validate([
            'id' => 'required|int|min:1',
        ]);

        if (request()->is('admin*')) {
            $route = 'admin.abschlussprojekt.' . $doc_type . '.index';
        }
        else {
            $route = 'abschlussprojekt.' . $doc_type . '.index';
        }
        if ($document->versions()->count() < 2) {
            return redirect(route($route, $project))
                ->with('danger', 'Das Dokument hat nur eine Version. Sie kann nicht gelöscht werden.');
        }

        $version = Version::find($request->id);
        $this->authorize('delete', $version);
        if ($doc_type == 'antrag') {
            $compare = $version->proposal;
        }
        else {
            $compare = $version->documentation;
        }
        if (! $version || ! $document->is($compare)) {
            return redirect(route($route, $project))
                ->with('danger', 'Die Version konnte nicht gelöscht werden.');
        }
        else {
            //In der delete-Methode von Version werden, wenn nötig, Abschnitte gelöscht.
            $version->delete();

            return redirect(route($route, $project))
                ->with('status', 'Die Version wurde erfolgreich gelöscht.');
        }
    }

    /**
     * Lösche den Änderungsverlauf eines Dokuments
     *
     * @param Project $project
     * @param string $doc_type
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function clearHistory(Project $project, string $doc_type) {
        $document = $this->getDocument($project, $doc_type);
        $this->authorize('clearHistory', $document->versions()->without('sections')->first());

        $versions = $document->versions()->without('sections')->orderby('updated_at', 'DESC')->get();
        $versions->shift();
        foreach($versions as $v) {
            $v->delete();
        }

        return redirect()->back()->with('status', 'Der Änderungsverlauf wurde erfolgreich gelöscht.');
    }

    /**
     * Hilfsmethode, die da gewünschte Dokument liefert
     *
     * @param Project $project
     * @param string $doc_type
     * @return mixed|null
     */
    private function getDocument(Project $project, string $doc_type) {
        switch ($doc_type) {
            case 'antrag':
                return $project->proposal;
            case 'dokumentation':
                return $project->documentation;
            default:
                return null;
        }
    }
}
