<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect()->route('home')->with('error', 'Akses ditolak');
        }
        
        return $next($request);
    }
}