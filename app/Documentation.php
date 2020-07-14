<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shortTitle',
        'longTitle',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function changedBy() {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }
}
