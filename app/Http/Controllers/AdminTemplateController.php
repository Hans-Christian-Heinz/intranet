<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminTemplateController extends Controller
{
    public function index() {
        $filename = storage_path('app/public/bewerbungen/templates.json');
        $templates = file_get_contents($filename);

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
}
