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
        return $user->is($comment->user) || $user->is($comment->getDocument()->project->user)
            ? Response::allow()
            : Response::deny('Sie können nur Kommentare löschen, die Sie entweder verfasst haben oder die zu einem Dokument gehören, das Ihnen gehört.');
    }
}
