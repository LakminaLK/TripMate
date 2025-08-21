<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Hotel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'hotels';

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'status',       // 'Active' | 'Inactive'
        'location_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // 🔕 Booking relation removed until the Booking model exists.
    // public function bookings() { return $this->hasMany(Booking::class); }
}
