<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'footnote',
        'path',
        'sequence',
        'width',
        'height',
    ];

    public function section() {
        return $this->belongsToMany(Section::class, 'images_sections');
    }
}
