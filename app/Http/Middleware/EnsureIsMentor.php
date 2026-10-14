<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsMentor
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || (!auth()->user()->isMentor() && !auth()->user()->isSuperAdmin())) {
            abort(403, 'Akses ditolak. Hanya Mentor yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
