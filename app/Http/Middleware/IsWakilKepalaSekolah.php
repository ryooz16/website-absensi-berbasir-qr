<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsWakilKepalaSekolah
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'wakil_kepala_sekolah') {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
