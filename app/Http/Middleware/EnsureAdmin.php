<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     * Allows only users with the 'admin' role to pass through.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            if (Auth::check()) {
                // Authenticated but wrong role — redirect to student area
                return redirect()->route('student.dashboard');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
