<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudent
{
    /**
     * Handle an incoming request.
     * Allows only users with the 'student' role to pass through.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || Auth::user()->role !== 'student') {
            if (Auth::check()) {
                // Authenticated but wrong role — redirect to admin area
                return redirect()->route('admin.payment-report');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
