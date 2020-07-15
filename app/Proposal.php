<?php

namespace App;

use App\Traits\HasSections;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    // Die Abschnitte eines Projektantrags
    const SECTIONS = [
        ['name' => 'topic', 'heading' => 'Thema', 'sequence' => 0,],
        ['name' => 'deadline', 'heading' => 'Termin', 'sequence' => 1, 'tpl' => 'antrag.deadline_section',],
        ['name' => 'description', 'heading' => 'Beschreibung', 'sequence' => 2,],
        ['name' => 'environment', 'heading' => 'Umfeld', 'sequence' => 3,],
        ['name' => 'phases', 'heading' => 'Phasen', 'sequence' => 4, 'tpl' => 'antrag.phases_parent_section', 'sections' => self::PHASES,],
        ['name' => 'documentation', 'heading' => 'Dokumentation', 'sequence' => 5,],
        ['name' => 'attachments', 'heading' => 'Anlagen', 'sequence' => 6,],
        ['name' => 'presantation', 'heading' => 'Präsentationsmittel', 'sequence' => 7,],
    ];

    const PHASES = [
        ['name' => 'planung', 'heading' => 'Planung und Analyse', 'sequence' => 0, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'entwurf', 'heading' => 'Entwurf', 'sequence' => 0, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'implementierung', 'heading' => 'Implementierung', 'sequence' => 0, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'test', 'heading' => 'Test', 'sequence' => 0, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'abnahme', 'heading' => 'Abnahme und Dokumentation', 'sequence' => 0, 'tpl' => 'antrag.phases_text_section',],
    ];

    use HasSections;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['sections'];

    /**
     * Gebe für jede Phase ihre Dauer an (Summe der Dauer aller Unterphasen)
     * Format: [Phasenname => Dauer, ...]
     *
     * @param bool $withSum Soll die Dauer aller Phasen ebenfalls angegeben werden?
     * @return array
     */
    public function getPhasesDuration($withSum = true) {
        $phases = $this->getPhases();
        $res = [];
        $fullDuration = 0;
        foreach ($phases as $heading => $phase) {
            $duration = 0;
            foreach ($phase as $data) {
                $duration += intval($data['duration']);
            }
            $res[$heading] = $duration;
            $fullDuration += $duration;
        }
        if ($withSum) {
            $res['Gesamt'] = $fullDuration;
        }

        return $res;
    }

    /**
     * Gebe die Phasen des Projekts (Zeitplanung in der Planungsphase) als Array aus.
     *
     * @return array
     */
    public function getPhases() {
        $res = [];
        $phasesSection = $this->findSection('phases');
        if ($phasesSection) {
            foreach ($phasesSection->sections as $phase) {
                $res[$phase->heading] = $phase->text;
            }
        }

        return $this->formatPhases($res);
    }

    /**
     * Formatiere die Phasen: In der Datenbank sind die Phasen als Text gespeichert (Phase1 : 2; Phase2 : 1; ...)
     * Gewünschtes Format: ['name' => 'Phase1', 'duration' => 2,], [....
     *
     * @param array $phases
     * @return array
     */
    private function formatPhases(array $phases) {
        $res = [];
        foreach ($phases as $header => $phase) {
            $res[$header] = [];
            $temp = explode(';', $phase);
            foreach($temp as $t) {
                if (empty(trim($t))) {
                    continue;
                }
                $temporary = explode(':', $t);
                array_push($res[$header], [
                    'name' => trim($temporary[0]),
                    'duration' => trim($temporary[1]),
                ]);
            }
        }

        return $res;
    }

    public function getStartAttribute() {
        return $this->project->start;
    }

    public function getEndAttribute() {
        return $this->project->end;
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function changedBy() {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }

    public function sections() {
        return $this->hasMany(Section::class, 'proposal_id', 'id');
    }
}
