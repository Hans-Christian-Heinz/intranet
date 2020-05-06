<?php

namespace App\Providers;

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

            $user = User::firstWhere('ldap_username', $username);

            if (!$user) {
                $user = new User();
                $user->ldap_username = $username;
                $user->save();
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
