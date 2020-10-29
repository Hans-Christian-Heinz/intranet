<?php

namespace App;

use App\Helpers\General\IncrementCounter;
use App\Structs\ImagePlaceholder;
use App\Structs\Link;
use App\Structs\ListStruct;
use App\Structs\Phase;
use App\Structs\Table;
use App\Traits\HasSections;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Section extends Model
{
    /**
     * Templates, die für einen Abschnitt im Formular für einen Projektantrag oder eine Projektdokumentation zur Verfügung stehen
     * Wird im Template abschlussprojekt.sections.dokumentation.addSectionModal und editSectionModal verwendet
     */
    const TEMPLATES = [
        'text_section',
        'parent_section',
        'tinymce_section',
        'antrag.deadline_section',
        'antrag.phases_parent_section',
        'antrag.phases_text_section',
        'dokumentation.abbreviations_section',
        'dokumentation.phases_section',
        'dokumentation.ressourcen_gesamt_section',
        'dokumentation.ressourcen_parent_section',
        'dokumentation.ressourcen_text_section',
        'dokumentation.title_section',
        'dokumentation.vgl_section',
    ];

    /**
     * Objekte, die in Abschnitte eingefügt werden können. Konstante wird im Template abschlussprojekt.insertModal.inserModal verwendet
     */
    const INSERT = [
        'tabelle',
        'liste',
        'link',
        'bild',
        //'dokument'
    ];

    const PLACEHOLDERS = [
        '##TABLE(',
        '##LIST(',
        '##LINK(',
        '##IMAGE(',
        //'##DOCUMENT(',
    ];

    /**
     * Vorlagen, auf denen die Tabellen in Projektdokumentation und -antrag beruhen
     */
    const TABLETPLS = [
        'phases' => [
            ['name' => 'Phasenname', 'type' => 'text', 'required' => true, 'def' => 'Phasenname'],
            ['name' => 'Dauer', 'type' => 'number', 'step' => 1, 'min' => 1, 'required' => true, 'def' => 1],
        ],
        'abbr' => [
            ['name' => 'Abkürzung', 'type' => 'text', 'required' => true, 'def' => 'Abkürzung'],
            ['name' => 'Ausgeschrieben', 'type' => 'text', 'required' => true, 'def' => 'Ausgeschrieben'],
        ],
        'kostenstellen' => [
            ['name' => 'Name', 'type' => 'text', 'required' => true, 'def' => 'Name'],
            ['name' => 'Beschreibung', 'type' => 'text', 'required' => false, 'def' => 'Beschreibung'],
            ['name' => 'Kosten', 'type' => 'number', 'step' => 0.1, 'min' => 0, 'required' => true, 'def' => 0.00],
        ],
    ];

    use HasSections;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'heading',
        'text',
        'tpl',
        'counter',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'images',
        //'sections',
    ];

    /**
     * Die id
     *
     * @return int
     */
    public static function nextId() {
        return DB::table('sections')->max('id') + 1;
    }

    /**
     * accessor for name
     *
     * @param $value
     * @return string
     */
    public function getNameAttribute($value) {
        if ($value) {
            return $value;
        }
        else {
            return 'section' . $this->id;
        }
    }

    /**
     * mutator for name
     *
     * @param $value
     */
    public function setNameAttribute($value) {
        if ($value === 'section' . $this->id) {
            $this->attributes['name'] = null;
        }
        else {
            $this->attributes['name'] = $value;
        }
    }

    /**
     * Ersetze alle Platzhalter in einem Abschnitt durch entsprechende Structs (App\Structs). Beim Generieren eines PDF-Dokuments
     * werden die Structs dann durch passende HTML-Syntax ersetzt.
     *
     * @param IncrementCounter $table_nr
     * @return array
     */
    public function formatText(IncrementCounter $table_nr) {
        if ($this->tpl == 'tinymce_section') {
            $text = $this->formatTextTinymce($table_nr);
        }
        else {
            $text = $this->text;
        }
        $text = $this->abbreviationLinks($text);
        $res = [];

        $pos = strpos($text, '##');
        $countImg = 0;
        //Falls keine Platzhalter vorliegen, sind auch keine Platzhalter zu ersetzen
        if ($pos !== false) {
            //Schleife: solange noch ein Platzhalter(kandidat) gefunden wird
            while ($pos !== false) {
                //Versuche nur dann, Platzhalter zu ersetzen, wenn zumindest ein valider Platzhaltername vorliegt.
                $valid = false;
                foreach(self::PLACEHOLDERS as $placeholder) {
                    if (strpos($text, $placeholder) === $pos) {
                        $valid = true;
                    }
                }
                //Wenn ein vermuteter Platzhalter kein klares Ende hat, wird er nicht als Platzhalter behandelt.
                $end = strpos($text, ')##', $pos + 2);
                if ($end === false) {
                    $valid = false;
                }
                if (!$valid) {
                    //Wenn kein Platzhalter gefunden wurde: Gehe ein Zeichen weiter als der Beginn des Platzhalterkandidats und versuche es erneut
                    array_push($res, substr($text, 0, $pos + 1));
                    $text = substr($text, $pos + 1);
                    $pos = strpos($text, '##');
                    continue;
                }

                //Nehme den Text vor dem Platzhalter in das Ergebnis auf
                array_push($res, substr($text, 0, $pos));
                $help = strpos($text,'(', $pos);
                $type = substr($text, $pos + 2, $help - $pos -2);
                $create = substr($text, $pos, $end - $pos + 3);
                switch($type) {
                    case 'IMAGE':
                        if ($countImg < $this->images->count()) {
                            array_push($res, new ImagePlaceholder($countImg));
                            $countImg++;
                        }
                        else {
                            array_push($res, "\n");
                        }
                        break;
                    case 'TABLE':
                        array_push($res, Table::create($create));
                        break;
                    case 'LIST':
                        array_push($res, ListStruct::create($create));
                        break;
                    case 'LINK':
                        array_push($res, Link::create($create));
                        break;
                }

                $text = substr($text, $end + 3);
                $pos = strpos($text, '##');
            }
        }

        //Der Rest des Texts.
        array_push($res, $text);

        if ($this->images->count() > $countImg) {
            for ($i = $countImg; $i < $this->images->count(); $i++) {
                array_push($res, new ImagePlaceholder($i));
            }
        }

        //Fasse aufeinanderfolgende Textblöcke im Array zusammen
        return $this->combineTexts($res);
    }

    /**
     * Füge Tabellen zum Tabellenverzeichnis hinzu und aktualisiere ihre Fußnote / Überschrift mit ihrer Nummer
     *
     * @param IncrementCounter $table_nr
     * @return string
     */
    private function formatTextTinymce(IncrementCounter $table_nr) {
        $text = $this->text;
        //Suche nach <table>-tags
        $pos = strpos($text, '<table');
        $end = strpos($text, '</table>');
        while($pos !== false) {
            //determine the table's caption
            $captPos = strpos($text, '<caption', $pos);
            if ($captPos === false || $captPos >= $end) {
                $caption = '<caption>Tabelle ' . $table_nr->nextNumber() . ': </caption>';
                $captPosHelp = strpos($text, '>', $pos) + 1;
                $text = substr_replace($text, $caption, $captPosHelp, 0);
                $addLength = strlen($caption);
                $caption = 'Tabelle ' . $table_nr->getNumber() . ': ';
            }
            else {
                $captEnd = strpos($text, '</caption>', $pos);
                $captContentStart = strpos($text, '>', $captPos) + 1;
                $caption = substr($text, $captContentStart, $captEnd - $captContentStart);
                $caption = 'Tabelle ' . $table_nr->nextNumber() . ': ' . $caption;
                $text = substr_replace($text, $caption, $captContentStart, $captEnd - $captContentStart);
                $addLength = strlen('Tabelle ' . $table_nr->getNumber() . ': ');
            }
            $toc_entry = '<tocentry content="' . $caption . '" name="toc_tables"/>';
            $addLength = $addLength + strlen($toc_entry);
            $text = substr_replace($text, $toc_entry, $pos, 0);
            $pos = strpos($text, '<table', $pos + $addLength + 1);
            $end = strpos($text, '</table>', $end + $addLength + 1);
        }

        return $text;
    }

    /**
     * Hilfsmethode, um im Ergebnis von formatText($text) aufeinanderfolgende Textblöcke zusammenzufassen
     * (gemeint: [$text1, $text2, $tabelle, ...] wird zu [$text1 . $text2, $tabelle, ...]
     *
     * @param array $values
     * @return array
     */
    private function combineTexts(array $values) {
        $res = [];
        for ($i = 0; $i < count($values); $i++) {
            if (is_string($values[$i])) {
                $temp = '';
                while($i < count($values) && is_string($values[$i])) {
                    $temp = $temp . $values[$i];
                    $i++;
                }
                array_push($res, $temp);
                $i--;
            }
            else {
                array_push($res, $values[$i]);
            }
        }

        return $res;
    }

    /**
     * Hilfsmethode, die in formatText() aufgerufen wird.
     * Durchsuche den übergebenen Text nach allen Abkürzungen im Abkürzungsverzeichnis und erstze sie durch Platzhalter für
     * Links, die auf das Abkürzungsverzeichnis zeigen.
     *
     * @param $text
     * @return string|string[]
     */
    private function abbreviationLinks($text) {
        $dokumentation = $this->getUltimateParent();
        if (! $dokumentation instanceof Documentation) {
            return $text;
        }
        else {
            //Die Abkürzungen aus dem Abkürzungsverzeichnis, sofern dieses vorliegt.
            $abbreviations = array_keys($dokumentation->abbreviations);
            foreach ($abbreviations as $abbr) {
                $length = strlen($abbr);
                //stripos ignoriert Groß- und Kleinschreibung
                $pos = stripos($text, $abbr);
                while ($pos !== false) {
                    $help = substr($text, $pos, $length);
                    $placeholder = '##LINK(abbreviations, ' . $help . ')##';
                    //Stelle sicher, dass die Abkürzung nicht Teil eines anderen Wortes ist:
                    //Entweder steht sie nach einem Leerzeichen oder nach einer offenen Klammer oder am Beginn des Abschnitts.
                    //Entweder steht sie vor einem Leerzeichen oder vor einem Satzzeichen oder am Ende des Abschnitts.
                    $valid = $pos == strlen($text) - $length || in_array($text{$pos + $length}, [' ', '.', ',', ';', '-', '_', ':', '!', '?', ')', ']', '}',]);
                    $valid = $valid && ($pos === 0 || in_array($text{$pos - 1}, [' ', '(', '[', '{',]));
                    if (! $valid) {
                        $pos = $pos = stripos($text, $abbr, $pos + 1);
                        continue;
                    }
                    $text = substr_replace($text, $placeholder, $pos, $length);
                    $pos = stripos($text, $abbr, $pos + strlen($placeholder));
                }
            }
            return $text;
        }
    }

    /**
     * Nummeriere die Überschrift des Abschnitts (beim Generieren eines PDF-Dokuments)
     * Erlaube separate Nummerierung für den Anhang
     *
     * @param IncrementCounter $inhalt_counter aktuelle Nummer im Inhaltsverzeichnis
     * @param Version $version
     * @return string
     */
    public function getNumberedHeading(IncrementCounter $inhalt_counter, Version $version) {
        $res = '';
        //Lese die Abschnitte erneut aus der Datenbank aus, um auf das Pivot-Feld sequence zugreifen zu können
        //$section = $version->sections()->where('id', $this->id)->first();
        $section = $this;
        while (! is_null($section->section)) {
            if (empty($res)) {
                $res = strval($section->pivot->sequence + 1);
            }
            else {
                $res = $section->pivot->sequence + 1 . '.' . $res;
            }
            $section = $version->sections()->where('id', $section->section_id)->first();
        }
        switch($section->counter) {
            case 'inhalt':
                $res = strval($inhalt_counter->getNumber()) . '.' . $res;
                break;
            case 'anhang':
                if (! is_null($this->section)) {
                    $res = 'A' . $res;
                }
                break;
            default:
                $res = '';
                break;
        }

        return $res . '  ' . $this->heading;
    }

    /**
     * Sperre oder entsperre den Abschnitt und alle Unterabschnitte
     *
     * @param bool $lock
     */
    public function lock(bool $lock) {
        $this->is_locked = $lock;
        $this->save();
        foreach ($this->sections as $section) {
            $section->lock($lock);
        }
    }

    /**
     * Gebe die Unterphasen einer Phase als Array aus.
     * Beachte: Es wird davon ausgegeangen, dass diese Methode nur auf einem passenden Abschnitt aufgerufen wird.
     * TODO abschaffen, sobald Alternative fertig implementiert ist
     *
     * @return array
     */
    public function getPhases() {
        $phases = [];
        $gesamtDauer = 0;
        foreach(explode(';', $this->text) as $help) {
            if (empty(trim($help))) {
                continue;
            }
            $tmp = Phase::create($help);
            $gesamtDauer += $tmp->duration;
            array_push($phases, $tmp);
        }

        $phases['gesamt'] = new Phase('Gesamt', $gesamtDauer);

        return $phases;
    }

    /**
     * Gebe die Unterphasen einer Phase als Array aus.
     * Beachte: Es wird davon ausgegeangen, dass diese Methode nur auf einem passenden Abschnitt aufgerufen wird.
     *
     * @return array
     */
    public function getPhasesNew() {
        $val = json_decode($this->text, true);
        $res = [];
        usort($val, function($a, $b) {
            return $a['number'] - $b['number'];
        });
        $gesamt = 0;
        foreach ($val as $v) {
            array_push($res, new Phase($v['Phasenname'], $v['Dauer']));
            $gesamt += $v['Dauer'];
        }
        $res['gesamt'] = new Phase('Gesamt', $gesamt);

        return $res;
    }

    /**
     * @return User|null
     */
    public function getUser() {
        if ($this->getParent()) {
            return $this->getParent()->getUser();
        }

        return null;
    }

    /**
     * @return Section|Documentation|Proposal|null
     */
    public function getParent() {
        if ($this->section) {
            return $this->section;
        }
        if ($this->documentation) {
            return $this->documentation;
        }
        if ($this->proposal) {
            return $this->proposal;
        }

        return null;
    }

    /**
     * @return Documentation|Proposal|null
     */
    public function getUltimateParent() {
        if ($this->getParent() instanceof Section) {
            return $this->section->getUltimateParent();
        }
        else {
            return $this->getParent();
        }
    }

    public function proposal() {
        return $this->belongsTo(Proposal::class);
    }

    public function documentation() {
        return $this->belongsTo(Documentation::class);
    }

    public function section() {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function sections() {
        return $this->hasMany(Section::class, 'section_id', 'id');
    }

    public function versions() {
        return $this->belongsToMany(Version::class, 'sections_versions')
            ->withTimestamps()
            ->withPivot('sequence');
    }

    public function images() {
        return $this->belongsToMany(Image::class, 'images_sections')
            ->withPivot('sequence')
            ->orderBy('pivot_sequence');
    }
}
