<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=== TESTING FIXED CONTROLLER METHODS ===\n\n";

$ADMIN_COMMISSION_RATE = 0.10;

// Test the fixed getDailyRevenueForChart method
echo "1. Testing getDailyRevenueForChart (fixed):\n";
$endDate = Carbon::now()->endOfDay();
$startDate = Carbon::now()->subDays(6)->startOfDay();

$bookingData = Booking::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(total_amount) as total_revenue'),
        DB::raw('COUNT(*) as booking_count')
    )
    ->whereBetween('created_at', [$startDate, $endDate])
    ->groupBy('date')
    ->orderBy('date', 'asc')
    ->get()
    ->map(function ($item) use ($ADMIN_COMMISSION_RATE) {
        return (object) [
            'date' => $item->date,
            'total_revenue' => (float) $item->total_revenue,
            'booking_count' => (int) $item->booking_count,
            'admin_commission' => (float) $item->total_revenue * $ADMIN_COMMISSION_RATE
        ];
    });

foreach ($bookingData as $item) {
    echo "Date: {$item->date}, Revenue: {$item->total_revenue}, Commission: {$item->admin_commission}\n";
}

echo "\n2. Testing getTopHotelsByCommission (fixed):\n";
$now = Carbon::now();
$dateRange = [
    'start' => $now->copy()->startOfMonth(),
    'end' => $now->copy()->endOfMonth()
];

$topHotels = Booking::select('hotel_id')
    ->selectRaw('SUM(total_amount) as total_booking_value')
    ->selectRaw('COUNT(*) as booking_count')
    ->with('hotel')
    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
    ->groupBy('hotel_id')
    ->orderByRaw('SUM(total_amount) DESC')
    ->limit(10)
    ->get()
    ->map(function ($item) use ($ADMIN_COMMISSION_RATE) {
        return (object) [
            'hotel_id' => $item->hotel_id,
            'hotel' => $item->hotel,
            'total_booking_value' => (float) $item->total_booking_value,
            'booking_count' => (int) $item->booking_count,
            'admin_commission' => (float) $item->total_booking_value * $ADMIN_COMMISSION_RATE
        ];
    });

foreach ($topHotels as $item) {
    echo "Hotel ID: {$item->hotel_id}, Hotel: " . ($item->hotel ? $item->hotel->name : 'N/A') . ", Revenue: {$item->total_booking_value}, Commission: {$item->admin_commission}\n";
}

echo "\n3. Testing getHotelRevenueBreakdown (fixed):\n";
$hotelRevenue = Booking::select('hotel_id')
    ->selectRaw('SUM(total_amount) as total_booking_value')
    ->selectRaw('COUNT(*) as booking_count')
    ->selectRaw('AVG(total_amount) as avg_booking_value')
    ->with('hotel')
    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
    ->groupBy('hotel_id')
    ->orderByRaw('SUM(total_amount) DESC')
    ->get()
    ->map(function ($item) use ($ADMIN_COMMISSION_RATE) {
        $totalBookingValue = (float) $item->total_booking_value;
        return (object) [
            'hotel_id' => $item->hotel_id,
            'hotel' => $item->hotel,
            'total_booking_value' => $totalBookingValue,
            'booking_count' => (int) $item->booking_count,
            'avg_booking_value' => (float) $item->avg_booking_value,
            'admin_commission' => $totalBookingValue * $ADMIN_COMMISSION_RATE,
            'hotel_revenue' => $totalBookingValue * (1 - $ADMIN_COMMISSION_RATE)
        ];
    });

foreach ($hotelRevenue as $item) {
    echo "Hotel ID: {$item->hotel_id}, Hotel: " . ($item->hotel ? $item->hotel->name : 'N/A') . ", Total Revenue: {$item->total_booking_value}, Commission: {$item->admin_commission}, Hotel Revenue: {$item->hotel_revenue}\n";
}

echo "\n=== END TEST ===\n";
