<?php

namespace App;

use App\Structs\ImagePlaceholder;
use App\Structs\Link;
use App\Structs\ListStruct;
use App\Structs\Phase;
use App\Structs\Table;
use App\Traits\HasSections;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    const TEMPLATES = [
        'text_section',
        'parent_section',
        'antrag.deadline_section',
        'antrag.phases_parent_section',
        'antrag.phases_text_section',
        'dokumentation.phases_section',
        'dokumentation.ressourcen_gesamt_section',
        'dokumentation.ressourcen_parent_section',
        'dokumentation.ressourcen_text_section',
        'dokumentation.title_section',
        'dokumentation.vgl_section',
    ];

    const INSERT = [
        'tabelle',
        'liste',
        'link',
        'bild',
    ];

    const PLACEHOLDERS = [
        '##TABLE(',
        '##LIST(',
        '##LINK(',
        '##IMAGE(',
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

    public function formatText() {
        $text = $this->text;
        $res = [];

        $pos = strpos($text, '##');
        $countImg = 0;
        //Falls keine Platzhalter vorliegen, sind auch keine Platzhalter zu ersetzen
        if ($pos === false) {
            $res = [$text];
        }
        else {
            //Schleife: solange noch ein Platzhalter(kandidat) gefunden wird
            while ($pos !== false) {
                //Versuche nur dann Platzhalter zu ersetzen, wenn zumindest ein valider Platzhaltername vorliegt.
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
                    array_push($res, substr($text, 0, $pos + 2));
                    $text = substr($text, $pos + 2);
                    $pos = strpos($text, '##');
                    continue;
                }

                //Nehme den Text vor dem Platzhalter in das Ergebnis auf
                array_push($res, substr($text, 0, $pos));
                $help = strpos($text,'(');
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

        if ($this->images->count() > $countImg) {
            for ($i = $countImg; $i < $this->images->count(); $i++) {
                array_push($res, new ImagePlaceholder($i));
            }
        }

        return $res;
    }

    /**
     * Liefert zwei Arrays: Der Text und die Links die ihn unterbrechen
     *
     * @param $text
     * @return array[]
     */
    public static function separateLinks($text) {
        $pos = strpos($text,'##LINK(');
        if ($pos === false) {
            return ['text' => [$text,], 'links' => []];
        }

        $res = ['text' => [], 'links' => [],];
        $start = 0;
        while($pos !== false) {
            array_push($res['text'], substr($text, $start, $pos - $start));
            $help = substr($text, $pos, strpos($text, ')##', $pos) - $pos + 3);
            array_push($res['links'], Link::create($help));
            $start = strpos($text, ')##', $start) + 3;
            $pos = strpos($text,'##LINK(', $start);
        }
        array_push($res['text'], substr($text, $start));

        return $res;
    }

    /**
     * Sperre oder entsperre den Abschnitt und alle Unterabschnitte
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
