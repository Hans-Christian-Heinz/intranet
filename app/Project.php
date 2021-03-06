<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * Textarten, die für generierte PDF-Dokumente zur Verfügung stehen.
     */
    const FONTS = [
        'Courier' => 'courier',
        'Helvetica' => 'helvetica',
        'Times New Roman' => 'times',
        'DejaVu Sans' => 'dejavusans',
    ];

    protected $with = [
        'documentation',
        'proposal',
        'user',
    ];

    public function getTopicAttribute() {
        if ($this->proposal) {
            return $this->proposal->topic;
        }
        else {
            return "Projektthema wurde im Projektantrag noch nicht erfasst.";
        }
    }

    /**
     * Gebe für jede Phase ihre Dauer an (Summe der Dauer aller Unterphasen)
     * Format: [Phasenname => ['heading' => heading, 'duration' => Dauer,], ...]
     *
     * @param bool $withSum Soll die Dauer aller Phasen ebenfalls angegeben werden?
     * @return array
     */
    public function getPhasesDuration($withSum = true) {
        if ($this->proposal) {
            $version = $this->proposal->latestVersion();
            return $this->proposal->getPhasesDuration($version, $withSum);
        }
        else {
            return [];
        }
    }

    /**
     * Gebe die Phasen des Projekts (Zeitplanung in der Planungsphase) als Array aus.
     *
     * @return array
     */
    public function getPhases() {
        if ($this->proposal) {
            $version = $this->proposal->latestVersion();
            return $this->proposal->getPhases($version);
        }
        else {
            return [];
        }
    }

    public function getStartAttribute() {
        if ($this->proposal) {
            return $this->proposal->start;
        }
        else {
            return '';
        }
    }

    public function getEndAttribute() {
        if ($this->proposal) {
            return $this->proposal->end;
        }
        else {
            return '';
        }
    }

    public function getJahreszeitAttribute() {
        $end = $this->end;
        if ($end) {
            $help = Carbon::create($end);
            $m = (int) $help->format('m');
            if (4 <= $m && $m <= 9) {
                return 'Sommer ' . $help->format('Y');
            }
            else {
                return 'Winter ' . $help->format('Y');
            }
        }
        else {
            return 'ACHTUNG: Im Projektantrag liegt kein Datum vor.';
        }
    }

    public function getPruefungsJahrAttribute() {
        $end = $this->end;
        if ($end) {
            return Carbon::create($end)->format('Y');
        }
        else {
            return 0;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id', 'id');
    }

    public function proposal() {
        return $this->belongsTo(Proposal::class);
    }

    public function documentation() {
        return $this->belongsTo(Documentation::class);
    }
}
