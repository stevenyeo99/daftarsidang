<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MustFillAttachment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $student = Auth::guard('student')->user();

        if ($student->must_fill_attachment && $student->profile_filled) {
            return redirect()->route('student.attachment');
        }

        return $next($request);
    }
}
