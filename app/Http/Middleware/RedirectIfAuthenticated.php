<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
    public function handle($request, Closure $next, $guard = null)
    {
        // if (Auth::guard($guard)->check()) {
        //     return redirect('/request/kp');
        // }

        // return $next($request);
        switch ($guard) {
            case 'student':
                if (Auth::guard($guard)->check()) {
                    return redirect()->route('student.request.kp');
                }
                break;
            case 'prodis':
                if(Auth::guard($guard)->check()) {
                    return redirect()->route('prodi.request.kp');
                }
                break;
            case 'lib':
                if(Auth::guard($guard)->check()) {
                    return redirect()->route('hardcover_kp');
                }
                break;
            case 'finance':
                if(Auth::guard($guard)->check()) {
                    return redirect()->route('finance.request.skripsi');
                }
                break;
            default:
                if (Auth::guard($guard)->check()) {
                    $user = \Auth::user();
                    $role = $user->roles()->first();

                    if ($role->code == "MTRUSR") {
                        return redirect()->route('meteor.skripsi.students');
                    }
                    return redirect()->route('request.kp');
                }
                break;
        }
        return $next($request);
    }
}
