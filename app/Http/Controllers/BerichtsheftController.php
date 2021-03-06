<?php

namespace App\Http\Controllers;

use App\Berichtsheft;
use App\Http\Requests\BerichtsheftRequest;
use App\Http\Requests\BerichtsheftUpdateRequest;
use App\Notifications\CustomNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            //Die Dauer der Ausbildung wird als Differenz in Wochen zwischen Ausbildungsbeginn und Minimum von jetzt und Ausbildungsende berechnet
            $ausbildungsende = $user->ausbildungsende;
            if ($ausbildungsende && $now > $ausbildungsende) {
                $helpDate = Carbon::create($ausbildungsende);
            }
            else {
                $helpDate = $now;
            }

            //+1 wegen angefangener Wochen.
            $dauer = $helpDate->diffInWeeks($beginn) + 1;
            $anzahl = $user->berichtshefte()->count();
            $fehlend = $dauer - $anzahl;

            //Erstelle eine Liste der fehlenden Wochen:
            if ($fehlend > 0) {
                $dates = DB::table('berichtshefte')->select('week')->where('user_id', $user->id)->orderBy('week')->get()->all();
                $missing = [];

                for($i = 1; $i < count($dates); $i++) {
                    $w1 = Carbon::create($dates[$i - 1]->week);
                    $w2 = Carbon::create($dates[$i]->week);
                    //Falls mehr als eine Woche Unterschied vorliegt, fehlt die nächste Woche (von $w1)
                    if ($w1->diffInWeeks($w2) > 1) {
                        array_push($missing, $w1->addWeek());
                        $dates[$i-1]->week = $w1->toString();
                        $i--;
                    }
                }
                //Vom größten vorhandenen Datum bis zu min(ausbildungsende, now)
                //helpDate: min(ausbildungsende, now)
                $w = Carbon::create($dates[count($dates) - 1]->week);
                //$w = $wMax;

                $wEnd = $helpDate->startOfWeek();

                //lt lesser than
                while($w->lt($wEnd)) {
                    $w = $w->addWeek();
                    array_push($missing, $w->copy());
                }
                /*$index = count($dates) - 1;
                while($index >= 0 && $wNow->ne($dates[$index])) {
                    array_push($missing, $wNow);
                    $wNow = $wNow->subWeek();
                }*/
                /*if ($wEnd->ne($dates[count($dates) - 1])) {
                    array_push($missing, $wEnd);
                }*/
            }
            else {
                $missing = [];
            }

            $criteria = compact('beginn', 'dauer', 'anzahl', 'fehlend', 'missing');
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
        /*if (is_null($user->ausbildungsbeginn) || $week < $user->ausbildungsbeginn) {
            $user->ausbildungsbeginn = $week;
            $user->save();
        }*/

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
        /*if (is_null($user->ausbildungsbeginn) || $week < $user->ausbildungsbeginn) {
            $user->ausbildungsbeginn = $week;
            $user->save();
        }*/

        if (! $user->is(app()->user)) {
            $user->notify(new CustomNotification(app()->user->full_name, 'Wochenbericht geändert',
                'Ihr Wochenbericht für die Woche ' . $berichtsheft->week->format("Y-W") . ' wurde vom Absender geändert.'));
        }

        return back()->with('status', 'Das Berichtsheft wurde erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Berichtsheft $berichtsheft
     * @return \Illuminate\Http\Response
     */
    public function destroy(Berichtsheft $berichtsheft)
    {
        $this->authorize('destroy', $berichtsheft);

        $user = $berichtsheft->owner;
        //$beginn_anpassen = $berichtsheft->week == DB::table('berichtshefte')->where('user_id', $user->id)->min('week');

        $berichtsheft->delete();
        //beim Löschen des ersten Berichtshefts wird der hinterlegte Ausbildungsbeginn angepasst.
        /*if ($beginn_anpassen) {
            $help = DB::table('berichtshefte')->where('user_id', $user->id)->min('week');
            is_null($help)
                ? $user->ausbildungsbeginn = null
                : $user->ausbildungsbeginn = Carbon::create(DB::table('berichtshefte')->where('user_id', $user->id)->min('week'));
            $user->save();
        }*/

        if (! $user->is(app()->user)) {
            $user->notify(new CustomNotification(app()->user->full_name, 'Wochenbericht gelöscht',
                'Ihr Wochenbericht für die Woche ' . $berichtsheft->week->format("Y-W") . ' wurde vom Absender gelöscht.'));
        }

        if (request()->is('admin*')) {
            return redirect()->route('admin.berichtshefte.liste', $user)->with('status', 'Das Berichtsheft wurde erfolgreich gelöscht.');
        }
        else {
            return redirect()->route('berichtshefte.index')->with('status', 'Das Berichtsheft wurde erfolgreich gelöscht.');
        }
    }
}
