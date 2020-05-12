<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exemption extends Model
{
    protected $fillable = [
        'start',
        'end',
        'reason',
        'status',
        'admin_id',
    ];

    protected $dates = ['start', 'end'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
