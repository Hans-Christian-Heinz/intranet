<?php

namespace App;

use App\Structs\Kostenstelle;
use App\Traits\HasSections;
use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    // Die Standardabschnitte einer Projektdokumentation
    const SECTIONS = [
        //Titel
        ['name' => 'title', 'heading' => 'Titel', 'sequence' => 0, 'tpl' => 'dokumentation.title_section',],
        //Einleitung
        ['name' => 'intro', 'heading' => 'Einleitung', 'sequence' => 1, 'tpl' => 'parent_section', 'sections' => [
            ['name' => 'umfeld', 'heading' => 'Projektumfeld', 'sequence' => 0,],
            ['name' => 'ziel', 'heading' => 'Projektziel', 'sequence' => 1,],
            ['name' => 'begruendung', 'heading' => 'Projektbegründung', 'sequence' => 2,],
            ['name' => 'schnittstellen', 'heading' => 'Projektschnittstellen', 'sequence' => 3,],
            ['name' => 'abgrenzung', 'heading' => 'Projektabgrenzung', 'sequence' => 4,],
        ],],
        //Projektplanung
        ['name' => 'projekt_planung', 'heading' => 'Projektplanung', 'sequence' => 2, 'tpl' => 'parent_section', 'sections' => [
            ['name' => 'doku_phasen', 'heading' => 'Projektphasen', 'sequence' => 0, 'tpl' => 'dokumentation.phases_section',],
            ['name' => 'abweichungen', 'heading' => 'Abweichnungen vom Projektantrag', 'sequence' => 1,],
            ['name' => 'ressourcen', 'heading' => 'Ressourcenplanung', 'sequence' => 2, 'tpl' => 'dokumentation.ressourcen_parent_section', 'sections' => [
                ['name' => 'hardware', 'heading' => 'Hardware', 'sequence' => 0, 'tpl' => 'dokumentation.ressourcen_text_section',],
                ['name' => 'software', 'heading' => 'Software', 'sequence' => 1, 'tpl' => 'dokumentation.ressourcen_text_section',],
                ['name' => 'personal', 'heading' => 'Personal', 'sequence' => 2, 'tpl' => 'dokumentation.ressourcen_text_section',],
                ['name' => 'gesamt', 'heading' => 'Gesamtkosten', 'sequence' => 3, 'tpl' => 'dokumentation.ressourcen_gesamt_section',],
            ],],
            ['name' => 'entwicklungsprozess', 'heading' => 'Entwicklungsprozess', 'sequence' => 3,],
        ],],
        //Analysephase
        ['name' => 'analyse', 'heading' => 'Analysephase', 'sequence' => 3, 'tpl' => 'parent_section', 'sections' => [
            ['name' => 'ist_analyse', 'heading' => 'Ist-Analyse', 'sequence' => 0,],
            ['name' => 'wirtschaft_analyse', 'heading' => 'Wirtschaftlichkeitsanalyse (Make or Buy Entscheidung)', 'sequence' => 1,],
            ['name' => 'nutzwertanalyse', 'heading' => 'Nutzwertanalyse', 'sequence' => 2,],
            ['name' => 'anwendungsfaelle', 'heading' => 'Anwendungsfälle', 'sequence' => 3,],
            ['name' => 'quality', 'heading' => 'Qualitätsanforderungen', 'sequence' => 4,],
        ],],
        //Entwurfphase
        ['name' => 'entwurf_phase', 'heading' => 'Entwurfphase', 'sequence' => 4, 'tpl' => 'parent_section', 'sections' => [
            ['name' => 'plattform', 'heading' => 'Zielplattform', 'sequence' => 0,],
            ['name' => 'architektur', 'heading' => 'Architekturdesign', 'sequence' => 1,],
            ['name' => 'user_interface', 'heading' => 'Entwurf der Benutzeroberfläche', 'sequence' => 2,],
            ['name' => 'datenmodell', 'heading' => 'Datenmodell', 'sequence' => 3,],
            ['name' => 'geschaeftslogik', 'heading' => 'Geschäftslogik', 'sequence' => 4,],
            ['name' => 'qualitaetssicherung', 'heading' => 'Maßnahmen zur Qualitätssicherung', 'sequence' => 5,],
        ],],
        //Implementierungsphase
        ['name' => 'impl_phase', 'heading' => 'Implementierungsphase', 'sequence' => 5, 'tpl' => 'parent_section', 'sections' => [
            ['name' => 'datenstrukturen', 'heading' => 'Implementierung der Datenstrukturen', 'sequence' => 0,],
            ['name' => 'benutzeroberfl', 'heading' => 'Implementierung der Benutzeroberfläche', 'sequence' => 1,],
            ['name' => 'impl_geschaeftslogik', 'heading' => 'Implementierung der Geschäftslogik', 'sequence' => 2,],
        ],],
        //Abnahmephase
        ['name' => 'abnahme_phase', 'heading' => 'Abnahmephase', 'sequence' => 6,],
        //Einführungsphase
        ['name' => 'einfuehrung', 'heading' => 'Einführungsphase', 'sequence' => 7,],
        //Dokumentation
        ['name' => 'dokumentation', 'heading' => 'Dokumentation', 'sequence' => 8,],
        //Fazit
        ['name' => 'fazit', 'heading' => 'Fazit', 'sequence' => 9, 'tpl' => 'parent_section', 'sections' => [
            ['name' => 'soll_ist_vgl', 'heading' => 'Soll-Ist-Vergleich', 'sequence' => 0, 'tpl' => 'dokumentation.vgl_section'],
            ['name' => 'lessons', 'heading' => 'Lessons Learned', 'sequence' => 1,],
            ['name' => 'ausblick', 'heading' => 'Ausblick', 'sequence' => 2,],
        ],],
        //Anhang
        ['name' => 'anhang', 'heading' => 'Anhang', 'sequence' => 10,],
    ];

    use HasSections;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vc_locked',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        //'sections'
    ];

    /**
     * @param bool $withSum
     * @return mixed
     */
    public function getPhasesDifference($withSum = true) {
        $durations = $this->project->getPhasesDuration($withSum);
        $zeitplanung = $this->zeitplanung;
        foreach ($durations as $name => &$phase) {
            $phase['difference'] = $zeitplanung[$name] - $phase['duration'];
        }

        return $durations;
    }

    /**
     * Gebe für jede Kostenstellenkategorie ihre Kosten an (Summe der Kosten aller Kostenstellen in der Kategorie)
     * Format: [kategorie => Preis, ...]
     *
     * @param Version $version
     * @param bool $withSum Sollen die Gesamtkosten aller Kategorien ebenfalls angegeben werden?
     * @return array
     */
    public function getKostenstellenGesamt(Version $version, $withSum = true) {
        $kategorien = $this->getKostenstellen($version);
        $res = [];
        $fullCosts = 0.0;
        foreach ($kategorien as $heading => $kostenstellen) {
            $kosten = 0.0;
            foreach ($kostenstellen as $data) {
                //str_replace ist nötig, da floatval keine Kommata erkennt.
                $kosten += $data->prize;
            }
            $res[$heading] = number_format($kosten, 2);
            $fullCosts += $kosten;
        }
        if ($withSum) {
            $res['Summe'] = number_format($fullCosts, 2);
        }

        return $res;
    }

    /**
     * Gebe die Kostenstellen der Ressourcenplanung als Array aus.
     * Format des Egebnis: [$heading => Kostenstelle[]]
     *
     * @param Version $version
     * @return array
     */
    public function getKostenstellen(Version $version) {
        $res = [];
        $ressourcenSection = $this->findSection('ressourcen', $version);
        if ($ressourcenSection) {
            foreach ($ressourcenSection->sections as $subsection) {
                if ($subsection->name == 'gesamt') {
                    continue;
                }
                $res[$subsection->heading] = [];
                $text = $subsection->text;
                foreach (explode(';', $text) as $data) {
                    if (empty(trim($data))) {
                        continue;
                    }
                    array_push($res[$subsection->heading], Kostenstelle::create($data));
                }
            }
        }

        return $res;
    }

    public function getShortTitle(Version $version) {
        $title = $this->findSection('title', $version);
        if ($title && $title->text) {
            $temp = explode('||', $title->text);
            return $temp[0];
        }
        else {
            return '';
        }
    }

    public function getLongTitle(Version $version) {
        $title = $this->findSection('title', $version);
        if ($title && $title->text) {
            $temp = explode('||', $title->text);
            return $temp[1];
        }
        else {
            return '';
        }
    }

    public function getShortTitleAttribute() {
        return $this->getShortTitle($this->latestVersion());
    }

    public function getLongTitleAttribute() {
        return $this->getLongTitle($this->latestVersion());
    }

    public function getZeitplanung(Version $version) {
        $vgl = $this->findSection('soll_ist_vgl', $version);
        $keys = ['planung', 'entwurf', 'implementierung', 'test', 'abnahme',];
        if ($vgl && $vgl->text) {
            $temp = explode('##TEXTEND##', $vgl->text);
            $times = array_slice(explode(';', $temp[1]),0,5);
            $res = array_combine($keys, $times);
            $gesamt = 0;
            foreach($times as $time) {
                $gesamt += intval($time);
            }
            //array_push($res, ['text' => $temp[0]]);
            $res['text'] = $temp[0];
            $res['gesamt'] = $gesamt;
            return $res;
        }
        else {
            $res = array_combine($keys, [0,0,0,0,0,]);
            //array_push($res, ['text' => '']);
            $res['text'] = '';
            $res['gesamt'] = 0;
            return $res;
        }
    }

    /**
     * Return value: array-keys text, planung, entwurf, implementierung, test, abnahme, gesamt
     *
     * @return array
     */
    public function getZeitplanungAttribute() {
        return $this->getZeitplanung($this->latestVersion());
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
        return $this->hasMany(Section::class, 'documentation_id', 'id');
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
