<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $with = [
        'sections',
    ];

    public function proposal() {
        return $this->belongsTo(Proposal::class);
    }

    public function documentation() {
        return $this->belongsTo(Documentation::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function sections() {
        return $this->belongsToMany(Section::class, 'sections_versions')->withTimestamps();
    }
}
