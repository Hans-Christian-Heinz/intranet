<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeywordTemplate extends Model
{
    public $timestamps = false;
    
    protected $table = 'keyword_tpls';
    
    protected $casts = [
        'tpls' => 'array',
    ];
    protected $fillable = [
        'number',
        'heading',
        'conjunction',
        'tpls',
    ];
}
