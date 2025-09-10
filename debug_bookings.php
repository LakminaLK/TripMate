<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use Carbon\Carbon;

echo "=== BOOKING DEBUG INFO ===\n\n";

// Total bookings
$totalBookings = Booking::count();
echo "Total bookings in database: {$totalBookings}\n\n";

if ($totalBookings > 0) {
    // Show some sample bookings
    echo "Sample bookings:\n";
    $sampleBookings = Booking::select('id', 'total_amount', 'created_at', 'status', 'booking_status')->limit(5)->get();
    foreach ($sampleBookings as $booking) {
        echo "ID: {$booking->id}, Amount: {$booking->total_amount}, Created: {$booking->created_at}, Status: {$booking->status}, Booking Status: {$booking->booking_status}\n";
    }
    
    echo "\n";
    
    // Check this month's bookings
    $thisMonth = Booking::whereBetween('created_at', [
        Carbon::now()->startOfMonth(),
        Carbon::now()->endOfMonth()
    ])->count();
    echo "This month's bookings: {$thisMonth}\n";
    
    // Check today's bookings
    $today = Booking::whereDate('created_at', Carbon::today())->count();
    echo "Today's bookings: {$today}\n";
    
    // Check last 7 days
    $last7Days = Booking::whereBetween('created_at', [
        Carbon::now()->subDays(6)->startOfDay(),
        Carbon::now()->endOfDay()
    ])->count();
    echo "Last 7 days bookings: {$last7Days}\n";
    
    // Total revenue
    $totalRevenue = Booking::sum('total_amount');
    echo "Total revenue: {$totalRevenue}\n";
    
    // Commission calculation
    $commission = $totalRevenue * 0.10;
    echo "Total commission (10%): {$commission}\n";
    
} else {
    echo "No bookings found in database!\n";
    echo "You may need to:\n";
    echo "1. Run the booking seeder: php artisan db:seed --class=BookingSeeder\n";
    echo "2. Or create some test bookings manually\n";
}

echo "\n=== END DEBUG ===\n";
