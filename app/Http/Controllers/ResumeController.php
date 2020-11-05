<?php

namespace App\Http\Controllers;

use App\Http\Requests\PdfRequest;
use App\Resume;
use App\User;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resume = app()->user->resume;
        //Variable, in der Resume nicht geladen ist, da das Binärfeld passbild Probleme bereitet (toString))
        $user = User::find(app()->user->id);
        if (is_null($resume)) {
            $resume = false;
        }

        return view("bewerbungen.resumes.index", compact("resume", "user"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $data = $request->input("resume");

        $resume = $user->resume;

        if ($resume) {
            $resume->update([
                "data" => json_encode($data)
            ]);
        } else {
            $resume = Resume::create([
                "user_id" => $user->id,
                "data" => json_encode($data)
            ]);
        }

        return $resume;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $resume = $user->resume;

        if ($resume) {
            return $resume->data;
        }

        return null;
    }

    public function print(PdfRequest $request)
    {
        $resume = app()->user->resume;
        $content = json_decode($resume->data);
        $format = $request->all();
        //Signatur kann beim Drucken hochgeladen werden
        if ($request->signature) {
            //$format['signature'] = base64_encode(file_get_contents($request->file('signature')));
            //$format['sig_datatype'] = $request->file('signature')->extension();
            $sigfile = uniqid() . '.' . $request->file('signature')->extension();
            file_put_contents(storage_path('app/temp/' . $sigfile), file_get_contents($request->file('signature')));
        }
        else {
            $sigfile = false;
        }
        if($request->passbild) {
            //$format['passbild'] = base64_encode(file_get_contents($request->file('passbild')));
            //$format['pb_datatype'] = $request->file('passbild')->extension();
            $pbfile = uniqid() . '.' . $request->file('passbild')->extension();
            file_put_contents(storage_path('app/temp/' . $pbfile), file_get_contents($request->file('passbild')));
        }
        else {
            //$format['passbild'] = false;
            //$format['pb_datatype'] = $resume->pb_datatype;
            $pbfile = false;
        }
        $format['passbild'] = $pbfile;
        $format['signature'] = $sigfile;

        $pdf = new \Mpdf\Mpdf([
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

        $pdf->WriteHTML(view("bewerbungen.resumes.print", compact("content", "format"))->render());
        $pdf->Output();
        if ($sigfile) {
            unlink(storage_path('app/temp/' . $sigfile));
        }
        if ($pbfile) {
            unlink(storage_path('app/temp/' . $pbfile));
        }
        return view("bewerbungen.resumes.print");
        //return $pdf->Output('Lebenslauf_' . app()->user->full_name . '.pdf', 'I');
    }
}
