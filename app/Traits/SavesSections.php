<?php


namespace App\Traits;


use App\Documentation;
use App\Http\Requests\AddImageRequest;
use App\Http\Requests\CreateSectionRequest;
use App\Proposal;
use App\Section;
use App\Version;
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
     * Beachte: In Zukunft werden unveränderte Abschnitte mit der neuen Version assoziiert; nur für veränderte Abschnitte
     * werden neue Instanzen angelegt.
     *
     * @param Request $request
     * @param Documentation|Proposal|Section $parent
     * @param Version $version
     * @param Version $versionOld
     * @param Section $oldSection
     * @return bool false, falls versucht wurde einen gesperrter Abschnitt zu ändern
     * @throws \Exception
     */
    private function saveSection(Request $request, $parent, Version $version, Version $versionOld, Section $oldSection) {
        $name = $oldSection->name;
        //Erstelle ein neues Objekt, falls sich der Inhalt des Abschnitts geändert hat
        if (! $this->sectionMatches($request, $oldSection)) {
            //Wenn ein Abschnitt, der gesperrt ist geändert wurde: Lösche die neu angelegte Version und gebe eine Fehlermeldung aus.
            if ($oldSection->is_locked) {
                $version->delete();
                return false;
            }
            $section = $oldSection->replicate();
            $section->text = $this->getSectionText($request, $name);
            $parent->sections()->save($section);
            foreach ($oldSection->images as $image) {
                $section->images()->save($image, ['sequence' => $image->pivot->sequence]);
            }
        }
        else {
            $section = $oldSection;
        }
        $version->sections()->save($section, ['sequence' => $oldSection->pivot->sequence]);

        foreach($oldSection->getSections($versionOld) as $child) {
            if (!$this->saveSection($request, $section, $version, $versionOld, $child)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Hilfsmethode, die überprüft, ob sich der Inhalt eines Abschnitts geändert hat. Notwendig für die Abschnitte, die
     * nicht durch ein Textfeld beschrieben werden
     *
     * @param Request $request
     * @param Section $oldSection
     * @return bool
     */
    private function sectionMatches(Request $request, Section $oldSection) {
        $name = $oldSection->name;
        //Nehme keine Änderungen an gesperrten Abschnitten vor
        if ($oldSection->is_locked) {
            return true;
        }
        switch ($name) {
            case 'deadline':
                if (! $oldSection->text) {
                    return is_null($request->start) && is_null($request->end);
                }
                $temp = explode('||', $oldSection->text);
                return $request->start == $temp[0] && $request->end == $temp[1];
            case 'title':
                if (! $oldSection->text) {
                    return is_null($request->shortTitle) && is_null($request->longTitle);
                }
                $temp = explode('||', $oldSection->text);
                return $request->shortTitle == $temp[0] && $request->longTitle == $temp[1];
            case 'soll_ist_vgl':
                $help = ['planung', 'entwurf', 'implementierung', 'test', 'abnahme',];
                if (! $oldSection->text) {
                    $res = is_null($request->$name);
                    foreach ($help as $h) {
                        $res = $res && $request->$h == 0;
                    }
                    return $res;
                }
                $temp = explode('##TEXTEND##', $oldSection->text);
                $res = $request->$name == $temp[0];
                $durations = explode(';', $temp[1]);
                for ($i = 0; $i < count($help); $i++) {
                    $key = $help[$i];
                    $res = $res && $request->$key == $durations[$i];
                }
                return $res;
            default:
                return $request->$name == $oldSection->text;
        }
    }

    /**
     * Für die Abschnitte, die nichjt durch ein Textfeld ausgefüllt werden, muss die Benutzereingabe in Textformat formatiert werden-
     *
     * @param Request $request
     * @param $name
     * @return string
     */
    private function getSectionText(Request $request, $name) {
        switch ($name) {
            case 'deadline':
                return $request->start . '||' . $request->end;
            case 'title':
                return $request->shortTitle . '||' . $request->longTitle;
            case 'soll_ist_vgl':
                $help = ['planung', 'entwurf', 'implementierung', 'test', 'abnahme'];
                $text = $request->$name . '##TEXTEND##';
                foreach($help as $h) {
                    $text .= $request->$h ? $request->$h . ';' : '0;';
                }
                return $text;
            default:
                return $request->$name;
        }
    }

    /*
     * Hilfsmethode zum Speichern eines Abschnitts.
     * Beachte: Beim Speichern wird ein neuer Eintrag in der Datenbank angelegt; der alte Eintrag bleibt unverändert.
     * Alte Version der Methode, bevor Tabelle versionen vorhanden war.
     *
     * @param Request $request
     * @param Documentation|Proposal|Section $parent
     * @param $old
     */
    /*private function saveSectionOld(Request $request, $parent, $old) {
        $section = $old->replicate();
        $name = $old->name;
        $section->text = $request->$name;
        $parent->sections()->save($section);
        //ggf Unterabschnitte
        foreach ($old->sections as $child) {
            $this->saveSection($request, $section, $child);
        }
    }*/
}
