<?php


namespace App\Traits;

use App\Section;

/**
 * Anträge, Dokumentationen und Abschnitte gehen gleich mit Abschnitten um.
 * Beachte: Jede Klasse, die diesen Trait verwendet, muss eine one-to-many Beziehung zu sections namens sections haben.
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

    /**
     * Durchsuche alle Unterabschnitte dieses Objekts nach dem Abschnitt mit dem gewünschten Namen.
     * Beachte: Es wird davon ausgegangen, dass der Name eines Abschnitts bzgl. seines Dokuments (Antrag oder Dokumentation)
     * eindeutig ist.
     *
     * @param $name
     * @return bool|Section
     */
    public function findSection($name) {
        foreach ($this->sections as $section) {
            if ($section->name == $name) {
                return $section;
            }
            $temp = $section->findSection($name);
            if ($temp) {
                return $temp;
            }
        }
        return false;
    }
}
