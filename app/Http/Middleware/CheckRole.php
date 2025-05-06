<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()) {
            return redirect('login');
        }

       // Dapatkan role pengguna saat ini
       $userRole = $request->user()->role->nama;

       // Tentukan halaman tujuan berdasarkan role
       $intendedRoute = null;
       if ($userRole === 'admin') {
           $intendedRoute = 'dashboard';
       } elseif ($userRole === 'user') {
           $intendedRoute = 'tasks.index';
       }

       // Jika halaman saat ini bukan halaman yang sesuai dengan role,
       // dan pengguna belum berada di halaman yang dituju, alihkan mereka.
       if ($intendedRoute && !$request->routeIs($intendedRoute)) {
           return redirect()->route($intendedRoute);
       }
        
        return $next($request);
    }
}