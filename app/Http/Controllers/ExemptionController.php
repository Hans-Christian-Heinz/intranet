<?php

namespace App\Http\Controllers;

use App\Exemption;
use App\Notifications\CustomNotification;
use App\User;
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
        $exemptions = app()->user->exemptions()->orderBy('start', 'DESC')->paginate(10);
        $statuses = ['new' => 'Neu', 'approved' => 'Genehmigt', 'rejected' => 'Abgelehnt'];

        return view('exemptions.index', compact('exemptions', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('exemptions.create');
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
            'start-date' => 'required|date_format:Y-m-d',
            'start-time' => 'nullable|date_format:H:i',
            'end-date' => 'required|date_format:Y-m-d|after_or_equal:start-date',
            'end-time' => 'nullable|date_format:H:i',
            'reason' => 'required',
        ]);

        $attributes['start'] = array_key_exists('start-time', $attributes)
            ? $attributes['start-date'] . ' ' . $attributes['start-time']
            : $attributes['start-date'];

        $attributes['end'] = array_key_exists('end-time', $attributes)
            ? $attributes['end-date'] . ' ' . $attributes['end-time']
            : $attributes['end-date'];

        $attributes['status'] = 'new';

        app()->user->exemptions()->create($attributes);

        //Sende eine Nachricht an alle Ausbilder
        foreach(User::where('fachrichtung', 'Ausbilder')->get() as $admin) {
            $admin->notify(new CustomNotification(app()->user->full_name, 'Freistellungsantrag gestellt',
                'Der Absender hat einen neuen Freistellungsantrag gestellt.'));
        }

        return redirect()->route('exemptions.index')->with('status', 'Der Freistellungsantrag wurde erfolgreich hinzugefügt.');
    }

    /**
     * Drucken; Methode aus AdminExemptionController kopiert; ggf. Trait machen oder sonstige Lösung suchen
     *
     * @param Exemption $exemption
     * @return mixed
     */
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Exemption  $exemption
     * @return \Illuminate\Http\Response
     */
    public function edit(Exemption $exemption)
    {
        $this->authorize('edit', $exemption);

        return view('exemptions.edit', compact('exemption'));
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
        $this->authorize('update', $exemption);

        $attributes = request()->validate([
            'start-date' => 'required|date_format:Y-m-d',
            'start-time' => 'nullable|date_format:H:i',
            'end-date' => 'required|date_format:Y-m-d|after_or_equal:start-date',
            'end-time' => 'nullable|date_format:H:i',
            'reason' => 'required',
        ]);

        $attributes['start'] = array_key_exists('start-time', $attributes)
            ? $attributes['start-date'] . ' ' . $attributes['start-time']
            : $attributes['start-date'];

        $attributes['end'] = array_key_exists('end-time', $attributes)
            ? $attributes['end-date'] . ' ' . $attributes['end-time']
            : $attributes['end-date'];

        $exemption->update($attributes);

        //Sende eine Nachricht an alle Ausbilder
        foreach(User::where('fachrichtung', 'Ausbilder')->get() as $admin) {
            $admin->notify(new CustomNotification(app()->user->full_name, 'Freistellungsantrag geändert',
                'Der Absender hat einen bestehenden Freistellungsantrag geändert.'));
        }

        return redirect()->route('exemptions.index')->with('status', 'Der Freistellungsantrag wurde erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Exemption  $exemption
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exemption $exemption)
    {
        $this->authorize('destroy', $exemption);

        $exemption->delete();

        //Sende eine Nachricht an alle Ausbilder
        foreach(User::where('fachrichtung', 'Ausbilder')->get() as $admin) {
            $admin->notify(new CustomNotification(app()->user->full_name, 'Freistellungsantrag gelöscht',
                'Der Absender hat einen bestehenden Freistellungsantrag gelöscht.'));
        }

        return redirect()->route('exemptions.index')->with('status', 'Der Freistellungsantrag wurde erfolgreich gelöscht.');
    }
}
