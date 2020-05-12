<?php

namespace App\Http\Controllers;

use App\Option;
use Illuminate\Http\Request;

class RulesController extends Controller
{
    public function index()
    {
        return view('rules', [
            'rules' => Option::where('key', 'rules')->first()
        ]);
    }
}
