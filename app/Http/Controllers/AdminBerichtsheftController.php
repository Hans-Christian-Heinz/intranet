<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminBerichtsheftController extends Controller
{
    public function index()
    {
        return view("admin.berichtshefte.index");
    }
}
