<?php

namespace App\Http\Middleware;

use Closure;

class FlashError
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
        if (session()->has('errors')) {
            session()->flash('danger', 'Das Speichern schlug aufgrund eines Validierungsfehlers fehl!');
        }

        return $next($request);
    }
}
