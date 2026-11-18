<?php

use App\Http\Middleware\EnsureRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => EnsureRole::class,
        ]);

        // Cek role harus jalan sebelum route-model-binding, supaya non-admin
        // mendapat respons yang sama baik ID-nya ada maupun tidak (bukan 404 vs redirect).
        $middleware->prependToPriorityList(
            before: SubstituteBindings::class,
            prepend: EnsureRole::class,
        );

        // Tamu yang membuka halaman terproteksi (mis. checkout) diarahkan ke login.
        $middleware->redirectGuestsTo(fn (Request $request) => route('login'));

        // User yang sudah login tidak perlu melihat halaman login lagi.
        $middleware->redirectUsersTo(
            fn (Request $request) => $request->user()?->isAdmin()
                ? route('admin.dashboard')
                : route('home')
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
