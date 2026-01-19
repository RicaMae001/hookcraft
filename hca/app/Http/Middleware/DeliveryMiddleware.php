<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DeliveryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if delivery coordinator is authenticated
        if (!session()->has('coordinator_id')) {
            // If it's an AJAX request, return JSON error
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not authenticated. Please login again.',
                    'redirect' => route('staff.login')
                ], 401);
            }
            
            // Otherwise redirect to login page
            return redirect()->route('staff.login')
                ->with('error', 'Please login as delivery coordinator');
        }

        return $next($request);
    }
}