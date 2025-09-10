<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use App\Models\Hotel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=== HOTEL REVENUE DEBUG ===\n\n";

// Get first hotel
$hotel = Hotel::first();
if (!$hotel) {
    echo "No hotels found!\n";
    exit;
}

echo "Testing for Hotel: {$hotel->name} (ID: {$hotel->id})\n\n";

// Check hotel bookings
$hotelBookings = Booking::where('hotel_id', $hotel->id)->count();
echo "Total bookings for this hotel: {$hotelBookings}\n";

if ($hotelBookings > 0) {
    // Test this month's data
    $now = Carbon::now();
    $dateRange = [
        'start' => $now->copy()->startOfMonth(),
        'end' => $now->copy()->endOfMonth()
    ];
    
    echo "Date range: {$dateRange['start']} to {$dateRange['end']}\n\n";
    
    $bookings = Booking::where('hotel_id', $hotel->id)
        ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
        ->get();
    
    $totalBookings = $bookings->count();
    $totalBookingValue = $bookings->sum('total_amount');
    $hotelRevenue = $totalBookingValue * 0.90;
    $adminCommission = $totalBookingValue * 0.10;
    
    echo "This month's metrics:\n";
    echo "- Total Bookings: {$totalBookings}\n";
    echo "- Total Booking Value: \${$totalBookingValue}\n";
    echo "- Hotel Revenue (90%): \${$hotelRevenue}\n";
    echo "- Admin Commission (10%): \${$adminCommission}\n\n";
    
    // Test daily revenue for last 7 days
    $endDate = Carbon::now()->endOfDay();
    $startDate = Carbon::now()->subDays(6)->startOfDay();
    
    echo "Daily revenue for last 7 days ({$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}):\n";
    
    $dailyData = Booking::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total_revenue'),
            DB::raw('COUNT(*) as booking_count')
        )
        ->where('hotel_id', $hotel->id)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();
    
    foreach ($dailyData as $day) {
        $hotelRev = $day->total_revenue * 0.90;
        echo "- {$day->date}: {$day->booking_count} bookings, \${$day->total_revenue} total, \${$hotelRev} hotel revenue\n";
    }
    
} else {
    echo "No bookings found for this hotel!\n";
}

echo "\n=== END DEBUG ===\n";
