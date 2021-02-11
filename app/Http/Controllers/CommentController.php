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
    /**
     * @param AddCommentRequest $request
     * @param Project $project
     * @param Proposal $proposal
     * @return array|false
     */
    public function addToProposal(AddCommentRequest $request, Project $project, Proposal $proposal) {
        $comment = new Comment($request->all());
        $comment->user()->associate(app()->user);
        if($proposal->comments()->save($comment)) {
            return [
                'html' => view('abschlussprojekt.sections.kommentarHelp', compact('comment'))->render(),
                'id' => $comment->id,
            ];
        }
        else {
            return false;
        }
    }

    /**
     * @param AddCommentRequest $request
     * @param Project $project
     * @param Documentation $documentation
     * @return array|false
     */
    public function addToDocumentation(AddCommentRequest $request, Project $project, Documentation $documentation) {
        $comment = new Comment($request->all());
        $comment->user()->associate(app()->user);
        if($documentation->comments()->save($comment)) {
            return [
                'html' => view('abschlussprojekt.sections.kommentarHelp', compact('comment'))->render(),
                'id' => $comment->id,
            ];
        }
        else {
            return false;
        }
    }

    public function acknowledge(Request $request, Comment $comment) {
        $request->validate([
            'acknowledge' => 'required|boolean',
        ]);

        return $comment->update(["acknowledged" => $request->acknowledge]);
    }

    /**
     * @param Comment $comment
     * @return mixed
     */
    public function delete(Comment $comment) {
        $this->authorize('delete', $comment);
        return $comment->delete();
    }
}
