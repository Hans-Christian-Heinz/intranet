<?php

namespace App\Http\Controllers;

use App\Option;
use Illuminate\Http\Request;

class AdminRulesController extends Controller
{
    public function edit()
    {
        return view('admin.rules.edit', [
            'rules' => Option::where('key', 'rules')->first()->value
        ]);
    }

    public function update()
    {
        request()->validate([
            'rules' => 'required'
        ]);

        Option::where('key', 'rules')->update([
            'value' => request('rules')
        ]);

        return redirect()->route('admin.rules.edit')->with('status', 'Die Änderungen wurden erfolgreich gespeichert.');
    }
}
