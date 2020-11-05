<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    public const DATATYPES = ["png", "jpg", "jpeg"];

    protected $fillable = ["user_id", "data",];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
