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
                return abort(403, 'Nur Ausbilder dÃ¼rfen Benutzerprofile lÃ¶schen.');
            }
            return ($toDelete->is(app()->user))
                //? Response::deny('Sie dÃ¼rfen nicht Ihr eigenes Benutezrprofil lÃ¶schen.')
                ? abort(403, 'Sie dÃ¼rfen nicht Ihr eigenes Benutzerprofil lÃ¶schen.')
                : Response::allow();
        });

        //custom auth_guard for single sign on
        Auth::viaRequest('sso', function ($request) {
            //Es wÃ¤re wahrscheinlich sauberer, eine config-Datei anzulegen oder weitere Werte zur config/auth.php hinzuzufÃ¼gen,
            //anstatt in der Programmlogik unmittelbar auf env-Variablen zuzugreifen.
            /*$username = $request->server(env('SERVER_USERNAME_FIELD', 'REMOTE_USER'));

            if (session()->get('auth_guard', 'sso') === 'sso' && $username) {

                //Falls in der Server-Variable die DomÃ¤ne im Benutzernamen gespeichert ist, entferne diese:
                //(username@domain wird zu username)
                $length_help = strpos($username, env('KERBEROS_DOMAIN', ''));
                if ($length_help) {
                    $username = substr($username, 0, $length_help);
                }
                $adldap_user = Adldap::search()->findByDn(sprintf(env('LDAP_USER_FULL_DN_FMT'), $username));
                foreach ($adldap_user->getMemberOf() as $group) {
                    $fachrichtung = 'Anwendungsentwicklung';
                    if (strpos($group, 'cn=admins') === 0) {
                        $fachrichtung = 'Ausbilder';
                        break;
                    }
                    if (strpos($group, 'cn=systemintegration') === 0) {
                        $fachrichtung = 'Systemintegration';
                    }
                    if (strpos($group, 'cn=anwendungsentwicklung') === 0) {
                        $fachrichtung = 'Anwendungsentwicklung';
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
                //Wenn die Kerberos-Anmeldung nicht funktioniert oder sonst manuelle Anmeldung erwÃ¼nscht ist, verwende
                //auth-guard web.
                return Auth::guard('web')->user();
            }*/
            
            //TODO zurücknehmen
            return new LdapUser([
                'username' => 'm.mustermann',
                'name' => 'Max Mustermann',
                'email' => 'm.mustermann@mail.de',
                'fachrichtung' => 'Ausbilder',
            ]);
        });
    }
}
