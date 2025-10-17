<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to force JSON responses for API routes
 * This ensures that even with Accept: *\/* header, API returns JSON
 */
final class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): Response $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force JSON response for API routes
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        // Ensure response is JSON
        if (!$response->headers->has('Content-Type')) {
            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }
}
