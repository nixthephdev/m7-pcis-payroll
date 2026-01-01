<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuardMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow if user is Admin OR Guard
        if (Auth::check() && (Auth::user()->role === 'guard' || Auth::user()->role === 'admin')) {
            return $next($request);
        }

        // If not allowed, show error
        abort(403, 'Access Denied. Only Security Personnel can access the Scanner.');
    }
}