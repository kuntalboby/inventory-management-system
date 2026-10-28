<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class validAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next, ...$roles)
    {
      // dd(Auth::user());
            $user = Auth::user();

            if ($user && $user->status == 1 && in_array($user->role, $roles)) {
                return $next($request);
            }if(in_array('admin', $roles)) {
                    return redirect()->route('adminlogin');
                } elseif (in_array('manager', $roles)) {
                    return redirect()->route('managerlogin');
                }
        
    }
}
