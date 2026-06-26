<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            // Redirect ke dashboard sesuai role-nya
            $redirect = $request->user()?->role === 'admin' ? '/agen' : '/user';
            return redirect($redirect);
        }

        return $next($request);
    }
}
