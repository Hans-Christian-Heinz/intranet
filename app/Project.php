<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'topic',
        'start',
        'end',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start',
        'end',
    ];

    /**
     * Gebe für jede Phase ihre Dauer an (Summe der Dauer aller Unterphasen)
     * Format: [Phasenname => Dauer, ...]
     *
     * @param bool $withSum Soll die Dauer aller Phasen ebenfalls angegeben werden?
     * @return array
     */
    public function getPhasesDuration($withSum = true) {
        return $this->proposal->getPhasesDuration($withSum);
    }

    /**
     * Gebe die Phasen des Projekts (Zeitplanung in der Planungsphase) als Array aus.
     *
     * @return array
     */
    public function getPhases() {
        if ($this->proposal) {
            return $this->proposal->getPhases();
        }
        else {
            return [];
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

    public function proposalHistory() {
        return $this->hasMany(Proposal::class);
    }

    public function documentation() {
        return $this->belongsTo(Documentation::class);
    }

    public function documentationHistory() {
        return $this->hasMany(Documentation::class);
    }
}
