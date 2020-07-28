<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Project;
use App\Proposal;
use App\Version;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function index(Project $project, string $doc_type) {
        $document = $this->getDocument($project, $doc_type);
        //TODO authorize
        //$this->authorize('history', $proposal);

        if (is_null($document)) {
            return redirect(route('abschlussprojekt.index'))->with('danger', 'Es wurde kein gültiges Dokument gewählt.');
        }

        $versions = $document->versions()->with('user')->without('sections')->orderBy('updated_at', 'DESC')->get();
        return view('abschlussprojekt.versionen.index', compact('document', 'versions', 'doc_type'));
    }

    public function vergleich(Request $request, Project $project, string $doc_type) {
        $document = $this->getDocument($project, $doc_type);
        //todo authorize
        //$this->authorize('history', $proposal);

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

    public function useVersion(Request $request, Project $project, string $doc_type) {
        $document = $this->getDocument($project, $doc_type);
        //todo authorize
        //$this->authorize('store', $proposal);

        $request->validate([
            'id' => 'required|int|min:1',
        ]);

        $version = Version::with(['proposal', 'documentation'])->without('sections')->find($request->id);

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

    public function deleteVersion(Request $request, Project $project, string $doc_type) {
        $document = $this->getDocument($project, $doc_type);
        //todo authorize
        //$this->authorize('store', $proposal);

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

    public function clearHistory(Project $project, string $doc_type) {
        $document = $this->getDocument($project, $doc_type);
        //todo authorize
        //$this->authorize('store', $proposal);

        $versions = $document->versions()->without('sections')->orderby('updated_at', 'DESC')->get();
        $versions->shift();
        foreach($versions as $v) {
            $v->delete();
        }

        return redirect()->back()->with('status', 'Der Änderungsverlauf wurde erfolgreich gelöscht.');
    }

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
