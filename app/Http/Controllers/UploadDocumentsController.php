<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller wird im Moment nicht verwendet, da die Funktionalität des Einbindens anderer PDF-Dokumente in eine
 * Abschlussdokumentation im Moment nicht implementiert ist und somit keine Dokumente hochgeladen und verwendet werden.
 *
 * Class UploadDocumentsController
 * @package App\Http\Controllers
 */
class UploadDocumentsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request) {
        //TODO Validate Filesize (Regel; max:x, wobei x die Dateigröße in kilo-Bytes ist)
        $request->validate([
            'document' => 'required|file|mimetypes:application/pdf',
        ]);

        $user = app()->user;
        $dir_path = 'documents/' . $user->ldap_username;
        $doc = $request->file('document');
        Storage::disk('public')->putFileAs($dir_path, $doc, $doc->getClientOriginalName());

        return redirect()->back()->with('status', 'Das Dokument wurde erfolgreich hochgeladen.');
    }
}
