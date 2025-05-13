<?php

use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\IsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Fortify\Fortify;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'is_admin' => IsAdmin::class,
            'ensure-user-active' => EnsureUserIsActive::class, // Ensure alias matches
            'ensure-email-verified' => EnsureEmailIsVerified::class, // Ensure alias matches
        ]);
        // $middleware->web(append: [
        //     \App\Http\Middleware\CorsMiddleware::class,
        // ]);
    })
    // Register exception handler to use your custom Handler
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->reportable(function (Throwable $e) {
            // Here you can handle exception reporting if needed
            // For example, send the exception to a monitoring service
        });

        $exceptions->renderable(function (Throwable $e, $request) {
            // Check if it's an email verification exception from Fortify
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) { // Replace with a valid exception
                return response()->json([
                    'success' => false,
                    'message' => 'Your email address is not verified.',
                ], 400); // Custom status code
            }

            // General API error handling
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500); // Generic internal server error
        });
    })
    ->create();
