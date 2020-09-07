<?php

namespace App\Policies;

use App\Image;
use JotaEleSalinas\AdminlessLdap\LdapUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
{
    use HandlesAuthorization;

    public function vorschau(LdapUser $ldapUser, Image $image)
    {
        $user = app()->user;
        $start = strpos($image->path,'/') + 1;
        $end = strpos($image->path, '/', $start);
        return $user->ldap_username ==  substr($image->path, $start, $end - $start);
    }
}
