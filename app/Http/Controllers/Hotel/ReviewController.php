<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReviewController extends Controller
{
    /**
     * Display reviews for the authenticated hotel
     */
    public function index(Request $request)
    {
        $hotel = Auth::guard('hotel')->user();
        $search = $request->get('q');
        $ratingFilter = $request->get('rating', 'all'); // all, 5, 4, 3, 2, 1
        $periodFilter = $request->get('period', 'all'); // all, today, this_week, this_month

        // Build the query - show all reviews for this hotel
        $query = Review::with(['tourist', 'booking'])
            ->where('hotel_id', $hotel->id);

        // Apply rating filter
        if ($ratingFilter !== 'all') {
            $query->where('rating', $ratingFilter);
        }

        // Apply period filter
        if ($periodFilter !== 'all') {
            $dateRange = $this->getDateRange($periodFilter);
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereHas('tourist', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
            });
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Get review statistics
        $stats = $this->getReviewStatistics($hotel->id);

        return view('hotel.reviews.index', compact(
            'reviews', 
            'stats', 
            'search', 
            'ratingFilter', 
            'periodFilter'
        ));
    }

    /**
     * Get review statistics for the hotel
     */
    private function getReviewStatistics($hotelId)
    {
        $totalReviews = Review::where('hotel_id', $hotelId)->count();
        
        // Average rating (all reviews)
        $averageRating = Review::where('hotel_id', $hotelId)->avg('rating');

        // Rating distribution (all reviews)
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = Review::where('hotel_id', $hotelId)
                ->where('rating', $i)
                ->count();
        }

        // Recent reviews (last 30 days)
        $recentReviews = Review::where('hotel_id', $hotelId)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();

        return [
            'total' => $totalReviews,
            'average_rating' => round($averageRating ?? 0, 1),
            'rating_distribution' => $ratingDistribution,
            'recent_reviews' => $recentReviews
        ];
    }

    /**
     * Get date range based on period
     */
    private function getDateRange($period)
    {
        $now = Carbon::now();

        return match ($period) {
            'today' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay()
            ],
            'this_week' => [
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfWeek()
            ],
            'this_month' => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth()
            ],
            default => [
                'start' => $now->copy()->startOfYear(),
                'end' => $now->copy()->endOfYear()
            ]
        };
    }
}
