<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        if ($userRole !== $role) {
            abort(403, 'Akses ditolak. Anda harus ' . ($role === 'admin' ? 'admin' : 'siswa') . ' untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}

