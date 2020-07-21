<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    const FONTS = [
        'Courier' => 'courier',
        'Helvetica' => 'helvetica',
        'Times New Roman' => 'times',
        'DejaVu Sans' => 'dejavusans',
    ];

    public function getTopicAttribute() {
        if ($this->proposal) {
            return $this->proposal->topic;
        }
        else {
            return "Das Thema wird im Projektantrag ausgewählt.";
        }
    }

    /**
     * Gebe für jede Phase ihre Dauer an (Summe der Dauer aller Unterphasen)
     * Format: [Phasenname => ['heading' => heading, 'duration' => Dauer,], ...]
     *
     * @param Version $version
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
