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
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $e, $request) {
            // Only apply to API routes
            if ($request->is('api/*')) {

                // Handle specific Fortify email verification exception
                if ($e instanceof \Laravel\Fortify\Exceptions\EmailVerificationRequiredException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your email address is not verified.',
                    ], 403);
                }

                // Handle other exceptions for API
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            // For non-API requests (web), let Laravel handle it normally
            return null;
        });
    })
    ->create();
