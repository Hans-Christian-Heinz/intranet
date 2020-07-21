<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function address(Request $request) {
        $request->validate([
            'strasse' => 'required|string',
            'hausnr' => 'required|string',
            'plz' => 'required|regex:#^[0-9]{5}$#',
            'ort' => 'required|string',
        ]);

        $user = app()->user;

        $user->update([
            'strasse' => $request->strasse,
            'hausnr' => $request->hausnr,
            'plz' => $request->plz,
            'ort' => $request->ort,
        ]);

        return redirect()->back()->with('status', 'Ihre Adresse wurde erfolgreich aktualisiert.');
    }
}
