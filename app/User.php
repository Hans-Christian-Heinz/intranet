<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'accepted_rules_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return (bool) $this->is_admin;
    }

    public function acceptRules()
    {
        return $this->update([
            'accepted_rules_at' => Carbon::now()
        ]);
    }

    public function hasAcceptedRules()
    {
        return $this->accepted_rules_at >= Option::firstWhere('key', 'rules')->updated_at;
    }

    public function berichtshefte()
    {
        return $this->hasMany(Berichtsheft::class);
    }

    public function exemptions()
    {
        return $this->hasMany(Exemption::class);
    }
}
