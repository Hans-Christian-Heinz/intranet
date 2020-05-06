<?php

namespace App\Policies;

use App\Exemption;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExemptionPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Exemption $exemption)
    {
        return $user->is($exemption->owner);
    }

    public function update(User $user, Exemption $exemption)
    {
        return $user->is($exemption->owner);
    }

    public function edit(User $user, Exemption $exemption)
    {
        return $user->is($exemption->owner);
    }

    public function destroy(User $user, Exemption $exemption)
    {
        return $user->is($exemption->owner);
    }
}
