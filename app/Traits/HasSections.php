<?php


namespace App\Traits;

use App\Section;

/**
 * Anträge, Dokumentationen und Abschnitte gehen gleich mit Abschnitten um.
 *
 * Trait HasSections
 * @package App\Traits
 */
trait HasSections
{
    /**
     * create the standard sections of a document (proposal or documentation)
     *
     * @param array $sectionValues Format: vgl. Konstante SECTIONS in Proposal und Documentation
     */
    public function makeSections(array $sectionValues) {
        foreach ($sectionValues as $sect) {
            $s = new Section($sect);
            $this->sections()->save($s);
            if ($s->sections) {
                $s->makeSections(array_key_exists('sections', $sect) ? $sect['sections'] : []);
            }
        }
    }
}
