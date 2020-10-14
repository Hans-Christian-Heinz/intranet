<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApplicationTemplate;
use App\KeywordTemplate;
use Illuminate\Support\Facades\DB;

class AdminTemplateController extends Controller
{
    public function index() {
        $filename = storage_path('app/public/bewerbungen/templates.json');
        $templates = file_get_contents($filename);
        if (!$templates) {
            file_put_contents($filename, ApplicationController::STANDARD_TEMPLATES);
            $templates = ApplicationController::STANDARD_TEMPLATES;
        }

        return view('admin.bewerbungen.templates.index', compact('templates'));
    }

    public function update(Request $request) {
        $request->validate([
            'tpl' => 'required',
        ]);

        $filename = storage_path('app/public/bewerbungen/templates.json');
        try{
            return file_put_contents($filename, json_encode($request->tpl));
        }
        catch(\Exception $e) {
            return false;
        }
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

    public function restoreDefault() {
        $filename = storage_path('app/public/bewerbungen/templates.json');
        try {
            file_put_contents($filename, ApplicationController::STANDARD_TEMPLATES);
            return file_get_contents($filename);

        }
        catch (\Exception $e) {
            return false;
        }
    }
    
    public function restoreDefaultNew() {
        ApplicationTemplate::restoreDefault();
        $ac = new ApplicationController();
        return $ac->templatesNew();
    }
}
