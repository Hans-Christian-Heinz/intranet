<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Documentation;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class DocumentationPolicy
{
    use HandlesAuthorization;

    public function store(LdapUser $ldapUser, Documentation $documentation)
    {
        $user = app()->user;
        $res = app()->user->is($documentation->lockedBy);
        return ($user->is($documentation->project->user) || $user->is_admin) && $res;
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
