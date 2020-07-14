<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Project;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function update(LdapUser $ldapUser, Project $project)
    {
        $user = app()->user;
        return $user->is($project->user) || $user->is_admin;
    }
}
