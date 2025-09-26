<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\TouristPasswordResetNotification;

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
    'otp_verified',      // ✅ and this
    'location'
];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new TouristPasswordResetNotification($token));
    }

    /**
     * Get the bookings for the tourist.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

