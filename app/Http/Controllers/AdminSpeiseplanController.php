<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSpeiseplanController extends Controller
{
    public function index() {
        return view('admin.speiseplan');
    }

    public function save(Request $request) {
        $request->validate([
            'speiseplan' => 'required|file|mimetypes:application/pdf',
        ]);
        Storage::disk('public')->putFileAs('', $request->file('speiseplan'), 'speiseplan.pdf');

        return redirect()->back()->with('status', 'Der Speiseplan wurde erfolgreich hochgeladen');
    }
}
