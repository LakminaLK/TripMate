<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Tourist;
use App\Models\RoomType;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample data
        $hotels = Hotel::where('status', 'Active')->take(3)->get();
        $tourists = Tourist::take(5)->get();
        
        if ($hotels->isEmpty() || $tourists->isEmpty()) {
            $this->command->info('No hotels or tourists found. Please seed them first.');
            return;
        }

        foreach ($hotels as $hotel) {
            $roomTypes = $hotel->hotelRooms()->with('roomType')->get();
            
            foreach ($roomTypes as $hotelRoom) {
                // Create some sample bookings for the next 30 days
                for ($i = 0; $i < 5; $i++) {
                    $checkIn = Carbon::now()->addDays(rand(1, 30));
                    $checkOut = $checkIn->copy()->addDays(rand(1, 7));
                    $roomsBooked = rand(1, min(3, $hotelRoom->room_count));
                    
                    $booking = Booking::create([
                        'tourist_id' => $tourists->random()->id,
                        'hotel_id' => $hotel->id,
                        'room_type_id' => $hotelRoom->room_type_id,
                        'check_in_date' => $checkIn,
                        'check_out_date' => $checkOut,
                        'rooms_booked' => $roomsBooked,
                        'guests_count' => $roomsBooked * 2, // 2 guests per room average
                        'price_per_night' => $hotelRoom->price_per_night ?? rand(50, 200),
                        'total_amount' => ($hotelRoom->price_per_night ?? rand(50, 200)) * $roomsBooked * $checkIn->diffInDays($checkOut),
                        'status' => collect(['confirmed', 'pending', 'confirmed', 'confirmed'])->random(),
                        'booking_reference' => Booking::generateBookingReference(),
                        'special_requests' => rand(0, 1) ? 'Late check-in requested' : null,
                    ]);

                    $this->command->info("Created booking {$booking->booking_reference} for {$hotel->name}");
                }
            }
        }

        $this->command->info('Sample bookings created successfully!');
    }
}
