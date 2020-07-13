<?php

namespace App\Http\Middleware;

use Closure;

class ChangeAuthGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //nach Ausloggen soll manuelles Einloggen möglich und nötig sein
        auth()->setDefaultDriver('web');
        session()->put('auth_guard', 'web');

        return $next($request);
    }
}
