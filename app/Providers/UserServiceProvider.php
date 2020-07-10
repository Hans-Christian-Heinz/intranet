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
        $this->app->singleton('user', function ($app) {

            $username = $app->auth->user()->username;
            $fullName = $app->auth->user()->name;
            $email = $app->auth->user()->email;
            //sso guard: isAdmin wird lieg bereits vor; manuelles Einloggen (web guard): isAdmin liegt nicht vor
            if (isset($app->auth->user()->isAdmin)) {
                $isAdmin = $app->auth->user()->isAdmin;
            }
            else {
                $isAdmin = false;
                $groups = Adldap::search()->findByDn(sprintf(env('LDAP_USER_FULL_DN_FMT'), $username))->getMemberOf();
                foreach ($groups as $group) {
                    if (strpos($group, 'cn=admins') === 0) {
                        $isAdmin = true;
                        break;
                    }
                }
            }

            $user = User::firstWhere('ldap_username', $username);

            if (!$user) {
                $user = new User();
                $user->ldap_username = $username;
                $user->full_name = $fullName;
                $user->email = $email;

                $user->is_admin = $isAdmin;

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

                if ($user->is_admin != $isAdmin) {
                    $user->is_admin = $isAdmin;
                    $user->save();
                }
            }

            return $user;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
