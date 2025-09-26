<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class HotelNotification extends Model
{
    protected $fillable = [
        'hotel_id',
        'type',
        'title',
        'message',
        'action_url',
        'related_id',
        'related_type',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the hotel that owns this notification
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the related booking if this is a booking notification
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'related_id')->where('related_type', 'booking');
    }

    /**
     * Get the related review if this is a review notification
     */
    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'related_id')->where('related_type', 'review');
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => Carbon::now()
        ]);
    }

    /**
     * Get unread notifications for a hotel
     */
    public static function unreadForHotel($hotelId)
    {
        return static::where('hotel_id', $hotelId)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get recent notifications for a hotel
     */
    public static function recentForHotel($hotelId, $limit = 10)
    {
        return static::where('hotel_id', $hotelId)
            ->orderBy('created_at', 'desc')
            ->limit($limit);
    }

    /**
     * Create notification for new booking
     */
    public static function createBookingNotification($hotelId, $booking)
    {
        $touristName = $booking->tourist ? $booking->tourist->name : 'Guest';
        
        return static::create([
            'hotel_id' => $hotelId,
            'type' => 'booking',
            'title' => 'New Booking Received',
            'message' => "Booking #{$booking->booking_reference} from {$touristName}",
            'action_url' => route('hotel.bookings.index'),
            'related_id' => $booking->id,
            'related_type' => 'booking'
        ]);
    }

    /**
     * Create notification for new review
     */
    public static function createReviewNotification($hotelId, $review)
    {
        $rating = $review->rating;
        $touristName = $review->tourist ? $review->tourist->name : 'Guest';
        
        return static::create([
            'hotel_id' => $hotelId,
            'type' => 'review',
            'title' => 'New Review Posted',
            'message' => "{$rating}-star review from {$touristName}: \"{$review->title}\"",
            'action_url' => route('hotel.reviews.index'),
            'related_id' => $review->id,
            'related_type' => 'review'
        ]);
    }

    /**
     * Get time ago string
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get icon based on notification type
     */
    public function getIconAttribute()
    {
        return [
            'booking' => 'fas fa-calendar-check',
            'review' => 'fas fa-star',
        ][$this->type] ?? 'fas fa-bell';
    }

    /**
     * Get color based on notification type
     */
    public function getColorAttribute()
    {
        return [
            'booking' => 'blue',
            'review' => 'yellow',
        ][$this->type] ?? 'gray';
    }

    /**
     * Scope for old read notifications
     */
    public function scopeOldRead($query, $hours = 24)
    {
        return $query->where('is_read', true)
            ->where('read_at', '<', Carbon::now()->subHours($hours));
    }

    /**
     * Scope for very old unread notifications
     */
    public function scopeVeryOldUnread($query, $days = 30)
    {
        return $query->where('is_read', false)
            ->where('created_at', '<', Carbon::now()->subDays($days));
    }

    /**
     * Clean up old notifications
     */
    public static function cleanupOld($hours = 24)
    {
        $deletedRead = static::oldRead($hours)->delete();
        $deletedOldUnread = static::veryOldUnread(30)->delete();
        
        return [
            'deleted_read' => $deletedRead,
            'deleted_old_unread' => $deletedOldUnread,
            'total_deleted' => $deletedRead + $deletedOldUnread
        ];
    }
}
