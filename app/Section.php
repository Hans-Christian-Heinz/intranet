<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    public function proposal() {
        return $this->belongsTo(Proposal::class);
    }

    public function documentation() {
        return $this->belongsTo(Documentation::class);
    }
}
