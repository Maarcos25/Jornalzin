<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->tipo !== 'administrador') {
            return redirect('/')->with('error', 'Acesso negado.');
        }

        return $next($request);
    }
}
