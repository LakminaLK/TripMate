<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=== COMMISSION CALCULATION DEBUG ===\n\n";

$ADMIN_COMMISSION_RATE = 0.10; // 10%

// Test simple calculation first
$testRevenue = 1770.00;
$testCommission = $testRevenue * $ADMIN_COMMISSION_RATE;
echo "Test calculation: {$testRevenue} * {$ADMIN_COMMISSION_RATE} = {$testCommission}\n\n";

// Test database query
$endDate = Carbon::now()->endOfDay();
$startDate = Carbon::now()->subDays(6)->startOfDay();

$rawData = Booking::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(total_amount) as total_revenue'),
        DB::raw('COUNT(*) as booking_count')
    )
    ->whereBetween('created_at', [$startDate, $endDate])
    ->groupBy('date')
    ->orderBy('date', 'asc')
    ->get();

echo "Raw data from database:\n";
foreach ($rawData as $item) {
    echo "Date: {$item->date}\n";
    echo "  Total Revenue: {$item->total_revenue} (type: " . gettype($item->total_revenue) . ")\n";
    echo "  Booking Count: {$item->booking_count} (type: " . gettype($item->booking_count) . ")\n";
    
    // Test calculations
    $revenue = (float) $item->total_revenue;
    $commission1 = $revenue * $ADMIN_COMMISSION_RATE;
    $commission2 = floatval($item->total_revenue) * 0.10;
    $commission3 = $item->total_revenue * 0.10;
    
    echo "  Commission calc 1 (cast float): {$commission1}\n";
    echo "  Commission calc 2 (floatval): {$commission2}\n";
    echo "  Commission calc 3 (direct): {$commission3}\n";
    echo "\n";
}

echo "=== END DEBUG ===\n";
