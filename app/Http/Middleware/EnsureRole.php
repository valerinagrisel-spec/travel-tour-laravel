<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Membatasi akses berdasarkan role yang tersimpan pada tabel users.
     * Contoh: ->middleware(EnsureRole::class . ':staff')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_unless($user && in_array($user->role, $roles, true), 403, 'Anda tidak memiliki izin mengakses halaman ini.');

        return $next($request);
    }
}
