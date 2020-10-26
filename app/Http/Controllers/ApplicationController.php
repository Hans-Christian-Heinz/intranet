<?php

namespace App\Http\Controllers;

use App\Application;
use App\ApplicationTemplate;
use App\Company;
use App\Http\Requests\PdfRequest;
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
        //Überprüfe, ob die Vorlage existiert; wenn nein, kan das Dokument nicht mehr bearbeitet werden.
        foreach ($applications as $a) {
            if (ApplicationTemplate::where('version', $a->tpl_version)->count() > 0) {
                $a->editable = true;
            }
            else {
                $a->editable = false;
                //Signatur wird beim Drucken verwendet
                $resume = $a->user->resume;
                if ($resume && $resume->signature) {
                    $a->signature = $resume->signatute;
                }
                else {
                    $a->signature = false;
                }
            }
        }

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
        if (is_null($help->signature)) {
            $format['signature'] = base64_encode(file_get_contents($request->file('signature')));
            $format['sig_datatype'] = $request->file('signature')->extension();
        }
        else {
            $format['signature'] = base64_encode($help->signature);
            $format['sig_datatype'] = $help->sig_datatype;
        }

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
        $resume = $user->resume;
        if (!(is_null($resume) || is_null($resume->signature))) {
            $signature = base64_encode($resume->signature);
        }
        else{
            $signature = false;
        }

        return view("bewerbungen.applications.edit", compact("application", "signature"));
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
     * @return false|string
     */
    public function templatesNew(int $version = null) {
        if (is_null($version)) {
            $version = DB::table('application_tpls')->max('version');
        }
        return ApplicationTemplate::where('version', $version)->orderBy('number')->get()->toArray();
    }
}
