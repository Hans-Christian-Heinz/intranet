<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TplVariable extends Model
{
    public $timestamps = false;

    protected $table = 'tpl_variables';
    protected $fillable = [
        'name',
        'standard',
    ];

    public function tplVersion() {
        return $this->belongsTo(TplVersion::class, 'version');
    }
}
