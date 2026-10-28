<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'customer') {
            session()->flash('response','danger');
            return redirect()->route('page','')
                ->with('message', 'Please login as user.');
        }
       
    //     if ($request->route()->getName() === 'page') {
    //     return redirect()->route('users.dashboard');
    // }

        return $next($request);
    }
}
