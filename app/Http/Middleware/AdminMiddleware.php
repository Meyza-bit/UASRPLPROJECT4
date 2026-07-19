<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Pastikan yang mengakses halaman ini adalah admin.
     * Kalau bukan, dianggap "halaman tidak ada" (404) — bukan 403,
     * supaya orang luar nggak tahu ada halaman admin di sini sama sekali.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            abort(404);
        }

        return $next($request);
    }
}