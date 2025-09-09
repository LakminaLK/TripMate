<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'tourist_id',
        'hotel_id', 
        'room_type_id',
        'check_in',
        'check_out',
        'check_in_date',  // Keep old field for compatibility
        'check_out_date', // Keep old field for compatibility
        'rooms_booked',
        'guests_count',
        'price_per_night',
        'total_amount',
        'status',
        'booking_status',
        'payment_status',
        'payment_method',
        'payment_details',
        'booking_details',
        'special_requests',
        'booking_reference'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'price_per_night' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'booking_date' => 'datetime',
        'payment_details' => 'array',
        'booking_details' => 'array'
    ];

    protected $dates = [
        'check_in_date',
        'check_out_date',
        'booking_date'
    ];

    /**
     * Relationships
     */
    public function tourist(): BelongsTo
    {
        return $this->belongsTo(Tourist::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Generate unique booking reference
     */
    public static function generateBookingReference(): string
    {
        do {
            $reference = 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        } while (self::where('booking_reference', $reference)->exists());
        
        return $reference;
    }

    /**
     * Get bookings that overlap with given date range
     */
    public static function getOverlappingBookings($hotelId, $roomTypeId, $checkIn, $checkOut, $excludeBookingId = null)
    {
        $query = self::where('hotel_id', $hotelId)
            ->where('room_type_id', $roomTypeId)
            ->where(function($q) {
                // Consider bookings that are not cancelled
                $q->where(function($q2) {
                    $q2->where('status', '!=', 'cancelled')
                       ->orWhereNull('status');
                })->where(function($q2) {
                    $q2->where('booking_status', '!=', 'cancelled')
                       ->orWhereNull('booking_status');
                });
            })
            ->where(function ($q) use ($checkIn, $checkOut) {
                // Check for overlapping dates using both date field sets
                $q->where(function ($q2) use ($checkIn, $checkOut) {
                    // Using check_in/check_out fields
                    $q2->where(function ($q3) use ($checkIn, $checkOut) {
                        $q3->where('check_in', '<=', $checkIn)
                           ->where('check_out', '>', $checkIn);
                    })->orWhere(function ($q3) use ($checkIn, $checkOut) {
                        $q3->where('check_in', '<', $checkOut)
                           ->where('check_out', '>=', $checkOut);
                    })->orWhere(function ($q3) use ($checkIn, $checkOut) {
                        $q3->where('check_in', '>=', $checkIn)
                           ->where('check_out', '<=', $checkOut);
                    });
                })->orWhere(function ($q2) use ($checkIn, $checkOut) {
                    // Using check_in_date/check_out_date fields as fallback
                    $q2->where(function ($q3) use ($checkIn, $checkOut) {
                        $q3->where('check_in_date', '<=', $checkIn)
                           ->where('check_out_date', '>', $checkIn);
                    })->orWhere(function ($q3) use ($checkIn, $checkOut) {
                        $q3->where('check_in_date', '<', $checkOut)
                           ->where('check_out_date', '>=', $checkOut);
                    })->orWhere(function ($q3) use ($checkIn, $checkOut) {
                        $q3->where('check_in_date', '>=', $checkIn)
                           ->where('check_out_date', '<=', $checkOut);
                    });
                });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query;
    }

    /**
     * Get available room count for a specific room type and date range
     */
    public static function getAvailableRoomCount($hotelId, $roomTypeId, $checkIn, $checkOut, $excludeBookingId = null)
    {
        // Get the total room count for this room type at this hotel
        $hotelRoom = \App\Models\HotelRoom::where('hotel_id', $hotelId)
            ->where('room_type_id', $roomTypeId)
            ->where('is_available', true)
            ->first();

        if (!$hotelRoom) {
            return 0;
        }

        // Get overlapping bookings
        $overlappingBookings = self::getOverlappingBookings($hotelId, $roomTypeId, $checkIn, $checkOut, $excludeBookingId);
        $bookedRooms = $overlappingBookings->sum('rooms_booked');

        // Calculate available rooms
        return max(0, $hotelRoom->room_count - $bookedRooms);
    }
}
