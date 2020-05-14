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
            $fullName = $app->auth->user()->name;
            $email = $app->auth->user()->email;

            $user = User::firstWhere('ldap_username', $username);

            if (!$user) {
                $user = new User();
                $user->ldap_username = $username;
                $user->full_name = $fullName;
                $user->email = $email;

                $isAdmin = User::count() < env('LDAP_ADMIN_THRESHOLD', 1);
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
