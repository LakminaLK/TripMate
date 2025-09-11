<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Review;
use App\Models\Hotel;

echo "=== HOTEL REVIEW DEBUG ===\n\n";

// Get a sample hotel
$hotel = Hotel::first();
if (!$hotel) {
    echo "No hotels found in database!\n";
    exit;
}

echo "Testing reviews for hotel: {$hotel->name} (ID: {$hotel->id})\n\n";

// Check total reviews for this hotel
$totalReviews = Review::where('hotel_id', $hotel->id)->count();
echo "Total reviews: {$totalReviews}\n";

if ($totalReviews > 0) {
    // Get review statistics
    $approvedReviews = Review::where('hotel_id', $hotel->id)->where('is_approved', true)->count();
    $pendingReviews = Review::where('hotel_id', $hotel->id)->where('is_approved', false)->count();
    
    echo "Approved reviews: {$approvedReviews}\n";
    echo "Pending reviews: {$pendingReviews}\n";
    
    // Average rating
    $averageRating = Review::where('hotel_id', $hotel->id)
        ->where('is_approved', true)
        ->avg('rating');
    echo "Average rating: " . round($averageRating ?? 0, 1) . "\n";
    
    // Rating distribution
    echo "\nRating distribution:\n";
    for ($i = 1; $i <= 5; $i++) {
        $count = Review::where('hotel_id', $hotel->id)
            ->where('is_approved', true)
            ->where('rating', $i)
            ->count();
        echo "{$i} stars: {$count} reviews\n";
    }
    
    // Sample reviews
    echo "\nSample reviews:\n";
    $sampleReviews = Review::with('tourist')
        ->where('hotel_id', $hotel->id)
        ->limit(3)
        ->get();
    
    foreach ($sampleReviews as $review) {
        echo "- {$review->title} ({$review->rating} stars) by " . ($review->tourist->name ?? 'Anonymous') . "\n";
        echo "  {$review->description}\n";
        echo "  Status: " . ($review->is_approved ? 'Approved' : 'Pending') . "\n\n";
    }
} else {
    echo "No reviews found for this hotel.\n";
    echo "You may need to create some test reviews in the database.\n";
}

echo "=== END DEBUG ===\n";
