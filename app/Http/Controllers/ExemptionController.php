<?php

namespace App\Http\Controllers;

use App\Exemption;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExemptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("exemptions.index", [
            "exemptions" => app()->user->exemptions()->orderBy("start", "DESC")->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("exemptions.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = request()->validate([
            "start" => "required",
            "end" => "required",
            "reason" => "required",
        ]);

        # $attributes["start"] = Carbon::create($attributes["start"])->timestamp;
        # $attributes["end"] = Carbon::create($attributes["end"])->timestamp;

        $attributes['status'] = 'new';

        $berichtsheft = app()->user->exemptions()->create($attributes);

        return redirect()->route("exemptions.index")->with("status", "Der Freistellungsantrag wurde erfolgreich hinzugefügt.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Exemption  $exemption
     * @return \Illuminate\Http\Response
     */
    public function show(Exemption $exemption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Exemption  $exemption
     * @return \Illuminate\Http\Response
     */
    public function edit(Exemption $exemption)
    {
        return view("exemptions.edit", compact("exemption"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Exemption  $exemption
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exemption $exemption)
    {
        $attributes = request()->validate([
            "start" => "required",
            "end" => "required",
            "reason" => "required",
        ]);

        # $attributes["start"] = Carbon::create($attributes["start"])->timestamp;
        # $attributes["end"] = Carbon::create($attributes["end"])->timestamp;

        $exemption->update($attributes);

        return redirect()->route("exemptions.index")->with("status", "Der Freistellungsantrag wurde erfolgreich aktualisiert.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Exemption  $exemption
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exemption $exemption)
    {
        //
    }
}
