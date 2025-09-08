<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\HotelRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        $hotel = Auth::guard('hotel')->user();
        
        // Get all active room types
        $roomTypes = RoomType::where('is_active', true)
                            ->orderBy('sort_order')
                            ->orderBy('name')
                            ->get();
        
        // Get hotel's current room counts
        $hotelRooms = $hotel->hotelRooms()->with('roomType')->get()->keyBy('room_type_id');
        
        return view('hotel.rooms.index', compact('roomTypes', 'hotelRooms', 'hotel'));
    }

    public function updateRoomCounts(Request $request)
    {
        $hotel = Auth::guard('hotel')->user();
        
        $request->validate([
            'room_counts' => 'required|array',
            'room_counts.*' => 'integer|min:0|max:999',
            'prices' => 'array',
            'prices.*' => 'nullable|numeric|min:0|max:999999.99'
        ]);

        foreach ($request->room_counts as $roomTypeId => $count) {
            $price = $request->prices[$roomTypeId] ?? null;
            
            if ($count > 0) {
                // Create or update room count
                HotelRoom::updateOrCreate(
                    [
                        'hotel_id' => $hotel->id,
                        'room_type_id' => $roomTypeId
                    ],
                    [
                        'room_count' => $count,
                        'price_per_night' => $price,
                        'is_available' => true
                    ]
                );
            } else {
                // Remove room type if count is 0
                HotelRoom::where('hotel_id', $hotel->id)
                         ->where('room_type_id', $roomTypeId)
                         ->delete();
            }
        }

        return back()->with('success', 'Room counts updated successfully!');
    }
}
