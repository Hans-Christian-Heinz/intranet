<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Section;
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
        return $user->isAdmin() || $user->is($section->getUser());
    }

    public function edit(LdapUser $ldap_user, Section $section) {
        $user = app()->user;
        return $user->isAdmin() || $user->is($section->getUser());
    }
}
