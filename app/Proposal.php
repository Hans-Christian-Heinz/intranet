<?php

namespace App;

use App\Structs\Phase;
use App\Traits\HasComments;
use App\Traits\HasSections;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    // Die Abschnitte eines Projektantrags
    const SECTIONS = [
        ['name' => 'topic', 'heading' => 'Thema', 'counter' => 'none', 'sequence' => 0,],
        ['name' => 'deadline', 'heading' => 'Termin', 'counter' => 'none', 'sequence' => 1, 'tpl' => 'antrag.deadline_section',],
        ['name' => 'description', 'heading' => 'Beschreibung', 'counter' => 'none', 'sequence' => 2,],
        ['name' => 'environment', 'heading' => 'Umfeld', 'counter' => 'none', 'sequence' => 3,],
        ['name' => 'phases', 'heading' => 'Projektphasen mit Zeitplanung', 'counter' => 'none', 'sequence' => 4, 'tpl' => 'parent_section', 'sections' => self::PHASES,],
        ['name' => 'documentation', 'heading' => 'Dokumentation', 'counter' => 'none', 'sequence' => 5,],
        ['name' => 'attachments', 'heading' => 'Anlagen', 'counter' => 'none', 'sequence' => 6,],
        ['name' => 'presentation', 'heading' => 'Präsentationsmittel', 'counter' => 'none', 'sequence' => 7,],
    ];

    //Die Unterabschnitte des Abschnitts phases
    const PHASES = [
        ['name' => 'planung', 'heading' => 'Planung und Analyse', 'sequence' => 0, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'entwurf', 'heading' => 'Entwurf', 'sequence' => 1, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'implementierung', 'heading' => 'Implementierung', 'sequence' => 2, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'test', 'heading' => 'Test', 'sequence' => 3, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'abnahme', 'heading' => 'Abnahme und Dokumentation', 'sequence' => 4, 'tpl' => 'antrag.phases_text_section',],
    ];

    use HasSections, HasComments;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        //'sections',
    ];

    protected $fillable = [
        'vc_locked',
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
            foreach ($version->sections->where('section_id', $phasesSection->id) as $phase) {
                $p = [];
                /*//Text der Unterabschnitte liegt im Format "phasenname : dauer; phasenname : dauer; ..." vor
                //Hier wird davon ausgegangen, dass das Format stimmt. (Bei der Eingabe findet eine Validierung statt)
                foreach (explode(';', $phase->text) as $t) {
                    if (empty(trim($t))) {
                        continue;
                    }
                    array_push($p, Phase::create($t));
                }*/
                $val = json_decode($phase->text, true);
                if (is_null($val)) {
                    $val = [];
                }
                usort($val, function($a, $b) {
                    return $a['number'] - $b['number'];
                });
                foreach($val as $help) {
                    array_push($p, new Phase($help['Phasenname'], $help['Dauer']));
                }
                $res[$phase->name] = ['heading' => $phase->heading, 'phasen' => $p];
            }
        }

        return $res;
    }

    public function getTopicAttribute() {
        $section = $this->findCurrentSection('topic');
        if ($section == false || ! $section->text) {
            return "Projektthema wurde im Projektantrag noch nicht erfasst.";
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

    /**
     * @return User|null
     */
    public function getUser() {
        if ($this->project) {
            return $this->project->user;
        }

        return null;
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

    public function lockedBy() {
        return $this->belongsTo(User::class, 'locked_by', 'id');
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
