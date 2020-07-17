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
        return $user->is($proposal->project->user) || $user->is_admin;
    }

    public function history(LdapUser $ldapUser, Proposal $proposal)
    {
        $user = app()->user;
        return $user->is($proposal->project->user) || $user->is_admin;
    }
}
