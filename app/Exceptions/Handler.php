<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class Handler extends ExceptionHandler
{
    protected $levels = [];
    protected $dontReport = [];
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];


    /**
     * Handle unauthenticated guard redirection
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {

        \Log::info('UNAUTHENTICATED HIT', ['guard' => $exception->guards()]);

        if ($request->expectsJson()) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        $guard = $exception->guards()[0] ?? 'web';

        switch ($guard) {
            case 'admin':
                $loginRoute = 'admin.login';
                break;
            case 'hotel':
                $loginRoute = 'hotel.login';
                break;
            default:
                $loginRoute = 'login';
                break;
        }

        return redirect()->route($loginRoute);
    }
}
