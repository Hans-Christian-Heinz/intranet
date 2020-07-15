<?php

namespace App;

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
        ['name' => 'planung', 'heading' => 'Projektplanung', 'sequence' => 2, 'tpl' => 'parent_section', 'sections' => [
            ['name' => 'phasen', 'heading' => 'Projektphasen', 'sequence' => 0, 'tpl' => 'dokumentation.phases_section',],
            ['name' => 'abweichungen', 'heading' => 'Abweischnungen vom Projektantrag', 'sequence' => 1,],
            ['name' => 'ressourcen', 'heading' => 'Ressourcenplanung', 'sequence' => 2, 'tpl' => 'dokumentation.ressourcen_parent_section', 'sections' => [
                ['name' => 'hardware', 'heading' => 'Hardware', 'sequence' => 0, 'tpl' => 'dokumentation.ressourcen_text_section',],
                ['name' => 'software', 'heading' => 'Software', 'sequence' => 1, 'tpl' => 'dokumentation.ressourcen_text_section',],
                ['name' => 'personal', 'heading' => 'Personal', 'sequence' => 2, 'tpl' => 'dokumentation.ressourcen_text_section',],
                ['name' => 'gesamt', 'heading' => 'Gesamtkoosten', 'sequence' => 3, 'tpl' => 'dokumentation.ressourcen_gesamt_section',],
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
        ['name' => 'entwurf', 'heading' => 'Entwurfphase', 'sequence' => 4, 'tpl' => 'parent_section', 'sections' => [
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
        ['name' => 'abnahme', 'heading' => 'Abnahmephase', 'sequence' => 6,],
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
        'shortTitle',
        'longTitle',
        'planung',
        'entwurf',
        'implementierung',
        'test',
        'abnahme',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['sections'];

    /**
     * @param bool $withSum
     * @return mixed
     */
    public function getPhasesDifference($withSum = true) {
        $durations = $this->project->getPhasesDuration($withSum);
        foreach ($durations as $name => &$phase) {
            $phase['difference'] = $this->$name - $phase['duration'];
        }

        return $durations;
    }

    /**
     * @return int
     */
    public function getGesamtAttribute() {
        return $this->planung + $this->entwurf + $this->implementierung + $this->test + $this-> abnahme;
    }

    /**
     * Gebe für jede Kostenstellenkategorie ihre Kosten an (Summe der Kosten aller Kostenstellen in der Kategorie)
     * Format: [kategorie => Preis, ...]
     *
     * @param bool $withSum Sollen die Gesamtkosten aller Kategorien ebenfalls angegeben werden?
     * @return array
     */
    public function getKostenstellenGesamt($withSum = true) {
        $kategorien = $this->getKostenstellen();
        $res = [];
        $fullCosts = 0.0;
        foreach ($kategorien as $heading => $kostenstellen) {
            $kosten = 0.0;
            foreach ($kostenstellen as $data) {
                //str_replace ist nötig, da floatval keine Kommata erkennt.
                $kosten += floatval(str_replace(',', '.', $data['price']));
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
     *
     * @return array
     */
    public function getKostenstellen() {
        $res = [];
        $ressourcenSection = $this->findSection('ressourcen');
        if ($ressourcenSection) {
            foreach ($ressourcenSection->sections as $subsection) {
                if ($subsection->name == 'gesamt') {
                    continue;
                }
                $res[$subsection->heading] = $subsection->text;
            }
        }

        return $this->formatKostenstellen($res);
    }

    /**
     * Formatiere die Kostenstellen: In der Datenbank sind die Kostenstellen als Text gespeichert (KS1 : Beschr : 2,49; KS2 : : 1; ...)
     * Gewünschtes Format: ['name' => 'KS1', 'description' => 'Beschr', 'price' => 2,], [....
     *
     * @param array $kostenstellen
     * @return array
     */
    private function formatKostenstellen(array $kostenstellen) {
        $res = [];
        foreach ($kostenstellen as $header => $kostenstelle) {
            $res[$header] = [];
            $temp = explode(';', $kostenstelle);
            foreach($temp as $t) {
                if (empty(trim($t))) {
                    continue;
                }
                $temporary = explode(':', $t);
                array_push($res[$header], [
                    'name' => trim($temporary[0]),
                    'description' => trim($temporary[1]),
                    'price' => trim($temporary[2]),
                ]);
            }
        }

        return $res;
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function changedBy() {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }

    public function sections() {
        return $this->hasMany(Section::class, 'documentation_id', 'id');
    }
}
