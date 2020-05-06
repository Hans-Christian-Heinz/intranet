<?php

namespace App\Http\Controllers;

use App\Exemption;
use Illuminate\Http\Request;

class AdminExemptionController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.exemptions.index");
    }

    public function show(Exemption $exemption)
    {

    }
}
