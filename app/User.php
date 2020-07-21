<?php

namespace App;

use App\Structs\Adresse;
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
        'accepted_rules_at',
        'ort',
        'plz',
        'hausnr',
        'strasse',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'accepted_rules_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->fachrichtung == 'Ausbilder';
    }

    public function getIsAdminAttribute() {
        return $this->fachrichtung == 'Ausbilder';
    }

    public function getAddress() {
        return new Adresse($this->strasse, $this->hausnr, $this->plz, $this->ort);
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

    public function project()
    {
        return $this->hasOne(Project::class);
    }

    public function supervisedProjects()
    {
        return $this->hasMany(Project::class, 'supervisor_id', 'id');
    }
}
