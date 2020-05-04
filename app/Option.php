<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $guarded = [];

    public static function addOption($key, $value = "")
    {
        return Option::create([
            "key" => $key,
            "value" => $value
        ]);
    }
}
