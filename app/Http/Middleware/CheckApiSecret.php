<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiSecret
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $secret = env('API_SECRET_KEY');

        if (!$secret || $request->header('X-API-SECRET') !== $secret) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
