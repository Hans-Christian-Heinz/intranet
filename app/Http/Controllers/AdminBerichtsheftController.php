<?php

namespace App\Http\Controllers;

use App\Berichtsheft;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use App\Helpers\General\CollectionHelper;

class AdminBerichtsheftController extends Controller
{
    public function index() {
        $azubis = User::where('fachrichtung', '<>', 'Ausbilder')->get();

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
        $azubis = $azubis->sortByDesc(function ($azubi) {
            return $azubi->criteria['fehlend'];
        });
        $azubis = CollectionHelper::paginate($azubis, 25);

        return view('admin.berichtshefte.index', compact('azubis'));
    }

    public function liste(User $azubi) {
        return view('admin.berichtshefte.liste', [
            'berichtshefte' => $azubi->berichtshefte()->orderBy('week', 'DESC')->paginate(10),
            'azubi' => $azubi,
        ]);
    }

    public function show(Berichtsheft $berichtsheft) {
        $azubi = $berichtsheft->owner;
        $previousWeek = $azubi->berichtshefte()->where('week', '<', $berichtsheft->week)->orderBy('week', 'DESC')->first();
        $nextWeek = $azubi->berichtshefte()->where('week', '>', $berichtsheft->week)->orderBy('week', 'ASC')->first();

        return view('admin.berichtshefte.show', compact('berichtsheft', 'previousWeek', 'nextWeek'));
    }
}
