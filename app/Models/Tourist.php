<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Tourist extends Authenticatable
{
    use Notifiable;

    protected $guard = 'tourist';

    protected $fillable = [
    'name',
    'email',
    'mobile',
    'password',
    'otp',              // ✅ add this
    'otp_verified'      // ✅ and this
];

    protected $hidden = [
        'password', 'remember_token',
    ];
}

