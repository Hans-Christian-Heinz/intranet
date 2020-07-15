<?php

namespace App;

use App\Traits\HasSections;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    // Die Abschnitte eines Projektantrags
    const SECTIONS = [
        ['name' => 'topic', 'heading' => 'Thema', 'sequence' => 0,],
        ['name' => 'deadline', 'heading' => 'Termin', 'sequence' => 1, 'tpl' => 'antrag.deadline_section',],
        ['name' => 'description', 'heading' => 'Beschreibung', 'sequence' => 2,],
        ['name' => 'environment', 'heading' => 'Umfeld', 'sequence' => 3,],
        ['name' => 'phases', 'heading' => 'Phasen', 'sequence' => 4, 'tpl' => 'antrag.phases_parent_section', 'sections' => self::PHASES,],
        ['name' => 'documentation', 'heading' => 'Dokumentation', 'sequence' => 5,],
        ['name' => 'attachments', 'heading' => 'Anlagen', 'sequence' => 6,],
        ['name' => 'presantation', 'heading' => 'Präsentationsmittel', 'sequence' => 7,],
    ];

    const PHASES = [
        ['name' => 'planung', 'heading' => 'Planung und Analyse', 'sequence' => 0, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'entwurf', 'heading' => 'Entwurf', 'sequence' => 0, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'implementierung', 'heading' => 'Implementierung', 'sequence' => 0, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'test', 'heading' => 'Test', 'sequence' => 0, 'tpl' => 'antrag.phases_text_section',],
        ['name' => 'abnahme', 'heading' => 'Abnahme und Dokumentation', 'sequence' => 0, 'tpl' => 'antrag.phases_text_section',],
    ];

    use HasSections;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['sections'];

    /**
     * create the standard sections of a document (proposal or documentation)
     */
    /*public function makeSections() {
        foreach (self::SECTIONS as $sect) {
            $s = new Section($sect);
            $this->sections()->save($s);
            if ($s->sections) {
                $s->makeSections(array_key_exists('sections', $sect) ? $sect['sections'] : []);
            }
        }
    }todo*/

    public function getStartAttribute() {
        return $this->project->start;
    }

    public function getEndAttribute() {
        return $this->project->end;
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
