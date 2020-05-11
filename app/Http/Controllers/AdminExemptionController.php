<?php

namespace App\Http\Controllers;

use App\Exemption;
use Illuminate\Http\Request;

class AdminExemptionController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exemptions = Exemption::where('status', 'new')->get();

        return view("admin.exemptions.index", compact("exemptions"));
    }

    public function edit(Exemption $exemption)
    {
        return view('admin.exemptions.edit', compact("exemption"));
    }

    public function update(Exemption $exemption)
    {
        $attributes = request()->validate([
            "start" => "required",
            "end" => "required",
            "reason" => "required",
            "status" => "required|in:new,approved,rejected"
        ]);

        # $attributes["start"] = Carbon::create($attributes["start"])->timestamp;
        # $attributes["end"] = Carbon::create($attributes["end"])->timestamp;
        $attributes['admin_id'] = app()->user->id;

        $exemption->update($attributes);

        return redirect(route("admin.exemptions.index"))->with('status', 'Die Freistellung wurde erfolgreich aktualisiert.');
    }
}
