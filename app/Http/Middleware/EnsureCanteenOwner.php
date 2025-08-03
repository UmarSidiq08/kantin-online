<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanteenOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Cek apakah user login dan memiliki peran admin & memiliki kantin
        if (!$user || $user->role !== 'admin' || !$user->canteen) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
