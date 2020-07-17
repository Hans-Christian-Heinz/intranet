<?php

namespace App;

use App\Structs\Phase;
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
        ['name' => 'entwurf', 'heading' => 'Entwurf', 'sequence' => 1, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'implementierung', 'heading' => 'Implementierung', 'sequence' => 2, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'test', 'heading' => 'Test', 'sequence' => 3, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'abnahme', 'heading' => 'Abnahme und Dokumentation', 'sequence' => 4, 'tpl' => 'antrag.phases_text_section',],
    ];

    use HasSections;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        //'sections',
    ];

    /**
     * Gebe für jede Phase ihre Dauer an (Summe der Dauer aller Unterphasen)
     * Format: [Phasenname => ['heading' => heading, 'duration' => Dauer,], ...]
     *
     * @param Version $version
     * @param bool $withSum Soll die Dauer aller Phasen ebenfalls angegeben werden?
     * @return array
     */
    public function getPhasesDuration(Version $version, $withSum = true) {
        $phases = $this->getPhases($version);
        $res = [];
        $fullDuration = 0;
        foreach ($phases as $name => $data) {
            $duration = 0;
            foreach ($data['phasen'] as $phase) {
                $duration += $phase->duration;
            }
            $res[$name] = ['heading' => $data['heading'], 'duration' => $duration];
            $fullDuration += $duration;
        }
        if ($withSum) {
            $res['gesamt'] = ['heading' => 'Gesamt', 'duration' => $fullDuration];
        }

        return $res;
    }

    /**
     * Gebe die Phasen des Projekts (Zeitplanung in der Planungsphase) als Array aus.
     * Format des Ergebnisses: [$name => ['heading' => $heading, 'phasen' => Phase[]], ['heading' => $heading, 'phasen' => Phase[]], ...]
     *
     * @param Version $version
     * @return array
     */
    public function getPhases(Version $version) {
        $res = [];
        $phasesSection = $this->findSection('phases', $version);
        if ($phasesSection) {
            foreach ($phasesSection->sections as $phase) {
                $res[$phase->name] = ['heading' => $phase->heading, 'text' => $phase->text];
            }
        }

        return $this->formatPhases($res);
    }

    /**
     * Formatiere die Phasen: In der Datenbank sind die Phasen als Text gespeichert (Phase1 : 2; Phase2 : 1; ...)
     * Gewünschtes Format: [$name => ['heading' => $heading, 'phasen' => Phase[]], ['heading' => $heading, 'phasen' => Phase[]], ...]
     *
     * @param array $phases
     * @return array
     */
    private function formatPhases(array $phases) {
        $res = [];
        foreach ($phases as $name => $phase) {
            $res[$name] = ['heading' => $phase['heading'], 'phasen' => [],];
            $temp = explode(';', $phase['text']);
            foreach($temp as $t) {
                if (empty(trim($t))) {
                    continue;
                }
                /*$temporary = explode(':', $t);
                array_push($res[$name]['phasen'], [
                    'name' => trim($temporary[0]),
                    'duration' => trim($temporary[1]),
                ]);*/
                array_push($res[$name]['phasen'], Phase::create($t));
            }
        }

        return $res;
    }

    public function getTopicAttribute() {
        $section = $this->findCurrentSection('topic');
        if ($section == false || ! $section->text) {
            return "Es ist ein Fehler aufgetreten: Der Abschnitt Thema liegt in dem Projektantrag nicht vor.";
        }
        else {
            return $section->text;
        }
    }

    public function getStart(Version $version) {
        $deadline = $this->findSection('deadline', $version);
        if ($deadline && $deadline->text) {
            $temp = explode('||', $deadline->text);
            return $temp[0];
        }
        else {
            return '';
        }
    }

    public function getEnd(Version $version) {
        $deadline = $this->findSection('deadline', $version);
        if ($deadline && $deadline->text) {
            $temp = explode('||', $deadline->text);
            return $temp[1];
        }
        else {
            return '';
        }
    }

    public function getStartAttribute() {
        return $this->getStart($this->latestVersion());
    }

    public function getEndAttribute() {
        return $this->getEnd($this->latestVersion());
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function sections() {
        return $this->hasMany(Section::class, 'proposal_id', 'id');
    }

    public function versions() {
        return $this->hasMany(Version::class);
    }
}
