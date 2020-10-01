<?php

namespace App\Http\Controllers;

use App\Berichtsheft;
use App\Http\Requests\BerichtsheftRequest;
use App\Notifications\CustomNotification;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use App\Helpers\General\CollectionHelper;
use Illuminate\Support\Facades\DB;

class AdminBerichtsheftController extends Controller
{
    /**
     * Gebe eine Liste aller Auszubildenden aus, sortiert nach der Anzahl der fehlenden Berichtshefte.
     * Wird momentan verwendet
     *
     * @return mixed
     */
    public function index() {
        $azubis = User::where('fachrichtung', '<>', 'Ausbilder')->orderByDesc('created_at')->get();

        //Bestimme für jeden Benutzer, wie viele Berichtshefte vorliegen und wie viele Wochen seit seinem Ausbildungsbeginn vergangen sind
        $now = Carbon::now()->startOfWeek();
        foreach($azubis as $azubi) {
            $beginn = Carbon::create($azubi->ausbildungsbeginn);
            //Max-Value, damit beim Sortieren diejenigen Auszubildenden zuerst aufgeführt werden, die noch keine Berichtshefte angelegt haben
            is_null($beginn) ? $dauer = PHP_INT_MAX : $dauer = $now->diffInWeeks($beginn);
            $anzahl = $azubi->berichtshefte()->count();
            $fehlend = $dauer - $anzahl;
            $azubi->criteria = compact('beginn', 'dauer', 'anzahl', 'fehlend');
        }
        /*$azubis = $azubis->sortByDesc(function ($azubi) {
            return $azubi->criteria['fehlend'];
        });*/
        $azubis = CollectionHelper::paginate($azubis, 30);

        return view('admin.berichtshefte.index', compact('azubis'));
    }

    /**
     * Gebe eine Liste aller Auszubildenden aus, unterteilt nach Ausbildungsbeginn, sortiert nach der Anzahl der fehlenden Berichtshefte.
     * Wird momentan nicht verwendet.
     *
     * @return mixed
     */
    public function indexNeu() {
        $beginn = DB::table('users')->select('ausbildungsbeginn')->distinct()->where('fachrichtung', '<>', 'Ausbilder')->get();
        $azubis = [];
        $now = Carbon::now()->startOfWeek();

        foreach($beginn as $b) {
            $help = $b->ausbildungsbeginn;
            if (is_null($help)) {
                $azubis['None'] = User::where('fachrichtung', '<>', 'Ausbilder')->where('ausbildungsbeginn', $help)->get();
                $list = $azubis['None'];
            }
            else {
                $azubis[$help] = User::where('fachrichtung', '<>', 'Ausbilder')->where('ausbildungsbeginn', $help)->get();
                $list = $azubis[$help];
            }

            foreach($list as $azubi) {
                $beginn = Carbon::create($azubi->ausbildungsbeginn);
                //Max-Value, damit beim Sortieren diejenigen Auszubildenden zuerst aufgeführt werden, die noch keine Berichtshefte angelegt haben
                is_null($help) ? $dauer = PHP_INT_MAX : $dauer = $now->diffInWeeks($beginn);
                $anzahl = $azubi->berichtshefte()->count();
                $fehlend = $dauer - $anzahl;
                $azubi->criteria = compact('beginn', 'dauer', 'anzahl', 'fehlend');
            }
            $list = $list->sortByDesc(function ($azubi) {
                return $azubi->criteria['fehlend'];
            });
            //$list = CollectionHelper::paginate($list, 25);
            if (is_null($help)) {
                $azubis['None'] = $list;
            }
            else {
                $azubis[$help] = $list;
            }
        }

        return view('admin.berichtshefte.index_neu', compact('azubis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param User $azubi
     * @return \Illuminate\Http\Response
     */
    public function create(User $azubi)
    {
        $latestBerichtsheft = $azubi->berichtshefte()->orderBy('week', 'DESC')->first();

        return view('berichtshefte.create', [
            'nextBerichtsheftDate' => ($latestBerichtsheft) ? $latestBerichtsheft->week->addWeek() : Carbon::now(),
            'nextBerichtsheftGrade' => ($latestBerichtsheft) ? $latestBerichtsheft->grade : 1,
            'azubi' => $azubi,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BerichtsheftRequest $request
     * @param User $azubi
     * @return \Illuminate\Http\Response
     */
    public function store(BerichtsheftRequest $request, User $azubi)
    {
        $attributes = request()->all();
        $week = Carbon::create($attributes['week']);

        $attributes['week'] = $week->timestamp;

        $berichtsheft = $azubi->berichtshefte()->create($attributes);
        //Aktualisiere ggf. den Ausbildungsbeginn des Benutzers
        if (is_null($azubi->ausbildungsbeginn) || $week < $azubi->ausbildungsbeginn) {
            $azubi->ausbildungsbeginn = $week;
            $azubi->save();
        }

        $azubi->notify(new CustomNotification(app()->user->full_name, 'Neuer Wochenbericht',
            'Für Sie wurde vom Absender ein Wochenbericht für die Woche ' . $week->format("Y-W") . ' angelegt'));

        return redirect()->route('admin.berichtshefte.edit', $berichtsheft)->with('status', 'Das Berichtsheft wurde erfolgreich hinzugefügt.');
    }

    /**
     * Gebe eine Liste aller Wochenberichte für den ausgewählten Auszubildenden aus
     *
     * @param User $azubi
     * @return mixed
     */
    public function liste(User $azubi) {
        return view('admin.berichtshefte.liste', [
            'berichtshefte' => $azubi->berichtshefte()->orderBy('week', 'DESC')->paginate(10),
            'azubi' => $azubi,
        ]);
    }
}
