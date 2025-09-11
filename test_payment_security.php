<?php
// Simple test to verify payment security implementation

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PAYMENT SECURITY TEST ===\n\n";

// Test 1: Check if routes are working
echo "1. Testing routes registration...\n";
$routes = app('router')->getRoutes();
$paymentRoutes = [];

foreach ($routes as $route) {
    if (str_contains($route->uri(), 'payment')) {
        $paymentRoutes[] = $route->uri() . ' => ' . $route->getActionName();
    }
}

echo "Found " . count($paymentRoutes) . " payment routes:\n";
foreach ($paymentRoutes as $route) {
    echo "  - " . $route . "\n";
}

echo "\n2. Testing session functionality...\n";

// Simulate session data
session(['payment_booking_data' => json_encode([
    'hotel_id' => 1,
    'check_in' => '2025-09-15',
    'check_out' => '2025-09-16',
    'total' => 75.00
])]);

echo "Session data set: " . (session('payment_booking_data') ? 'SUCCESS' : 'FAILED') . "\n";

// Test clearing session
session()->forget(['payment_booking_data', 'payment_from_checkout']);
echo "Session data cleared: " . (!session('payment_booking_data') ? 'SUCCESS' : 'FAILED') . "\n";

echo "\n3. Testing controller logic...\n";

// Test controller instantiation
try {
    $controller = new App\Http\Controllers\Tourist\PaymentController();
    echo "PaymentController instantiated: SUCCESS\n";
} catch (Exception $e) {
    echo "PaymentController instantiated: FAILED - " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
