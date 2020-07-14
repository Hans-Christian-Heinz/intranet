<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    // Die Abschnitte eines Projektantrags
    const SECTIONS = [
        ['name' => 'topic', 'heading' => 'Thema', 'sequence' => 0,],
        ['name' => 'deadline', 'heading' => 'Termin', 'sequence' => 1,],
        ['name' => 'description', 'heading' => 'Beschreibung', 'sequence' => 2,],
        ['name' => 'environment', 'heading' => 'Umfeld', 'sequence' => 3,],
        ['name' => 'phases', 'heading' => 'Phasen', 'sequence' => 4,],
        ['name' => 'documentation', 'heading' => 'Dokumentation', 'sequence' => 5,],
        ['name' => 'attachments', 'heading' => 'Anlagen', 'sequence' => 6,],
        ['name' => 'presantation', 'heading' => 'Präsentationsmittel', 'sequence' => 7,],
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['sections'];

    /**
     * create the standard sections of a project-proposal
     */
    public function makeSections() {
        $sections = [];
        foreach (self::SECTIONS as $sect) {
            array_push($sections, new Section($sect));
        }

        $this->sections()->saveMany($sections);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function changedBy() {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }

    public function sections() {
        return $this->hasMany(Section::class, 'proposal_id', 'id');
    }
}
