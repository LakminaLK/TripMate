<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\CustomAuthenticate;


class Kernel extends HttpKernel
{
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
    ];

    protected $routeMiddleware = [
        // ...
        'auth' => \App\Http\Middleware\CustomAuthenticate::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}
