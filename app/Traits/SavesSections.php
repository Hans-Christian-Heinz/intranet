<?php


namespace App\Traits;


use App\Documentation;
use App\Proposal;
use App\Section;
use Illuminate\Http\Request;

/**
 * Das Speichern von Abschnitten funktioniert für Anträge und Dokumentationen identisch: verwende einen Trait.
 *
 * Trait SavesSections
 * @package App\Traits
 */
trait SavesSections
{
    /**
     * Hilfsmethode zum Speichern eines Abschnitts.
     * Beachte: Beim Speichern wird ein neuer Eintrag in der Datenbank angelegt; der alte Eintrag bleibt unverändert.
     *
     * @param Request $request
     * @param Documentation|Proposal|Section $parent
     * @param $old
     */
    private function saveSection(Request $request, $parent, $old) {
        $section = $old->replicate();
        $name = $old->name;
        $section->text = $request->$name;
        $parent->sections()->save($section);
        //ggf Unterabschnitte
        foreach ($old->sections as $child) {
            $this->saveSection($request, $section, $child);
        }
    }
}
