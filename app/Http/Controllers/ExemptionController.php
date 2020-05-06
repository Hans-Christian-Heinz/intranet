<?php

namespace App\Http\Controllers;

use App\Exemption;
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
        return view("exemptions.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("exemptions.show");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
