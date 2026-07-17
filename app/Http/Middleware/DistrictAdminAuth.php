<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DistrictAdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->has('district_admin')) {
            return redirect()->route('district-admin.login');
        }

        return $next($request);
    }
}
