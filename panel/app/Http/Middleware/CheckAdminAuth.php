<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminAuth
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function handle(Request $request, Closure $next)
    {

        if (!Auth::guard('admin')->check()) {
            return redirect()->route('auth.admin.login.form');
        }
        return $next($request);
    }
}
