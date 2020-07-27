<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Proposal;
use Illuminate\Auth\Access\Response;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class ProposalPolicy
{
    use HandlesAuthorization;

    public function store(LdapUser $ldapUser, Proposal $proposal)
    {
        $user = app()->user;
        $res = $user->is($proposal->lockedBy);
        return ($user->is($proposal->project->user) || $user->is_admin) && $res
            ? Response::allow()
            : Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie es bearbeiten.');
    }

    public function history(LdapUser $ldapUser, Proposal $proposal)
    {
        $user = app()->user;
        return $user->is($proposal->project->user) || $user->is_admin;
    }

    public function lock(LdapUser $ldapUser, Proposal $proposal)
    {
        $user = app()->user;
        return $user->is($proposal->project->user) || $user->is_admin;
    }

    public function pdf(LdapUser $ldapUser, Proposal $proposal)
    {
        $user = app()->user;
        return $user->is($proposal->project->user) || $user->is_admin;
    }
}
