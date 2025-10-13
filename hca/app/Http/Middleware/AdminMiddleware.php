<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login to access admin panel');
        }

        return $next($request);
    }
}