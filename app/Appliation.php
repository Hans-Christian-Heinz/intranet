<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appliation extends Model
{
    protected $fillable = ["user_id", "company_id", "body"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
