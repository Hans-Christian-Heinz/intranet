<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjektController extends Controller
{
    public function index() {
        return view('abschlussprojekt.index');
    }

    public function create() {

    }
}
