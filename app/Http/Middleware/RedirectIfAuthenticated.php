<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    // public function handle($request, Closure $next, $guard = null)
    // {
    //     // dd(Auth::user()->toArray());
    //     if (Auth::guard($guard)->check()) {
    //         if (\session('isVerified')) {
    //             return redirect('/home');
    //         }
    //         return redirect('/verify');
    //     }
    //     return $next($request);
    // }

    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            // if (FacadesRequest::path() == 'login/google' || 
            // FacadesRequest::path() == 'login/facebook' ||
            // FacadesRequest::path() == 'login/github') {
            //     dd("ok");
            // }

            $user_id = $request->user()->id;
            // dd($user_id);
            $token = $request->user()->getTokken();

            //    dd(env('APP_FRONTEND_URL').'sociallogin?user_id='.$user_id.'&token='.$token);
            return redirect()->to(env('APP_FRONTEND_URL').'sociallogin?user_id='.$user_id.'&token='.$token);
            // dd(FacadesRequest::path(), );
            // return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
