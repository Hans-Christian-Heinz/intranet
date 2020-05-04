<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminExemptionController extends Controller
{
    public function index()
    {
        return view("admin.exemptions.index");
    }
}
