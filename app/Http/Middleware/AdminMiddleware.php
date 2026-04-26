<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Garante que apenas utilizadores admin acedem
 * às rotas globais e sensíveis de analytics.
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Admin access required.');
        }

        return $next($request);
    }
}
