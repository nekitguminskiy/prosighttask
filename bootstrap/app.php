<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Force JSON responses for all API routes
        $middleware->group('api', [
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\ForceJsonResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\App\Exceptions\SalesmanNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => [
                        [
                            'code' => 'PERSON_NOT_FOUND',
                            'message' => $e->getMessage(),
                        ],
                    ],
                ], 404);
            }
        });

        $exceptions->render(function (\App\Exceptions\SalesmanAlreadyExistsException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => [
                        [
                            'code' => 'PERSON_ALREADY_EXISTS',
                            'message' => $e->getMessage(),
                        ],
                    ],
                ], 409);
            }
        });
    })->create();
