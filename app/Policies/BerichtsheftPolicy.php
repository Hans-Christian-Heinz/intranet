<?php

namespace App\Policies;

use App\Berichtsheft;
use JotaEleSalinas\AdminlessLdap\LdapUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class BerichtsheftPolicy
{
    use HandlesAuthorization;

    public function show(LdapUser $ldapUser, Berichtsheft $berichtsheft)
    {
        $user = app()->user;
        return $user->is($berichtsheft->owner) || $user->isAdmin;
    }

    public function update(LdapUser $ldapUser, Berichtsheft $berichtsheft)
    {
        $user = app()->user;
        return $user->is($berichtsheft->owner) || $user->isAdmin;
    }

    public function edit(LdapUser $ldapUser, Berichtsheft $berichtsheft)
    {
        $user = app()->user;
        return $user->is($berichtsheft->owner) || $user->isAdmin;
    }

    public function destroy(LdapUser $ldapUser, Berichtsheft $berichtsheft)
    {
        $user = app()->user;
        return $user->is($berichtsheft->owner) || $user->isAdmin;
    }
}
