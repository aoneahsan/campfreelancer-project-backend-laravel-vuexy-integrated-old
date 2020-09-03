<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    // public function handle($request, $next, ...$guards)
    // {
    //     // dd("ok");
    //     $this->authenticate($request, $guards);
    //     // dd($request->expectsJson(), $guards);
    //     if (session("isVerified")) {
    //         return $next($request);
    //     }
    //     return \redirect('verify');
    // }
    // /**
    //  * Get the path the user should be redirected to when they are not authenticated.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return string
    //  */
    // protected function redirectTo($request)
    // {
    //     if (!$request->expectsJson()) {
    //         return route('login');
    //     }
    // }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
