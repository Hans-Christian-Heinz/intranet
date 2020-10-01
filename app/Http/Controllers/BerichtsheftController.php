<?php

namespace App\Http\Controllers;

use App\Berichtsheft;
use App\Http\Requests\BerichtsheftRequest;
use App\Http\Requests\BerichtsheftUpdateRequest;
use App\Notifications\CustomNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BerichtsheftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = app()->user;
        $now = Carbon::now()->startOfWeek();
        $beginn = $user->ausbildungsbeginn;
        if (! is_null($beginn)) {
            $ausbildungsende = $user->ausbildungsende;
            if ($now > $ausbildungsende) {
                $helpDate = Carbon::create($ausbildungsende);
            }
            else {
                $helpDate = $now;
            }

            $dauer = $helpDate->diffInWeeks($beginn);
            $anzahl = $user->berichtshefte()->count();
            $fehlend = $dauer - $anzahl;
            $criteria = compact('beginn', 'dauer', 'anzahl', 'fehlend');
        }
        else {
            $criteria = [];
        }


        return view('berichtshefte.index', [
            'berichtshefte' => $user->berichtshefte()->orderBy('week', 'DESC')->paginate(10),
            'criteria' => $criteria,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $latestBerichtsheft = app()->user->berichtshefte()->orderBy('week', 'DESC')->first();

        return view('berichtshefte.create', [
            'nextBerichtsheftDate' => ($latestBerichtsheft) ? $latestBerichtsheft->week->addWeek() : Carbon::now(),
            'nextBerichtsheftGrade' => ($latestBerichtsheft) ? $latestBerichtsheft->grade : 1
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BerichtsheftRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BerichtsheftRequest $request)
    {
        $attributes = request()->all();
        $week = Carbon::create($attributes['week']);
        $user = app()->user;

        $attributes['week'] = $week->timestamp;

        $berichtsheft = $user->berichtshefte()->create($attributes);
        //Aktualisiere ggf. den Ausbildungsbeginn des Benutzers
        if (is_null($user->ausbildungsbeginn) || $week < $user->ausbildungsbeginn) {
            $user->ausbildungsbeginn = $week;
            $user->save();
        }

        return redirect()->route('berichtshefte.edit', $berichtsheft)->with('status', 'Das Berichtsheft wurde erfolgreich hinzugefügt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Berichtsheft  $berichtsheft
     * @return \Illuminate\Http\Response
     */
    public function show(Berichtsheft $berichtsheft)
    {
        $this->authorize('show', $berichtsheft);

        $title = 'Berichtsheft ' . $berichtsheft->week->format('Y-\WW');

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

        $mpdf->WriteHTML(view('pdf.berichtsheft', compact('berichtsheft'))->render());

        return $mpdf->Output($title . '.pdf', 'I');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Berichtsheft  $berichtsheft
     * @return \Illuminate\Http\Response
     */
    public function edit(Berichtsheft $berichtsheft)
    {
        $this->authorize('edit', $berichtsheft);

        $user = $berichtsheft->owner;
        $previousWeek = $user->berichtshefte()->where('week', '<', $berichtsheft->week)->orderBy('week', 'DESC')->first();
        $nextWeek = $user->berichtshefte()->where('week', '>', $berichtsheft->week)->orderBy('week', 'ASC')->first();

        return view('berichtshefte.edit', compact('berichtsheft', 'previousWeek', 'nextWeek'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Berichtsheft  $berichtsheft
     * @return \Illuminate\Http\Response
     */
    public function update(BerichtsheftUpdateRequest $request, Berichtsheft $berichtsheft)
    {
        $this->authorize('update', $berichtsheft);
        $attributes = $request->all();

        $user = $berichtsheft->owner;
        $week = Carbon::create($attributes['week']);
        $attributes['week'] = $week->timestamp;

        $berichtsheft->update($attributes);
        //Aktualisiere ggf. den Ausbildungsbeginn des Benutzers
        if (is_null($user->ausbildungsbeginn) || $week < $user->ausbildungsbeginn) {
            $user->ausbildungsbeginn = $week;
            $user->save();
        }

        if (! $user->is(app()->user)) {
            $user->notify(new CustomNotification(app()->user->full_name, 'Wochenbericht geändert',
                'Ihr Wochenbericht für die Woche ' . $berichtsheft->week->format("Y-W") . ' wurde vom Absender geändert.'));
        }

        return back()->with('status', 'Das Berichtsheft wurde erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Berichtsheft  $berichtsheft
     * @return \Illuminate\Http\Response
     */
    public function destroy(Berichtsheft $berichtsheft)
    {
        $this->authorize('destroy', $berichtsheft);

        $berichtsheft->delete();

        if (! $berichtsheft->owner->is(app()->user)) {
            $berichtsheft->owner->notify(new CustomNotification(app()->user->full_name, 'Wochenbericht gelöscht',
                'Ihr Wochenbericht für die Woche ' . $berichtsheft->week->format("Y-W") . ' wurde vom Absender gelöscht.'));
        }

        if (request()->is('admin*')) {
            return redirect()->route('admin.berichtshefte.liste', $berichtsheft->owner)->with('status', 'Das Berichtsheft wurde erfolgreich gelöscht.');
        }
        else {
            return redirect()->route('berichtshefte.index')->with('status', 'Das Berichtsheft wurde erfolgreich gelöscht.');
        }
    }
}
