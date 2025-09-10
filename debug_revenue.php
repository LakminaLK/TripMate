<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=== REVENUE CONTROLLER DEBUG ===\n\n";

$ADMIN_COMMISSION_RATE = 0.10; // 10%

// Test getDailyRevenueForChart method
echo "1. Testing getDailyRevenueForChart:\n";
$endDate = Carbon::now()->endOfDay();
$startDate = Carbon::now()->subDays(6)->startOfDay(); // Last 7 days including today

echo "Date range: {$startDate} to {$endDate}\n";

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
        $item->admin_commission = $item->total_revenue * $ADMIN_COMMISSION_RATE;
        return $item;
    });

echo "Raw booking data:\n";
foreach ($bookingData as $item) {
    echo "Date: {$item->date}, Revenue: {$item->total_revenue}, Bookings: {$item->booking_count}, Commission: {$item->admin_commission}\n";
}

echo "\n2. Testing current month daily revenue:\n";
$now = Carbon::now();
$dateRange = [
    'start' => $now->copy()->startOfMonth(),
    'end' => $now->copy()->endOfMonth()
];

echo "Month range: {$dateRange['start']} to {$dateRange['end']}\n";

$dailyRevenueTable = Booking::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(total_amount) as total_revenue'),
        DB::raw('COUNT(*) as booking_count')
    )
    ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
    ->groupBy('date')
    ->orderBy('date', 'asc')
    ->get()
    ->map(function ($item) use ($ADMIN_COMMISSION_RATE) {
        $item->admin_commission = $item->total_revenue * $ADMIN_COMMISSION_RATE;
        return $item;
    });

echo "Daily revenue table data:\n";
foreach ($dailyRevenueTable as $item) {
    echo "Date: {$item->date}, Revenue: {$item->total_revenue}, Bookings: {$item->booking_count}, Commission: {$item->admin_commission}\n";
}

echo "\n3. Testing Top Hotels by Commission:\n";
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
        $item->admin_commission = $item->total_booking_value * $ADMIN_COMMISSION_RATE;
        return $item;
    });

echo "Top hotels data:\n";
foreach ($topHotels as $item) {
    echo "Hotel ID: {$item->hotel_id}, Hotel: " . ($item->hotel ? $item->hotel->name : 'N/A') . ", Revenue: {$item->total_booking_value}, Bookings: {$item->booking_count}, Commission: {$item->admin_commission}\n";
}

echo "\n4. Testing Hotel Revenue Breakdown:\n";
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
        $item->admin_commission = $item->total_booking_value * $ADMIN_COMMISSION_RATE;
        $item->hotel_revenue = $item->total_booking_value * (1 - $ADMIN_COMMISSION_RATE);
        return $item;
    });

echo "Hotel revenue breakdown:\n";
foreach ($hotelRevenue as $item) {
    echo "Hotel ID: {$item->hotel_id}, Hotel: " . ($item->hotel ? $item->hotel->name : 'N/A') . ", Total Revenue: {$item->total_booking_value}, Bookings: {$item->booking_count}, Commission: {$item->admin_commission}, Hotel Revenue: {$item->hotel_revenue}\n";
}

echo "\n=== END DEBUG ===\n";
