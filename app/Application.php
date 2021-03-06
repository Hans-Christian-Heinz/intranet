<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ["user_id", "company_id", "body", "tpl_version",];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tplVersion() {
        return $this->belongsTo(TplVersion::class, 'tpl_version');
    }
}
