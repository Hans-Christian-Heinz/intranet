<?php

namespace App\Providers;

use Adldap\Laravel\Facades\Adldap;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\AuthorizationException;
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

        //Gate to authorize deleting users
        //erster Parameter der closure ist der Benutzer, der bei der Authentifizierung herauskommt; er wird aber nicht verwendet
        Gate::define('delete-user', function(LdapUser $user, User $toDelete) {
            if (! app()->user->is_admin) {
                return Response::deny('Nur Ausbilder dürfen Benutzerprofile löschen.');
            }
            return ($toDelete->is(app()->user))
                ? Response::deny('Sie dürfen nicht Ihr eigenes Benutezrprofil löschen.')
                : Response::allow();
        });

        //custom auth_guard for single sign on
        Auth::viaRequest('sso', function ($request) {
            $username = $request->server(env('SERVER_USERNAME_FIELD', 'REMOTE_USER'));

            if (session()->get('auth_guard', 'sso') === 'sso' && $username) {

                $username = substr($username, 0, strpos($username, env('KERBEROS_DOMAIN', '')));
                $adldap_user = Adldap::search()->findByDn(sprintf(env('LDAP_USER_FULL_DN_FMT'), $username));
                $fachrichtung = 'Anwendungsentwicklung';
                foreach ($adldap_user->getMemberOf() as $group) {
                    if (strpos($group, 'cn=admins') === 0) {
                        $fachrichtung = 'Ausbilder';
                        break;
                    }
                    if (strpos($group, 'cn=systemintegration') === 0) {
                        $fachrichtung = 'Systemintegration';
                    }
                }

                //Benutze den LdapUser (Paket adminless_ldap), da dieser an manchen Stellen von Nicolai Savolyi benutzt und vorausgesetzt wurde.
                return new LdapUser([
                    'username' => $adldap_user->getFirstAttribute(env('LDAP_USER_SEARCH_ATTRIBUTE', 'uid')),
                    'name' => $adldap_user->getCommonName(),
                    'email' => $adldap_user->getEmail(),
                    'fachrichtung' => $fachrichtung,
                ]);
            }
            else {
                //Wenn die Kerberos-Anmeldung nicht funktioniert oder sonst manuelle Anmeldung erwünscht ist, verwende
                //auth-guard web.
                return Auth::guard('web')->user();
            }
        });
    }
}
