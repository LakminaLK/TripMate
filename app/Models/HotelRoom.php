<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelRoom extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_type_id',
        'room_count',
        'price_per_night',
        'is_available',
        'notes'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
