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
        return $user->is_admin || $user->is($berichtsheft->owner);
    }

    public function update(LdapUser $ldapUser, Berichtsheft $berichtsheft)
    {
        $user = app()->user;
        return $user->is_admin || $user->is($berichtsheft->owner);
    }

    public function edit(LdapUser $ldapUser, Berichtsheft $berichtsheft)
    {
        $user = app()->user;
        return $user->is_admin || $user->is($berichtsheft->owner);
    }

    public function destroy(LdapUser $ldapUser, Berichtsheft $berichtsheft)
    {
        $user = app()->user;
        return $user->is_admin || $user->is($berichtsheft->owner);
    }
}
