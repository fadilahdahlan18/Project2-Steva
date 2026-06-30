<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * If already authenticated, redirect to the appropriate dashboard based on role.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return match (Auth::user()->role) {
                    'admin'   => redirect()->route('admin.dashboard'),
                    'pelatih' => redirect()->route('pelatih.dashboard'),
                    'murid'   => redirect()->route('murid.dashboard'),
                    default   => redirect('/'),
                };
            }
        }

        return $next($request);
    }
}
