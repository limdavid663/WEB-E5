<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and is a super admin
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            return redirect()
                ->route('home')
                ->with('error', 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
