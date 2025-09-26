<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Booking;

class HotelProfileController extends Controller
{
    public function dashboard(Request $request)
    {
        $hotel = auth('hotel')->user();
        $period = $request->get('period', 'last_month');
        
        // Initialize default values
        $totalRooms = 0;
        $totalBookings = 0;
        $last30DaysRevenue = 0;
        $totalReviews = 0;
        $bookingsToday = 0;
        $averageRating = 0;
        
        try {
            // Get statistics for the logged-in hotel using Eloquent models
            
            // Total rooms from hotel_rooms table (pivot table)
            $totalRooms = $hotel->hotelRooms()->sum('room_count') ?? 0;
            
            // Total bookings (only confirmed and completed - same as revenue page)
            $totalBookings = $hotel->bookings()
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereIn('booking_status', ['confirmed', 'completed'])
                ->count() ?? 0;
            
            // Revenue for last 30 days (hotel gets 90%, admin gets 10%)
            // Only include confirmed and completed bookings - same as revenue page
            $last30DaysRevenue = $hotel->bookings()
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereIn('booking_status', ['confirmed', 'completed'])
                ->sum('total_amount') ?? 0;
            
            // Calculate hotel's 90% share
            $last30DaysRevenue = $last30DaysRevenue * 0.90;
            
            // Total reviews
            $totalReviews = $hotel->reviews()->count() ?? 0;
            
            // Average rating
            $averageRating = $hotel->reviews()->avg('rating') ?? 0;
            
            // Bookings today (only confirmed and completed)
            $bookingsToday = $hotel->bookings()
                ->whereDate('created_at', today())
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereIn('booking_status', ['confirmed', 'completed'])
                ->count() ?? 0;
            
        } catch (\Exception $e) {
            // Log the error but don't break the page
            \Log::error('Dashboard statistics error: ' . $e->getMessage());
        }
        
        // Get revenue data for chart
        $revenueData = [];
        try {
            $revenueData = $this->getRevenueData($hotel->id, $period);
        } catch (\Exception $e) {
            \Log::error('Revenue chart data error: ' . $e->getMessage());
            $revenueData = [];
        }
        
        return view('hotel.dashboard', compact(
            'hotel', 
            'totalRooms', 
            'totalBookings', 
            'last30DaysRevenue', 
            'totalReviews',
            'bookingsToday',
            'averageRating',
            'revenueData',
            'period'
        ));
    }
    
    private function getRevenueData($hotelId, $period)
    {
        try {
            switch ($period) {
                case 'last_7_days':
                    $startDate = Carbon::now()->subDays(7);
                    $endDate = Carbon::now();
                    break;
                case 'last_month':
                    $startDate = Carbon::now()->subMonth();
                    $endDate = Carbon::now();
                    break;
                case 'last_3_months':
                    $startDate = Carbon::now()->subMonths(3);
                    $endDate = Carbon::now();
                    break;
                case 'last_year':
                    $startDate = Carbon::now()->subYear();
                    $endDate = Carbon::now();
                    break;
                default:
                    $startDate = Carbon::now()->subMonth();
                    $endDate = Carbon::now();
            }
            
            // Get daily revenue data for chart using same logic as revenue page
            // Only include confirmed and completed bookings
            $dailyRevenue = \App\Models\Booking::where('hotel_id', $hotelId)
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereIn('booking_status', ['confirmed', 'completed'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, SUM(total_amount * 0.90) as revenue, COUNT(*) as booking_count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
                
            // Fill missing dates with zero values
            $dateRange = [];
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $dateStr = $current->format('Y-m-d');
                $existing = $dailyRevenue->firstWhere('date', $dateStr);
                
                $dateRange[] = (object) [
                    'date' => $dateStr,
                    'revenue' => $existing ? floatval($existing->revenue) : 0,
                    'booking_count' => $existing ? intval($existing->booking_count) : 0
                ];
                
                $current->addDay();
            }
            
            return $dateRange;
        } catch (\Exception $e) {
            \Log::error('Revenue data generation error: ' . $e->getMessage());
            return [];
        }
    }

    public function edit()
    {
        $hotel = auth('hotel')->user();
        return view('hotel.profile', compact('hotel'));
    }

    public function updatePassword(Request $request)
    {
        $hotel = auth('hotel')->user();

        $data = $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8),
                'regex:/^(?=.*[a-z])(?=.*[A-Z]).+$/'
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase and one lowercase letter.'
        ]);

        if (! Hash::check($data['current_password'], $hotel->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $hotel->password = Hash::make($data['password']);
        $hotel->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateUsername(Request $request)
    {
        $hotel = auth('hotel')->user();

        // Custom validation for case-sensitive uniqueness
        $existingHotel = \App\Models\Hotel::whereRaw('BINARY username = ?', [$request->username])
            ->where('id', '!=', $hotel->id)
            ->first();
        
        if ($existingHotel) {
            return back()->withErrors(['username' => 'This username is already taken.']);
        }

        $data = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $hotel->username = $data['username'];
        $hotel->save();

        return back()->with('success', 'Username updated successfully.');
    }
}
