<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ConfirmPassword
{
    public function handle($request, Closure $next)
    {
        // Check if user is authenticated
        Session::put('prev_url', url()->previous());
        Session::put('intended_url', url()->current());
            // Check if the user has confirmed their password
            if (!session('confirmed_password')) {
                return redirect()->route('confirm-password'); // Change to your confirm password route
            }

        return $next($request);
    }
}
