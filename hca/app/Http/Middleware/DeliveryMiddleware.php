<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DeliveryMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('coordinator_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login to access delivery portal');
        }

        return $next($request);
    }
}