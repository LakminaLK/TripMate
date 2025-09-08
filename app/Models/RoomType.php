<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'max_occupancy',
        'base_price',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function hotelRooms()
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_rooms')
                    ->withPivot('room_count', 'price_per_night', 'is_available', 'notes')
                    ->withTimestamps();
    }
}
