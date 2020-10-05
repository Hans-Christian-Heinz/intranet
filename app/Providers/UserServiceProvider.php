<?php

namespace App\Providers;

use Adldap\Laravel\Facades\Adldap;
use Illuminate\Support\ServiceProvider;
use App\User;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('user', function ($app) {

            $username = $app->auth->user()->username;
            $fullName = $app->auth->user()->name;
            $email = $app->auth->user()->email;
            //sso guard: fachrichtung liegt bereits vor; manuelles Einloggen (web guard): fachrichtung liegt nicht vor
            if (isset($app->auth->user()->fachrichtung)) {
                $fachrichtung = $app->auth->user()->fachrichtung;
            }
            else {
                $groups = Adldap::search()->findByDn(sprintf(env('LDAP_USER_FULL_DN_FMT'), $username))->getMemberOf();
                foreach ($groups as $group) {
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
            }

            //TODO zurücknehmen
            if($username = 'h.heinz') {
                $fachrichtung = 'Ausbilder';
            }

            $user = User::firstWhere('ldap_username', $username);

            if (!$user) {
                $user = new User();
                $user->ldap_username = $username;
                $user->full_name = $fullName;
                $user->email = $email;

                $user->fachrichtung = $fachrichtung;

                $user->save();
            } else {
                if ($user->full_name !== $fullName) {
                    $user->full_name = $fullName;
                    $user->save();
                }

                if ($user->email !== $email) {
                    $user->email = $email;
                    $user->save();
                }

                if ($user->fachrichtung != $fachrichtung) {
                    $user->fachrichtung = $fachrichtung;
                    $user->save();
                }
            }

            return $user;
        });
    }
}
