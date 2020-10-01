<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class UserController extends Controller
{
    public function profile(Request $request) {
        $request->validate([
            'strasse' => 'required|string',
            'hausnr' => 'required|string',
            'plz' => 'required|regex:#^[0-9]{5}$#',
            'ort' => 'required|string',
            'ende' => 'nullable|date'
        ]);

        $user = app()->user;

        $user->update([
            'strasse' => $request->strasse,
            'hausnr' => $request->hausnr,
            'plz' => $request->plz,
            'ort' => $request->ort,
            'ausbildungsende' => $request->ende,
        ]);

        return redirect()->back()->with('status', 'Ihre Benutzerprofil wurde erfolgreich aktualisiert.');
    }

    public function nachrichten() {
        $user = app()->user;
        $nachrichten = $user->notifications;

        return view('nachrichten.index', compact('nachrichten'));
    }

    public function showMessage(DatabaseNotification $message) {
        $message->markAsRead();
        return view('nachrichten.detail', compact('message'));
    }

    public function deleteMessage(DatabaseNotification $message) {
        try {
            $message->delete();
            return redirect(route('user.nachrichten'))->with('status', 'Die Nachricht wurde erfolgreich gelöscht.');
        } catch (\Exception $e) {
            return redirect(route('user.nachrichten'))->with('status', 'Beim Löschen der Nachricht ist ein Fehler aufgetreten.');
        }
    }

    public function deleteMany(Request $request) {
        $request->validate([
            'delete' => 'required|array',
        ]);

        $res = true;
        foreach ($request->delete as $id) {
            $res = $res && app()->user->notifications()->where('id', $id)->delete();
        }

        if ($res) {
            return redirect()->back()->with('status', 'Die Nachrichten wurden erfolgreich gelöscht.');
        }
        else {
            return redirect()->back()->with('danger', 'Beim Löschen der Nachrichten ist ein Problem aufgetreten.');
        }
    }
}
