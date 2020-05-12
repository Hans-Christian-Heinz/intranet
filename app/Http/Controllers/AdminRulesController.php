<?php

namespace App\Http\Controllers;

use App\Option;
use Illuminate\Http\Request;

class AdminRulesController extends Controller
{
    public function edit()
    {
        $rules = Option::find('rules')->value;
        return view('admin.rules.edit', compact('rules'));
    }

    public function update()
    {
        request()->validate([
            'rules' => 'required'
        ]);

        Option::find('rules')->update([
            'value' => request('rules')
        ]);

        return redirect()->route('admin.rules.edit')->with('status', 'Die Änderungen wurden erfolgreich gespeichert.');
    }
}
