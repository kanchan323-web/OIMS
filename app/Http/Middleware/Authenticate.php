<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            return route('user.login'); 
        }
        return null;
    }

    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (!Auth::check() || Auth::user()->user_type !== 'user' && Auth::user()->user_status !== 0) {
            Auth::logout();
            return redirect()->route('user.login')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
