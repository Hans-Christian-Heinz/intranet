<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // Add all mass assignable attributes
    protected $fillable = [
        "name",
        "description",
        "address",
        "city",
        "state",
        "zip",
        "country",
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
