<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class SectionTextComponentRule implements Rule
{
    private $message;
    private $sectionNames;
    private $username;

    /**
     * Create a new rule instance.
     *
     * @param array $sectionNames
     * @param User $user
     */
    public function __construct(array $sectionNames, User $user)
    {
        $this->message = 'Das Format des Werts passt nicht zu den Anforderungen der Applikation.';
        $this->sectionNames = $sectionNames;
        $this->username = $user->ldap_username;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $val = json_decode($value, true);
        foreach ($val as $i => $v) {
            if (! isset($v['type'])) {
                $this->message = 'Die Komponenete ' . $i . ' hat keinen definierten Typ.';
                return false;
            }

            switch ($v['type']) {
                case 'text':
                    if (! isset($v['val'])) {
                        $this->message = 'Für die Text-Komponente ' . $i . ' ist kein Wert definiert.';
                        return false;
                    }
                    break;
                case 'table':
                    if (! isset($v['caption'])) {
                        $this->message = 'Das caption_feld (Tabellenname) der Tabelle ' . $i .  ' ist nicht definiert.';
                        return false;
                    }
                    if (! isset($v['rows']) || ! is_array($v['rows'])) {
                        $this->message = 'Das rows-Feld der Tabelle ' . $i .  ' ist nicht definiert.';
                        return false;
                    }
                    foreach ($v['rows'] as $row) {
                        if (! isset($row['is_header'])) {
                            $this->message = 'Für die Tabelle ' . $i . ' ist nicht für alle Zeilen definiert, ob sie Kopfzeilen sind.';
                            return false;
                        }
                        if (! isset($row['cols']) || ! is_array($row['cols'])) {
                            $this->message = 'Für die Tabelle ' . $i . ' sind nicht für alle Zeilen Spalten definiert.';
                            return false;
                        }
                        foreach ($row['cols'] as $col) {
                            if (! isset($col['text'])) {
                                $this->message = 'Für die Tabelle ' . $i . ' ist nicht für alle Zellen ein Wert definiert.';
                                return false;
                            }
                        }
                    }
                    break;
                case 'list':
                    if (! isset($v['order']) || ! in_array($v['order'], ['unordered', '1', 'a', 'A', 'i', 'I',])) {
                        $this->message = 'Für die Liste ' . $i . ' ist keine Aufzählungsart definiert.';
                        return false;
                    }
                    if (! isset($v['items']) || ! is_array($v['items'])) {
                        $this->message = 'Für die Liste ' . $i . ' sind keine Listenelemente definiert.';
                        return false;
                    }
                    foreach ($v['items'] as $item) {
                        if (! isset($item['text'])) {
                            $this->message = 'Für die Liste ' . $i . ' ist nicht für alle Listenelemente ein Inhalt definiert.';
                            return false;
                        }
                    }
                    break;
                case 'link':
                    if (! isset($v['text']) || ! $v['text']) {
                        $this->message = 'Für den Link ' . $i . ' ist kein Text definiert.';
                        return false;
                    }
                    if (! isset($v['target']) || ! in_array($v['target'], $this->sectionNames)) {
                        $this->message = 'Für den Link ' . $i . ' ist kein valides Ziel (in Abschnitt des Dokuments) definiert.';
                        return false;
                    }
                    break;
                case 'img':
                    if (! isset($v['path']) || ! Storage::disk('public')->exists($v['path']) || strpos($v['path'], 'images/' . $this->username) !== 0) {
                        $this->message = 'Die ausgewählte Bilddatei in Komponente ' . $i . ' existiert nicht oder gehört einem anderen Benutzer.';
                        return false;
                    }
                    if (! isset($v['footnote']) || strlen($v['footnote']) > 255) {
                        $this->message = 'Für das Bild in Komponente '. $i . ' wurde keine gültige Fußnote angegeben.';
                        return false;
                    }
                    if (! isset($v['height']) || ctype_digit($v['height']) || $v['height'] < 10 || $v['height'] > 247) {
                        $this->message = 'Für das Bild in Komponente '. $i . ' wurde keine gültige Höhe angegeben.';
                        return false;
                    }
                    if (! isset($v['width']) || ctype_digit($v['width']) || $v['width'] < 10 || $v['width'] > 170) {
                        $this->message = 'Für das Bild in Komponente '. $i . ' wurde keine gültige Breite angegeben.';
                        return false;
                    }
                    break;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
