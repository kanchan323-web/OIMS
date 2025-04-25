<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckSessionTimeout
{
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

     
     public function handle($request, Closure $next)
     {
         if (!Auth::guard('admin')->check()) {
             return $next($request);
         }
 
         $sessionLifetime = 300; // 5 minute in seconds
         $lastActivity = $request->session()->get('last_activity', time());
         
         if (time() - $lastActivity > $sessionLifetime) {
             Auth::guard('admin')->logout();
             $request->session()->invalidate();
             return redirect()->route('admin.login')
                 ->with('error', 'Your session expired after 1 minute of inactivity.');
         }
 
         $request->session()->put('last_activity', time());
         return $next($request);
     }
}
