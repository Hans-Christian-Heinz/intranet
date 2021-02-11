<?php

namespace App\Policies;

use App\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;
use JotaEleSalinas\AdminlessLdap\LdapUser;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    use HandlesAuthorization;

    public function delete(LdapUser $ldapUser, Comment $comment)
    {
        $user = app()->user;
        return $user->is($comment->user)
            ? Response::allow()
            : Response::deny('Sie können nur Kommentare löschen, die Sie selbst verfasst haben.');
    }

    public function acknowledge(LdapUser $ldapUser, Comment $comment)
    {
        $user = app()->user;
        return $user->is($comment->getDocument()->project->user)
            ? Response::allow()
            : Response::deny("Sie können nur Kommentare abhaken, die zu einem Dokument gehören, das Ihnen gehört.");
    }
}
