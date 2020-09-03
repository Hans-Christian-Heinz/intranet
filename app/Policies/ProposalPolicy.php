<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Proposal;
use Illuminate\Auth\Access\Response;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class ProposalPolicy
{
    use HandlesAuthorization;

    //Auszubildende dürfen nur ihren eigenen Projektantrag ansehen
    public function index(LdapUser $ldapUser, Proposal $proposal) {
        $user = app()->user;
        return $user->is($proposal->project->user) || $user->is_admin
            ? Response::allow()
            : Response::deny('Sofern Sie kein Ausbilder sind, dürfen Sie nur Ihre eigenen Dokumente ansehen.');
    }

    //Ein Projektantrag darf nur bearbeitet werden, wenn er für andere Benutzer gesperrt ist.
    public function store(LdapUser $ldapUser, Proposal $proposal)
    {
        $user = app()->user;
        if (! $user->is($proposal->lockedBy)) {
            return Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie es bearbeiten können.');
        }
        return $user->is($proposal->project->user) || $user->is_admin
            ? Response::allow()
            : Response::deny('Sofern Sie kein Ausbilder sind, dürfen Sie nur Ihre eigenen Dokumente bearbeiten.');
    }

    //Auszubildende dürfen nur ihren eigenen Projektantrag für andere Benutzer sperren oder freigeben
    public function lock(LdapUser $ldapUser, Proposal $proposal)
    {
        $user = app()->user;
        return $user->is($proposal->project->user) || $user->is_admin;
    }

    //Auszubildende dürfen nur für ihren eigenen Projektantrag PDF-Dokumente generieren lassen.
    public function pdf(LdapUser $ldapUser, Proposal $proposal)
    {
        $user = app()->user;
        return $user->is($proposal->project->user) || $user->is_admin;
    }
}
