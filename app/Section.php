<?php

namespace App;

use App\Traits\HasSections;
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
    protected $with = ['sections'];

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
