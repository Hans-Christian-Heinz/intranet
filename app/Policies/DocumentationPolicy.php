<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Documentation;
use Illuminate\Auth\Access\Response;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class DocumentationPolicy
{
    use HandlesAuthorization;

    public function index(LdapUser $ldapUser, Documentation $documentation) {
        $user = app()->user;
        return $user->is($documentation->project->user) || $user->is_admin
            ? Response::allow()
            : Response::deny('Sofern Sie kein Ausbilder sind, dürfen Sie nur Ihre eigenen Dokumente ansehen.');
    }

    public function store(LdapUser $ldapUser, Documentation $documentation)
    {
        $user = app()->user;
        if (! $user->is($documentation->lockedBy)) {
            return Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie es bearbeiten können.');
        }
        return $user->is($documentation->project->user) || $user->is_admin
            ? Response::allow()
            : Response::deny('Sofern Sie kein Ausbilder sind, dürfen Sie nur Ihre eigenen Dokumente bearbeiten.');
    }

    public function history(LdapUser $ldapUser, Documentation $documentation)
    {
        $user = app()->user;
        return $user->is($documentation->project->user) || $user->is_admin;
    }

    public function lock(LdapUser $ldapUser, Documentation $documentation)
    {
        $user = app()->user;
        return $user->is($documentation->project->user) || $user->is_admin;
    }

    public function pdf(LdapUser $ldapUser, Documentation $documentation)
    {
        $user = app()->user;
        return $user->is($documentation->project->user) || $user->is_admin;
    }
}
