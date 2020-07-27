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
        $proposal->comments()->save($comment);

        return redirect()->back()->with('status', 'Der Kommentar wurde erfolgreich gespeichert.');
    }

    public function addToDocumentation(AddCommentRequest $request, Project $project, Documentation $documentation) {
        $comment = new Comment($request->all());
        $comment->user()->associate(app()->user);
        $documentation->comments()->save($comment);

        return redirect()->back()->with('status', 'Der Kommentar wurde erfolgreich gespeichert.');
    }

    public function delete(Comment $comment) {
        $this->authorize('delete', $comment);
        $comment->delete();

        return redirect()->back()->with('status', 'Der Kommentar wurde erfolgreich gelöscht.');
    }
}
