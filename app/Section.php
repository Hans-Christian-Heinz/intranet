<?php

namespace App;

use App\Structs\Phase;
use App\Traits\HasSections;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
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
        'sequence',
        'tpl'
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
     * Gibt den Inhalt des Abschnitts (für das HTML bzw. PDF Dokument) aus. Formatiert ggf den Inhalt.
     *
     * @return string
     */
    public function getContent() {
        switch ($this->name) {
            case 'deadline':
                if ($this->text) {
                    $help = explode('||', $this->text);
                    return 'Beginn: ' . Carbon::create($help[0])->format('d.m.Y') . "\n"
                        . 'Ende: ' . Carbon::create($help[1])->format('d.m.Y');
                }
                else {
                    return '';
                }
            default:
                return $this->text;
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
        return $this->belongsToMany(Version::class, 'sections_versions')->withTimestamps();
    }

    public function images() {
        return $this->belongsToMany(Image::class, 'images_sections')->withPivot('sequence');
    }
}
