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

        return view("bewerbungen.resumes.index", compact("resume"));
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

        $pdf->WriteHTML(view("bewerbungen.resumes.print", compact("resume", "content", "format"))->render());
        $pdf->Output();
        return view("bewerbungen.resumes.print");
        //return $pdf->Output('Lebenslauf_' . app()->user->full_name . '.pdf', 'I');
    }
}
