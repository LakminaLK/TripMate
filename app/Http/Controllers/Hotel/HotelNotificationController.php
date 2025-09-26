<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotelNotification;
use Illuminate\Support\Facades\Auth;

class HotelNotificationController extends Controller
{
    /**
     * Get notifications for the authenticated hotel
     */
    public function index(Request $request)
    {
        $hotel = Auth::guard('hotel')->user();
        
        $query = HotelNotification::where('hotel_id', $hotel->id)
            ->orderBy('created_at', 'desc');
            
        // If requesting only unread
        if ($request->get('unread')) {
            $query->where('is_read', false);
        }
        
        $notifications = $query->take(10)->get();
        $unreadCount = HotelNotification::where('hotel_id', $hotel->id)
            ->where('is_read', false)
            ->count();
        
        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'action_url' => $notification->action_url,
                    'is_read' => $notification->is_read,
                    'time_ago' => $notification->time_ago,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'created_at' => $notification->created_at->toISOString(),
                ];
            }),
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $hotel = Auth::guard('hotel')->user();
        
        $notification = HotelNotification::where('hotel_id', $hotel->id)
            ->where('id', $id)
            ->first();
            
        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'action_url' => $notification->action_url
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $hotel = Auth::guard('hotel')->user();
        
        HotelNotification::where('hotel_id', $hotel->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
            
        return response()->json(['success' => true]);
    }

    /**
     * Clear all notifications
     */
    public function clearAll(Request $request)
    {
        $hotel = Auth::guard('hotel')->user();
        
        HotelNotification::where('hotel_id', $hotel->id)->delete();
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark notifications as read by type when user visits specific pages
     */
    public function markTypeAsRead(Request $request, $type)
    {
        $hotel = Auth::guard('hotel')->user();
        
        $validTypes = ['booking', 'review'];
        if (!in_array($type, $validTypes)) {
            return response()->json(['error' => 'Invalid type'], 400);
        }
        
        HotelNotification::where('hotel_id', $hotel->id)
            ->where('type', $type)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
            
        return response()->json(['success' => true]);
    }

    /**
     * Get unread count for the authenticated hotel
     */
    public function getUnreadCount()
    {
        $hotel = Auth::guard('hotel')->user();
        
        $count = HotelNotification::where('hotel_id', $hotel->id)
            ->where('is_read', false)
            ->count();
            
        return response()->json(['unread_count' => $count]);
    }

    /**
     * Manually trigger cleanup of old notifications (for testing)
     */
    public function cleanup(Request $request)
    {
        $hours = $request->get('hours', 24);
        $result = HotelNotification::cleanupOld($hours);
        
        return response()->json([
            'success' => true,
            'message' => "Cleaned up {$result['total_deleted']} notifications",
            'details' => $result
        ]);
    }
}
