<?php

namespace App\Http\Controllers;

use App\Application;
use App\Company;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = app()->user->applications()->paginate(14);

        return view("bewerbungen.applications.index", compact("applications"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Company $company)
    {
        $application = app()->user->applications()->create([
            "company_id" => $company->id,
            "body" => '{
                "greeting": {
                    "body": ""
                },
                "awareofyou": {
                    "body": ""
                },
                "currentactivity": {
                    "body": ""
                },
                "whycontact": {
                    "body": ""
                },
                "wayOfWork": [],
                "skills": [],
                "ending": {
                    "body": ""
                }
            }'
        ]);

        return redirect()->route("bewerbungen.applications.edit", $application)->with("status", "Eine leere Bewerbung für {$company->name} wurde erfolgreich angelegt.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        $content = json_decode($application->body);
        $resume = json_decode(app()->user->resume->data);

        $pdf = new Mpdf([
            'tempDir' => sys_get_temp_dir(),
        ]);
        $pdf->WriteHTML(view("bewerbungen.applications.pdf", compact("application", "content", "resume"))->render());
        $pdf->Output();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        return view("bewerbungen.applications.edit", compact("application"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        $attributes = $request->validate([
            "body" => "required"
        ]);

        $application->update([
            "body" => json_encode($request->input("body"))
        ]);

        return redirect()->route("bewerbungen.applications.edit", $application)->with("status", "Die Änderungen wurden erfolgreich gespeichert");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        if ($application->user->id !== app()->user->id && !app()->user->isAdmin()) {
            return abort(403);
        }

        $application->delete();

        return redirect()->route("bewerbungen.applications.index")->with("status", "Die Bewerbung für {$application->company->name} wurde erfolgreich gelöscht.");
    }
}
