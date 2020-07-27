<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Section;
use Illuminate\Auth\Access\Response;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class SectionPolicy
{
    use HandlesAuthorization;

    public function create(LdapUser $ldap_user, Section $section) {
        $user = app()->user;
        return $user->isAdmin() || $user->is($section->getUser());
    }

    public function delete(LdapUser $ldap_user, Section $section) {
        $user = app()->user;
        $documentation = $section->documentation;
        if (is_null($documentation)) {
            $lockedBy = null;
        }
        else {
            $lockedBy = $documentation->lockedBy;
        }
        return ($user->isAdmin() || $user->is($section->getUser())) && $user->is($lockedBy)
            ? Response::allow()
            : Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie es bearbeiten.');
    }

    public function edit(LdapUser $ldap_user, Section $section) {
        $user = app()->user;
        return $user->isAdmin() || $user->is($section->getUser());
    }
}
