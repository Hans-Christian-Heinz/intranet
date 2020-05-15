<?php

namespace App\Http\Controllers;

use App\User;
use App\Exemption;
use Illuminate\Http\Request;

class AdminExemptionController extends Controller
{
    public function index()
    {
        $newExemptions = Exemption::where('status', 'new')->orderBy('start')->get();
        $pastExemptions = Exemption::where('status', '!=', 'new')->orderBy('start', 'DESC')->get();
        $statuses = ['new' => 'Neu', 'approved' => 'Genehmigt', 'rejected' => 'Abgelehnt'];

        return view('admin.exemptions.index', compact('newExemptions', 'pastExemptions', 'statuses'));
    }

    public function show(Exemption $exemption)
    {
        $title = 'Freistellungsantrag';

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',

            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 20,
            'margin_bottom' => 20,

            'fontDir' => array_merge($fontDirs, [base_path() . '/resources/fonts']),
            'fontdata' => $fontData + [
                'opensans' => [
                    'R' => 'OpenSans-Regular.ttf',
                    'B' => 'OpenSans-Bold.ttf'
                ]
            ],
            'default_font_size' => 12,
            'default_font' => 'opensans',

            'tempDir' => sys_get_temp_dir(),
        ]);

        $mpdf->SetTitle($title);

        $applicant = User::find($exemption->user_id)->full_name;
        $approver = User::find($exemption->admin_id)->full_name;

        $mpdf->WriteHTML(view('pdf.exemption', compact('exemption', 'applicant', 'approver'))->render());

        return $mpdf->Output($title . '.pdf', 'I');
    }

    public function edit(Exemption $exemption)
    {
        return view('admin.exemptions.edit', compact('exemption'));
    }

    public function update(Exemption $exemption)
    {
        $attributes = request()->validate([
            'start-date' => 'required|date_format:Y-m-d|after_or_equal:now',
            'start-time' => 'nullable|date_format:H:i',
            'end-date' => 'required|date_format:Y-m-d|after_or_equal:start-date',
            'end-time' => 'nullable|date_format:H:i',
            'reason' => 'required',
            'status' => 'required|in:new,approved,rejected',
        ]);

        $attributes['start'] = array_key_exists('start-time', $attributes)
            ? $attributes['start-date'] . ' ' . $attributes['start-time']
            : $attributes['start-date'];

        $attributes['end'] = array_key_exists('end-time', $attributes)
            ? $attributes['end-date'] . ' ' . $attributes['end-time']
            : $attributes['end-date'];

        $attributes['admin_id'] = app()->user->id;

        $exemption->update($attributes);

        return redirect()->route('admin.exemptions.edit', $exemption)->with('status', 'Die Freistellung wurde erfolgreich aktualisiert.');
    }

    public function destroy(Exemption $exemption)
    {
        $exemption->delete();

        return redirect()->route('admin.exemptions.index')->with('status', 'Der Freistellungsantrag wurde erfolgreich gelöscht.');
    }
}
