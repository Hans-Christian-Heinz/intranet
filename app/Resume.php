<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    protected $fillable = ["user_id", "data", "passbild",];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
