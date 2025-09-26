<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\CustomAuthenticate;


class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     */
    protected $middleware = [
        \App\Http\Middleware\SecurityHeaders::class,
        \App\Http\Middleware\DetectSuspiciousActivity::class,
        // ... other global middlewares
    ];

    protected $middlewareGroups = [
        'web' => [
            // ...
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // ⬇️ this line ensures your Authenticate middleware works
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        
        'api' => [
            \App\Http\Middleware\SecurityRateLimiter::class . ':60,1', // 60 requests per minute
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        // ...
        'auth' => \App\Http\Middleware\CustomAuthenticate::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'security.rate' => \App\Http\Middleware\SecurityRateLimiter::class,
        'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
        'security.detect' => \App\Http\Middleware\DetectSuspiciousActivity::class,
    ];
}
