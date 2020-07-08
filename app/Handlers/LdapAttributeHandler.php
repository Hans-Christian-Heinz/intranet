<?php


namespace App\Handlers;

use App\User as EloquentUser;
use Adldap\Models\User as LdapUser;

/**
 * Class LdapAttributeHandler
 * @package App\Handlers
 *
 * Wird benutzt, um beim Anmelden über den LDAP-Server Attribute des Ldap-Eintrags auf Felder des Eloquent-Models zu kopieren.
 * Klasse wird zur Zeit nicht benutzt, da sie sich nicht mit dieser Applikation verträgt.
 */
class LdapAttributeHandler
{
    /**
     * Synchronizes ldap attributes to the specified model.
     *
     * @param LdapUser     $ldapUser
     * @param EloquentUser $eloquentUser
     *
     * @return void
     */
    public function handle(LdapUser $ldapUser, EloquentUser $eloquentUser)
    {
        $username = env('AUTH_USER_KEY_FIELD', 'username');
        $eloquentUser->$username = $ldapUser->getFirstAttribute(env('LDAP_USER_SEARCH_ATTRIBUTE', 'uid'));
        //$eloquentUser->username = $ldapUser->getAttribute(env('LDAP_USER_SEARCH_ATTRIBUTE', 'uid'));
        $eloquentUser->ldap_username = $ldapUser->getFirstAttribute(env('LDAP_USER_SEARCH_ATTRIBUTE', 'uid'));
        $eloquentUser->name = $ldapUser->getCommonName();
        //$eloquentUser->email = $ldapUser->getAttribute('mail');
        $eloquentUser->email = $ldapUser->getEmail();
        //$eloquentUser->groups = json_encode($ldapUser->getMemberOf());
    }
}
