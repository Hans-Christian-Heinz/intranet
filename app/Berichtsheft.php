<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Berichtsheft extends Model
{
    protected $table = "berichtshefte";
    protected $dates = ["week"];
    protected $fillable = ["work_activities", "instructions", "school", "grade", "week"];

    public function owner()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
