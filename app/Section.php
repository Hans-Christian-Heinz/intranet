<?php

namespace App;

use App\Structs\Phase;
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
