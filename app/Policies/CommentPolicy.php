<?php

namespace App\Policies;

use App\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class CommentPolicy
{
    use HandlesAuthorization;

    public function delete(LdapUser $ldapUser, Comment $comment)
    {
        $user = app()->user;
        return $user->is($comment->user) || $user->is($comment->getDocument()->project->user);
    }
}
