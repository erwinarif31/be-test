<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: '/',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
                   'auth' => \App\Http\Middleware\Authenticate::class,
               ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'status' => 'falied',
                'message' => 'Unauthenticated.'
            ], 401);
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'status' => 'falied',
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        });
    })->create();
