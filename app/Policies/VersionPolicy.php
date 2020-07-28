<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Version;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class VersionPolicy
{
    use HandlesAuthorization;

    public function use(LdapUser $user, Version $version)
    {
        $user = app()->user;
        $document = $version->getDocument();
        return (!is_null($document)) && !$document->vc_locked && ($user->is($document->project->user) || $user->is_admin);
    }

    public function delete(LdapUser $user, Version $version)
    {
        $user = app()->user;
        $document = $version->getDocument();
        return (!is_null($document)) && !($document->vc_locked && $version->is($document->latestVersion())) && ($user->is($document->project->user) || $user->is_admin);
    }

    public function clearHistory(LdapUser $user, Version $version)
    {
        $user = app()->user;
        $document = $version->getDocument();
        return (!is_null($document)) && ($user->is($document->project->user) || $user->is_admin);
    }

    public function vergleich(LdapUser $user, Version $version)
    {
        $user = app()->user;
        $document = $version->getDocument();
        return (!is_null($document)) && ($user->is($document->project->user) || $user->is_admin);
    }
}
