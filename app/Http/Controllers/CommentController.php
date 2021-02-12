<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Documentation;
use App\Http\Requests\AddCommentRequest;
use App\Project;
use App\Proposal;
use App\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
     * Füge einem Kommentar einen Unterkommentar hinzu.
     *
     * @param Request $request
     * @param Comment $comment
     */
    public function answer(Request $request, Comment $comment) {
        $request->validate([
            'text' => 'required|string|max:255',
        ]);
        $answer = new Comment([
            "text" => $request->text,
            "section_name" => "none",
        ]);
        $answer->user()->associate(app()->user);
        // return $comment->answers()->save($answer);
        if($comment->answers()->save($answer)) {
            return [
                'html' => view('abschlussprojekt.sections.kommentarHelp', ["comment" => $answer])->render(),
                'id' => $answer->id,
            ];
        }
        else {
            return false;
        }
    }

    public function showDeleted_Proposal(Project $project, Proposal $proposal, Section $section) {
        $comments = $proposal->getCommentsWithDeleted($section->name);

        return view('abschlussprojekt.sections.kommentar', compact('section', 'comments', 'proposal'))->render();
    }

    public function showDeleted_Documentation(Project $project, Documentation $documentation, Section $section) {
        $comments = $documentation->getCommentsWithDeleted($section->name);

        return view('abschlussprojekt.sections.kommentar', compact('section', 'comments', 'documentation'))->render();
    }

    /**
     * @param Comment $comment
     * @return mixed
     */
    public function delete(Comment $comment) {
        $this->authorize('delete', $comment);
        return $comment->delete();
    }

    public function forceDelete($id) {
        if(Gate::allows("update-deleted-comment", $id)) {
            return Comment::where("id", $id)->forceDelete();
        }
        return false;
    }

    public function restore($id) {
        if(Gate::allows("update-deleted-comment", $id)) {
            if (Comment::where('id', $id)->restore()) {
                return [
                    'id' => $id,
                    'html' => view('abschlussprojekt.sections.kommentarHelp', ["comment" => Comment::find($id)])->render(),
                ];
            }
            else {
                return false;
            }
        }
        return false;
    }
}
