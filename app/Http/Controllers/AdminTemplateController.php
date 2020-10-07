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
}
