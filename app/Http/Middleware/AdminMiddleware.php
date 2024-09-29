<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // Eğer admin girişi yapılmamışsa admin login sayfasına yönlendir.
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }


        return $next($request);
    }
}
