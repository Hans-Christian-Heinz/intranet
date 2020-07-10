<?php

namespace App\Providers;

use Adldap\Laravel\Facades\Adldap;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\User;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('sso', function ($request) {
            $username = $request->server(env('SERVER_USERNAME_FIELD', 'REMOTE_USER'));
            $username = substr($username, 0, strpos($username, env('KERBEROS_DOMAIN', '')));
            if ($username) {
                $adldap_user = Adldap::search()->findByDn(sprintf(env('LDAP_USER_FULL_DN_FMT'), $username));
                $isAdmin = false;
                foreach ($adldap_user->getMemberOf() as $group) {
                    if (strpos($group, 'cn=admins') === 0) {
                        $isAdmin = true;
                        break;
                    }
                }

                //Benutze den LdapUser (Paket adminless_ldap), da dieser an manchen Stellen von Nicolai Savolyi benutzt und vorausgesetzt wurde.
                return new LdapUser([
                    'username' => $adldap_user->getFirstAttribute(env('LDAP_USER_SEARCH_ATTRIBUTE', 'uid')),
                    'name' => $adldap_user->getCommonName(),
                    'email' => $adldap_user->getEmail(),
                    'isAdmin' => $isAdmin,
                ]);
            }
            else {
                return null;
            }
        });
    }
}
