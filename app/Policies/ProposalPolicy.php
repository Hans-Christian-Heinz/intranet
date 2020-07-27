<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Proposal;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class ProposalPolicy
{
    use HandlesAuthorization;

    public function store(LdapUser $ldapUser, Proposal $proposal)
    {
        $user = app()->user;
        $res = app()->user->is($proposal->lockedBy);
        return ($user->is($proposal->project->user) || $user->is_admin) && $res;
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
