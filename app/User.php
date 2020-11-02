<?php

namespace App;

use App\Structs\Adresse;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

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
        'ausbildungsende'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'accepted_rules_at' => 'datetime',
    ];

    /**
     * Gebe alle Bilder aus dem Ordner storage/app/public/images/username zurück.
     *
     * @return array
     */
    public function getImageFiles() {
        $dir_path = 'images/' . $this->ldap_username;
        if(! Storage::disk('public')->exists($dir_path)) {
            $image_files = [];
            Storage::disk('public')->makeDirectory($dir_path);
        }
        else {
            $image_files = Storage::disk('public')->files($dir_path);
        }

        return $image_files;
    }

    /**
     * Gebe alle Dokumente aus dem Ordner storage/app/public/documents/username zurück.
     * Methode wird im Moment nicht verwendet, da das Einbinden fremder Dokumente in eine Abschlussdokumentation im Moment
     * nicht implementiert ist, und somit keine Dokumente hochgeladen und verwendet werden.
     *
     * @return array
     */
    public function getUploadedDocuments() {
        $dir_path = 'documents/' . $this->ldap_username;
        if(! Storage::disk('public')->exists($dir_path)) {
            $documents = [];
            Storage::disk('public')->makeDirectory($dir_path);
        }
        else {
            $documents = Storage::disk('public')->files($dir_path);
        }

        return $documents;
    }

    public function isAdmin()
    {
        return $this->fachrichtung == 'Ausbilder';
    }

    public function getIsAdminAttribute() {
        return $this->fachrichtung == 'Ausbilder';
    }

    public function getAusbildungsbeginnAttribute() {
        return $this->berichtshefte()->min('week');
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

    public function resume()
    {
        return $this->hasOne(Resume::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
