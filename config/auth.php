<?php

return [

    'defaults' => [
        'guard' => 'tourist', // Default guard
        'passwords' => 'tourists',
    ],

    // ✅ All guards defined here
    'guards' => [
        'hotel' => [
            'driver' => 'session',
            'provider' => 'hotels',
        ],


        'tourist' => [
            'driver' => 'session',
            'provider' => 'tourists',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    // ✅ User providers
    'providers' => [
        'hotels' => [
            'driver' => 'eloquent',
            'model' => App\Models\Hotel::class,
        ],


        'tourists' => [
            'driver' => 'eloquent',
            'model' => App\Models\Tourist::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    // ✅ Password reset configurations
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'tourists' => [
            'provider' => 'tourists',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

    'redirects' => [
    'admin' => '/admin/login',
    'hotel' => '/hotel/login',
    'web' => '/login',
],


];
