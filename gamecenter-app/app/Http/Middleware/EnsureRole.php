<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Batasi route berdasarkan role (mis. role:admin).
     * Tamu diarahkan ke login, user biasa dikembalikan ke beranda toko.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        if ($user->role !== $role) {
            return redirect()->route('home')->with('error', 'Kamu tidak punya akses ke halaman itu.');
        }

        return $next($request);
    }
}
