<?php

namespace App\Http\Controllers;

use App\Option;
use Illuminate\Http\Request;

class RulesController extends Controller
{
    public function index()
    {
        $rules = Option::find('rules');
        return view('rules', compact('rules'));
    }
}
