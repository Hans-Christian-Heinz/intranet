<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TplVersion extends Model
{
    protected $table = 'tpl_versions';
    protected $with = [
        'applicationTpls',
        'tplVariables',
    ];

    public function applicationTpls() {
        return $this->hasMany(ApplicationTemplate::class, 'version');
    }

    public function tplVariables() {
        return $this->hasMany(TplVariable::class, 'version');
    }

    public function applications() {
        return $this->hasMany(Application::class, 'tpl_version');
    }
}
