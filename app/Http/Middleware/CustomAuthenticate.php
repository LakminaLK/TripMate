<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class CustomAuthenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Debug Log
            file_put_contents(storage_path('logs/auth-path.log'), $request->path() . PHP_EOL, FILE_APPEND);

            if ($request->is('admin') || $request->is('admin/*')) {
                file_put_contents(storage_path('logs/auth-path.log'), "Redirecting to admin.login\n", FILE_APPEND);
                return route('admin.login');
            }

            if ($request->is('hotel') || $request->is('hotel/*')) {
                file_put_contents(storage_path('logs/auth-path.log'), "Redirecting to hotel.login\n", FILE_APPEND);
                return route('hotel.login');
            }

            file_put_contents(storage_path('logs/auth-path.log'), "Redirecting to tourist login\n", FILE_APPEND);
            return route('login');
        }

        return null;
    }

    public function handle($request, \Closure $next, ...$guards)
    {
        // Check if the user is already authenticated as an admin
        if ($request->is('admin/login') && auth('admin')->check()) {
            return redirect()->route('admin.dashboard'); // Redirect to admin dashboard
        }

        return parent::handle($request, $next, ...$guards);
    }
}
