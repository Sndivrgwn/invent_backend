<?php

use App\Http\Middleware\AutoLogoutAfterTwoDays;
use App\Http\Middleware\UpdateLastActive;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\ForceHttpsWithService;
use Illuminate\View\Middleware\ShareErrorsFromSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('web', [
            EncryptCookies::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            AutoLogoutAfterTwoDays::class,
            UpdateLastActive::class,
            // Remove CheckRole from global middleware group
        ]);
        
        // Register middleware aliases as an array
        $middleware->alias([
            'role' => CheckRole::class,
            // Add other middleware aliases here if needed
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        \Spatie\LaravelFlare\Facades\Flare::handles($exceptions);
    })->create();