<?php

namespace App\Http\Controllers;

use App\Helpers\General\CollectionHelper;
use App\Http\Requests\DeleteImageRequest;
use App\Image;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Project $project) {
        $user = app()->user;
        $image_files = $user->getImageFiles();

        $image_files = CollectionHelper::paginate(collect($image_files), 5);

        return view('abschlussprojekt.bilder.index', [
            'image_files' => $image_files,
            'project' => $project,
        ]);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request, Project $project) {
        //Validate Filesize (Regel; max:x, wobei x die Dateigröße in kilo-Bytes ist)
        $request->validate([
            'bilddatei' => 'required|image|max:1000',
        ]);

        $user = app()->user;
        $dir_path = 'images/' . $user->ldap_username;

        Storage::disk('public')->put($dir_path, $request->file('bilddatei'));

        return redirect()->back()->with('status', 'Die Bilddatei wurde erfolgreich hochgeladen');
    }

    /**
     * @param DeleteImageRequest $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(DeleteImageRequest $request, Project $project) {
        Storage::disk('public')->delete($request->datei);

        return redirect()->back()->with('status', 'Die Bilddatei wurde erfolgreich gelöscht.');
    }
}
