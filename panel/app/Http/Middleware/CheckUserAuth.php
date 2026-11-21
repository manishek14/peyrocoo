<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class CheckUserAuth
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function handle(Request $request, Closure $next)
    {

        if (!Auth::guard('web')->check()) {
            return redirect()->route('auth.app.login.form');
        }
        return $next($request);
    }
}
