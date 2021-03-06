<?php

namespace App\Http\Controllers;

use App\Application;
use App\ApplicationTemplate;
use App\Company;
use App\Http\Requests\PdfRequest;
use App\TplVariable;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\DB;

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
        $tpl_version = DB::table('application_tpls')->max('version');
        $application = app()->user->applications()->create([
            "company_id" => $company->id,
            'tpl_version' => $tpl_version,
            "body" => '{}',
        ]);

        return redirect()->route("bewerbungen.applications.edit", $application)->with("status", "Eine leere Bewerbung für {$company->name} wurde erfolgreich angelegt.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function showNew(PdfRequest $request, Application $application)
    {
        $content = json_decode($application->body, true);
        $format = $request->all();
        $help = app()->user->resume;
        $resume = json_decode($help->data);
        //Falls keine Signatur hinterlegt ist, wurde sie direkt beim Drucken hochgeladen
        //Erstelle ine temporäre Datei für die Signatur; sie wird gelöscht, sobald das PDF-Dokument vorliegt
        //nötig, das Mpdf sehr lange html-strings (vgl. blob) nicht verträt
        if($request->signature) {
            //$format['signature'] = base64_encode($help->signature);
            //$format['sig_datatype'] = $help->sig_datatype;
            $filename = uniqid() . '.' . $request->file('signature')->extension();
            file_put_contents(storage_path('app/temp/' . $filename), file_get_contents($request->file('signature')));
        }
        else {
            $filename = false;
        }
        $format['signature'] = $filename;

        $pdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',

            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 20,
            'margin_bottom' => 20,

            'tempDir' => sys_get_temp_dir(),
            'default_font_size' => $request->textgroesse,
            'default_font' => 'opensans',
        ]);
        $pdf->DefHTMLFooterByName('footer',
            '<table style="width: 100%; border: none; border-top: 1px solid black;">
    <tr style="border: none;">
        <td style="border:none; text-align: right;">{PAGENO}/{nbpg}</td>
    </tr>
</table>');

        $pdf->WriteHTML(view("bewerbungen.applications.pdfNew", compact("application", "content", "resume", "format"))->render());
        if ($filename) {
            unlink(storage_path('app/temp/' . $filename));
        }
        return $pdf->Output("Bewerbungsanschreiben.pdf", 'I');
        //return view("bewerbungen.applications.pdfNew");
        //return view("bewerbungen.applications.pdfNew", compact("application", "content", "resume", "format"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        $user = $application->user;

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

        return $application->update([
            "body" => json_encode($request->input("body"))
        ]);

        //return redirect()->route("bewerbungen.applications.edit", $application)->with("status", "Die Änderungen wurden erfolgreich gespeichert");
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

    /**
     * Gibt die Templates bzw. Textbausteine für Bewerbungsanschreiben als Array aus.
     *
     * @param int|null $version
     * @return false|string
     */
    public function templatesNew(int $version = null) {
        if (is_null($version)) {
            $version = DB::table('application_tpls')->max('version');
        }
        return ApplicationTemplate::where('version', $version)->orderBy('number')->get()->toArray();
    }

    public function variables(int $version = null) {
        if (is_null($version)) {
            $version = DB::table('application_tpls')->max('version');
        }
        return TplVariable::where('version', $version)->get()->toArray();
    }
}
