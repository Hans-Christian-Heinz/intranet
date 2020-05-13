<?php

namespace App\Http\Controllers;

use App\Option;
use Illuminate\Http\Request;

class RulesController extends Controller
{
    public function index()
    {
        $rules = Option::find('rules');
        $hasAcceptedRules = app()->user->hasAcceptedRules();
        $acceptedRulesAt = app()->user->accepted_rules_at;

        return view('rules', compact('rules', 'hasAcceptedRules', 'acceptedRulesAt'));
    }

    public function accept()
    {
        request()->validate([
            'accept-rules' => 'required',
        ]);

        app()->user->acceptRules();
        return redirect()->route('rules.index')->with('status', 'Die Werkstattregeln wurden erfolgreich akzeptiert.');
    }
}
