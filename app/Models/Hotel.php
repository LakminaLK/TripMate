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
        'description',
        'main_image',
        'latitude',
        'longitude',
        'address',
        'phone',
        'website',
        'star_rating',
        'map_url',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'hotel_facilities');
    }

    public function hotelRooms()
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'hotel_rooms')
                    ->withPivot('room_count', 'price_per_night', 'is_available', 'notes')
                    ->withTimestamps();
    }

    /**
     * Get the bookings for the hotel.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the reviews for the hotel.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
