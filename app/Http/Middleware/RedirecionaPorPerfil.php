<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirecionaPorPerfil
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
