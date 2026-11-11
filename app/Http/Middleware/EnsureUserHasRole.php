<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles, true)) {
            if ($request->user() && $request->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($request->user() && $request->user()->isPelanggan()) {
                return redirect()->route('pelanggan.home');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
