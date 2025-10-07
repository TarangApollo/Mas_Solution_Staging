<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSessionValidity
{
    public function handle($request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Your session has expired. Please login again.');
        }

        // Example: check your important session variables (change keys accordingly)
        if (!$request->session()->has('CompanyId')) {
            Auth::logout();
            return redirect()->route('login')->with('message', 'Session expired or invalid. Please login again.');
        }

        return $next($request);
    }
}


?>