<?php


namespace App\Traits;

use App\Documentation;
use App\Proposal;
use App\Section;
use App\Version;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Anträge, Dokumentationen und Abschnitte gehen gleich mit Abschnitten um.
 * Beachte: Jede Klasse, die diesen Trait verwendet, muss eine one-to-many Beziehung zu sections namens sections haben.
 * Beachte: Jede Klasse, die diesen Trait verwendet, muss eine Beziehung zu sections namens versions haben.
 *
 * Trait HasSections
 * @package App\Traits
 */
trait HasSections
{
    /**
     * Erhalte die akteullste Version dieses Dokuments
     *
     * @return Version
     */
    public function latestVersion() {
        return $this->versions()->orderBy('updated_at', 'DESC')->first();
    }

    /**
     * create the standard sections of a document (proposal or documentation)
     *
     * @param array $sectionValues Format: vgl. Konstante SECTIONS in Proposal und Documentation
     * @param Version $version
     */
    public function makeSections(array $sectionValues, Version $version) {
        foreach ($sectionValues as $sect) {
            $s = new Section($sect);
            $this->sections()->save($s);
            $version->sections()->save($s, ['sequence' => $sect['sequence']]);
            if ($s->sections) {
                $s->makeSections(array_key_exists('sections', $sect) ? $sect['sections'] : [], $version);
            }
        }
    }

    /**
     * Erhalte alle Abschnitte, die zu diesem Objekt und der übergebenen Version gehören.
     *
     * @param Version $version
     * @return Collection
     */
    public function getSections(Version $version) {
        if ($this instanceof Section) {
            return $version->sections->where('section_id', $this->id);
        }
        if ($this instanceof Proposal) {
            return $version->sections->where('proposal_id', $this->id);
        }
        if ($this instanceof Documentation) {
            return $version->sections->where('documentation_id', $this->id);
        }

        /*return $this->sections()->whereHas('versions', function(Builder $query) use ($version) {
            $query->where('versions.id', $version->id);
        })->orderBy('sequence')->get();*/
    }

    /**
     * Erhalte alle Abschnitte, die zu diesem Objekt und der akteullsten Version gehören
     *
     * @return Collection
     */
    public function getCurrentSections() {
        return $this->getSections($this->latestVersion());
    }

    /**
     * Durchsuche alle Unterabschnitte der übergebenen Version dieses Objekts nach dem Abschnitt mit dem gewünschten Namen.
     * Beachte: Es wird davon ausgegangen, dass der Name eines Abschnitts bzgl. seines Dokuments (Antrag oder Dokumentation)
     * eindeutig ist.
     *
     * @param $name
     * @param Version $version
     * @return bool|Section
     */
    public function findSection($name, Version $version) {
        foreach ($this->getSections($version) as $section) {
            if ($section->name == $name) {
                return $section;
            }
            $temp = $section->findSection($name, $version);
            if ($temp) {
                return $temp;
            }
        }
        return false;
    }

    /**
     * Durchsuche alle Unterabschnitte der aktuellen Version dieses Objekts nach dem Abschnitt mit dem gewünschten Namen.
     * Beachte: Es wird davon ausgegangen, dass der Name eines Abschnitts bzgl. seines Dokuments (Antrag oder Dokumentation)
     * eindeutig ist.
     *
     * @param $name
     * @return bool|Section
     */
    public function findCurrentSection($name) {
        return $this->findSection($name, $this->latestVersion());
    }

    /**
     * Erhalte alle Abschnitte, die zu diesem Objekt und der übergebenen Version gehören, inklusive der Unterabschnitte (rekursiv).
     *
     * @param Version $version
     * @return Collection
     */
    public function getAllSections(Version $version) {
        $res = $this->getSections($version);
        foreach ($this->getSections($version) as $sect) {
            //rekursiv
            $res = $res->merge($sect->getAllSections($version));
        }

        return $res;
    }
}
