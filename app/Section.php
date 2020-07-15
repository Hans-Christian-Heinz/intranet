<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
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
    protected $with = ['sections'];

    /**
     * create the standard sections of a section
     */
    public function makeSections(array $sectionValues) {
        foreach ($sectionValues as $sect) {
            $s = new Section($sect);
            $this->sections()->save($s);
            if ($s->tpl === 'parent_section') {
                $s->makeSections($sect['sections'] ? $sect['sections'] : []);
            }
        }
    }

    public function proposal() {
        return $this->belongsTo(Proposal::class);
    }

    public function documentation() {
        return $this->belongsTo(Documentation::class);
    }

    public function parentSection() {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function sections() {
        return $this->hasMany(Section::class, 'section_id', 'id');
    }
}
