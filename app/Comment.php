<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $with = ['user', 'answers'];
    protected $fillable = [
        'text',
        'section_name',
        'acknowledged',
    ];

    public function getCommentAttribute() {
        return $this->user->full_name . ': ' . $this->text;
    }

    public function getDocument() {
        if ($this->parentComment) {
            return $this->parentComment->getDocument();
        }
        if ($this->proposal) {
            return $this->proposal;
        }
        else {
            return $this->documentation;
        }
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function proposal() {
        return $this->belongsTo(Proposal::class);
    }

    public function documentation() {
        return $this->belongsTo(Documentation::class);
    }

    public function parentComment() {
        return $this->belongsTo(Comment::class, 'comment_id', 'id');
    }

    public function answers() {
        return $this->hasMany(Comment::class);
    }
}
