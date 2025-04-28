<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserSessionTimeout
{
    public function handle($request, Closure $next)
    {
        // Check for regular user (using default guard)
        if (!Auth::check()) {
            return $next($request);
        }
        $sessionLifetime = 1800; // 30 min session 
        // $sessionLifetime = 30; // 30 sec session 
        $lastActivity = $request->session()->get('last_activity', time());

        if (time() - $lastActivity > $sessionLifetime) {
            Auth::logout();
            $request->session()->invalidate();
            
            return redirect()->route('user.login')
                ->with('error', 'Your session expired after 1 minute of inactivity.');
        }

        // Update last activity time
        $request->session()->put('last_activity', time());
        
        return $next($request);
    }
}