<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Documentation;
use App\Http\Requests\AddCommentRequest;
use App\Project;
use App\Proposal;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function addToProposal(AddCommentRequest $request, Project $project, Proposal $proposal) {
        $comment = new Comment($request->all());
        $comment->user()->associate(app()->user);
        if($proposal->comments()->save($comment)) {
            return view('abschlussprojekt.sections.kommentarHelp', compact('comment'))->render();
        }
        else {
            return false;
        }
    }

    public function addToDocumentation(AddCommentRequest $request, Project $project, Documentation $documentation) {
        $comment = new Comment($request->all());
        $comment->user()->associate(app()->user);
        if($documentation->comments()->save($comment)) {
            return view('abschlussprojekt.sections.kommentarHelp', compact('comment'))->render();
        }
        else {
            return false;
        }
    }

    public function delete(Comment $comment) {
        $this->authorize('delete', $comment);
        return $comment->delete();
    }
}
