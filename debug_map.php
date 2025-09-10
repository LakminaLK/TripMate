<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=== CONTROLLER MAP FUNCTION DEBUG ===\n\n";

$ADMIN_COMMISSION_RATE = 0.10; // 10%

$endDate = Carbon::now()->endOfDay();
$startDate = Carbon::now()->subDays(6)->startOfDay();

echo "Testing exact controller code:\n";

// This is EXACTLY what the controller does
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
        echo "Inside map function:\n";
        echo "  Item type: " . get_class($item) . "\n";
        echo "  Total revenue: {$item->total_revenue} (type: " . gettype($item->total_revenue) . ")\n";
        echo "  Commission rate: {$ADMIN_COMMISSION_RATE} (type: " . gettype($ADMIN_COMMISSION_RATE) . ")\n";
        
        $commission = $item->total_revenue * $ADMIN_COMMISSION_RATE;
        echo "  Calculated commission: {$commission} (type: " . gettype($commission) . ")\n";
        
        $item->admin_commission = $commission;
        echo "  Assigned commission: {$item->admin_commission}\n";
        echo "  Item properties: " . json_encode($item->toArray()) . "\n\n";
        
        return $item;
    });

echo "Final result:\n";
foreach ($bookingData as $item) {
    echo "Date: {$item->date}, Revenue: {$item->total_revenue}, Commission: {$item->admin_commission}\n";
}

echo "\n=== END DEBUG ===\n";
