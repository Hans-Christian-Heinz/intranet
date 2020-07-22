<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $with = [
        'sections',
    ];

    public function delete() {
        //Überprüfe für alle dieser Version zugeordneten Abschnitte, ob sie einer anderen Version zugeordnet sind.
        //Wenn nicht: Lösche sie.
        foreach ($this->sections as $section) {
            if ($section->versions()->count() <= 1) {
                //Überprüfe für die Bilder eines Abschnitts, ob sie einem anderen Abschnitt zugeordnet sind.
                //Wenn nciht: Lösche sie.
                foreach ($section->images as $image) {
                    if ($image->sections()->count() <= 1) {
                        $image->delete();
                    }
                }
                $section->delete();
            }
        }

        return parent::delete();
    }

    public function proposal() {
        return $this->belongsTo(Proposal::class);
    }

    public function documentation() {
        return $this->belongsTo(Documentation::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function sections() {
        return $this->belongsToMany(Section::class, 'sections_versions')->withTimestamps();
    }
}
