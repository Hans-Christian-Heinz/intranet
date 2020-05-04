<?php

namespace App\Policies;

use App\Berichtsheft;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BerichtsheftPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Berichtsheft $berichtsheft)
    {
        return $user->is($berichtsheft->owner);
    }

    public function update(User $user, Berichtsheft $berichtsheft)
    {
        return $user->is($berichtsheft->owner);
    }

    public function edit(User $user, Berichtsheft $berichtsheft)
    {
        return $user->is($berichtsheft->owner);
    }

    public function destroy(User $user, Berichtsheft $berichtsheft)
    {
        return $user->is($berichtsheft->owner);
    }
}
