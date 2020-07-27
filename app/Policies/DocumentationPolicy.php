<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Documentation;
use Illuminate\Auth\Access\Response;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class DocumentationPolicy
{
    use HandlesAuthorization;

    public function store(LdapUser $ldapUser, Documentation $documentation)
    {
        $user = app()->user;
        $res = $user->is($documentation->lockedBy);
        return ($user->is($documentation->project->user) || $user->is_admin) && $res
            ? Response::allow()
            : Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie es bearbeiten.');
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

    public function addImage(LdapUser $ldapUser, Documentation $documentation) {
        $user = app()->user;
        return $user->is($documentation->project->user) || $user->is_admin;
    }
}
