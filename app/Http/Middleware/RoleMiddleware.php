<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        if (!auth()->check()) {
    if ($request->is('admin') || $request->is('admin/*')) {
        return redirect()->route('admin.login');
    }

    if ($request->is('hotel') || $request->is('hotel/*')) {
        return redirect()->route('hotel.login');
    }

    return redirect()->route('login'); // tourist
}

        abort(403, 'Unauthorized');
    }
}
