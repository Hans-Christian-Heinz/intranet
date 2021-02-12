<?php


namespace App\Traits;


trait HasComments
{
    public function getCommentsWithDeleted($section_name) {
        $comments = $this->comments()->without("answers")->withTrashed()->where("section_name", $section_name)->get();
        foreach($comments as $c) {
            $c->loadAnswersWithTrashed();
        }

        return $comments;
    }
}
