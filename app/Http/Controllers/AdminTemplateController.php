<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApplicationTemplate;
use App\KeywordTemplate;
use Illuminate\Support\Facades\DB;

class AdminTemplateController extends Controller
{
    public function index() {
        if (ApplicationTemplate::count() === 0) {
            $this->restoreDefaultNew();
        }
        return view('admin.bewerbungen.templates.index');
    }

    public function updateNew(Request $request) {
        $request->validate([
            'templates' => 'required',
        ]);

        $version = DB::table('application_tpls')->max('version') + 1;
        $success = true;

        foreach($request->templates as $temp) {
            //Damit das Format passt
            $tpl = json_decode(json_encode($temp), true);
            $at = new ApplicationTemplate([
                'version' => $version,
                'fix' => $tpl['fix'],
                'is_heading' => $tpl['is_heading'],
                'choose_keywords' => $tpl['choose_keywords'],
                'number' => $tpl['number'],
                'heading' => $tpl['heading'],
                'tpls' => $tpl['tpls'],
                'name' => $tpl['name'],
            ]);
            $success = $success && $at->save();

            //Schlüsselworte falls vorhanden
            foreach($tpl['keywords'] as $keyword) {
                //TEST1234
                $kw = json_decode(json_encode($keyword), true);

                $kt = new KeywordTemplate([
                    'number' => $kw['number'],
                    'heading' => $kw['heading'],
                    'conjunction' => $kw['conjunction'],
                    'tpls' => $kw['tpls'],
                ]);
                $success = $success && $at->keywords()->save($kt);
            }
        }

        return $success;
    }

    public function restoreDefaultNew() {
        ApplicationTemplate::restoreDefault();
        $ac = new ApplicationController();
        return $ac->templatesNew();
    }

    /**
     * Zeige für jede Version der Vorlage an, wie viele Anschreiben darauf basieren
     *
     * @return mixed
     */
    public function versionen() {
        //SELECT a.version, COUNT(DISTINCT b.id) FROM application_tpls AS a
        //LEFT JOIN applications AS b ON a.version=b.tpl_version GROUP BY a.version ORDER BY a.version;
        $versionen =  DB::table('application_tpls')
            ->leftJoin('applications', 'application_tpls.version', '=', 'applications.tpl_version')
            ->select('application_tpls.version')
            ->selectRaw('count(distinct applications.id) as anzahl')
            ->groupBy('application_tpls.version')
            ->orderBy('application_tpls.version')
            ->get();

        return view('admin.bewerbungen.templates.versionen', compact('versionen'));
    }

    /**
     * Lösche eine Vorlage. (Alle Datensätze, die zur ausgewählten Version gehören)
     *
     * @param $tpl
     * @return unknown
     */
    public function delete($tpl) {
        ApplicationTemplate::where('version', $tpl)->delete();
        //Falls später eine andere Vorlage mit der gleichen Versionsnummer erstellt wird.
        DB::table('applications')->where('tpl_version', $tpl)->update(['tpl_version' => null]);
        return redirect()->back()->with('status', 'Die Version wurden erfolgreich gelöscht.');
    }

    /**
     * Lösche alle Vorlagen, auf denen keine tatsächlichen Bewerbungsanschreiben basieren
     *
     * @return redirect
     */
    public function deleteUnused() {
        //DELETE FROM application_tpls WHERE NOT version IN (SELECT DISTINCT tpl_version FROM applications);
        ApplicationTemplate::whereNotIn('version', function ($query) {
            $query->selectRaw('distinct tpl_version')
                ->from('applications');
        })->delete();
        return redirect()->back()->with('status', 'Die Versionen wurden erfolgreich gelöscht.');
    }

    /**
     * Lösche alle Vorlagen außer der neusten
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll() {
        $max = DB::table('application_tpls')->max('version');
        ApplicationTemplate::where('version', '<>', $max)->delete();
        return redirect()->back()->with('status', 'Die Versionen wurden erfolgreich gelöscht.');
    }
}
