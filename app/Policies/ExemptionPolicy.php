<?php

namespace App\Policies;

use App\Exemption;
use JotaEleSalinas\AdminlessLdap\LdapUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExemptionPolicy
{
    use HandlesAuthorization;

    public function show(LdapUser $ldapUser, Exemption $exemption)
    {
        $user = app()->user;
        return $user->is_admin || $user->is($exemption->owner);
    }

    public function update(LdapUser $ldapUser, Exemption $exemption)
    {
        $user = app()->user;
        return $user->is_admin || ($exemption->status === 'new' && $user->is($exemption->owner));
    }

    public function edit(LdapUser $ldapUser, Exemption $exemption)
    {
        $user = app()->user;
        return $user->is_admin || ($exemption->status === 'new' && $user->is($exemption->owner));
    }

    public function destroy(LdapUser $ldapUser, Exemption $exemption)
    {
        $user = app()->user;
        return $user->is_admin || ($exemption->status === 'new' && $user->is($exemption->owner));
    }
}
