<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user is an employee
        if ($user && $user->role === 'user') {
            // Allow read-only actions
            if ($request->isMethod('GET')) {
                return $next($request);
            }

            // Deny non-read actions
            return response()->json(['error' => 'Unauthorized'], 403);
        }


        return $next($request);
        // return redirect('/');
    }
}