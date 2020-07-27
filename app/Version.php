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
                //Überprüfe, ob ein Abschnitt des selben Namens in einer anderen Version des Dokuments existiert
                //Falls nicht: Lösche alle Kommentare des Dokuments, die zum Abschnitt mit diesem Namen gehören
                if (! is_null($this->getDocument())) {
                    $doc = $this->getDocument();
                    if ($doc->sections()->where('name', $section->name)->count() === 1) {
                        $doc->comments()->where('section_name', $section->name)->delete();
                    }
                }

                $section->delete();
            }
        }

        return parent::delete();
    }

    public function getDocument() {
        if (! is_null($this->proposal)) {
            return $this->proposal;
        }
        if (! is_null($this->documentation)) {
            return $this->documentation;
        }
        return null;
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
        return $this->belongsToMany(Section::class, 'sections_versions')
            ->withTimestamps()
            ->withPivot('sequence')
            ->orderBy('pivot_sequence');
    }
}
