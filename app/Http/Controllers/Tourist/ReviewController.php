<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Store a new review for a completed booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|between:1,5',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10|max:1000'
        ]);

        try {
            $booking = Booking::with('hotel')->findOrFail($request->booking_id);
            
            // Verify the booking belongs to the authenticated tourist
            if ($booking->tourist_id !== Auth::guard('tourist')->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Verify the booking is completed
            if ($booking->status !== 'completed') {
                return response()->json(['error' => 'Can only review completed bookings'], 400);
            }

            // Check if review already exists
            if (Review::where('booking_id', $booking->id)->exists()) {
                return response()->json(['error' => 'Review already exists for this booking'], 400);
            }

            // Create the review
            $review = Review::create([
                'booking_id' => $booking->id,
                'tourist_id' => $booking->tourist_id,
                'hotel_id' => $booking->hotel_id,
                'rating' => $request->rating,
                'title' => $request->title,
                'description' => $request->description,
                'is_approved' => true
            ]);

            Log::info('Review created successfully', [
                'review_id' => $review->id,
                'booking_id' => $booking->id,
                'tourist_id' => $booking->tourist_id,
                'hotel_id' => $booking->hotel_id,
                'rating' => $request->rating
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully!',
                'review' => $review
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating review', [
                'error' => $e->getMessage(),
                'booking_id' => $request->booking_id,
                'tourist_id' => Auth::guard('tourist')->id()
            ]);

            return response()->json(['error' => 'Failed to submit review'], 500);
        }
    }

    /**
     * Get review for a specific booking
     */
    public function show($bookingId)
    {
        try {
            $booking = Booking::with(['hotel', 'review'])
                ->where('id', $bookingId)
                ->where('tourist_id', Auth::guard('tourist')->id())
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'review' => $booking->review,
                'can_review' => $booking->status === 'completed' && !$booking->review
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching review', [
                'error' => $e->getMessage(),
                'booking_id' => $bookingId,
                'tourist_id' => Auth::guard('tourist')->id()
            ]);

            return response()->json(['error' => 'Review not found'], 404);
        }
    }

    /**
     * Update an existing review
     */
    public function update(Request $request, $reviewId)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10|max:1000'
        ]);

        try {
            $review = Review::with('booking')
                ->where('id', $reviewId)
                ->where('tourist_id', Auth::guard('tourist')->id())
                ->firstOrFail();

            // Update the review
            $review->update([
                'rating' => $request->rating,
                'title' => $request->title,
                'description' => $request->description
            ]);

            Log::info('Review updated successfully', [
                'review_id' => $review->id,
                'booking_id' => $review->booking_id,
                'rating' => $request->rating
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully!',
                'review' => $review
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating review', [
                'error' => $e->getMessage(),
                'review_id' => $reviewId,
                'tourist_id' => Auth::guard('tourist')->id()
            ]);

            return response()->json(['error' => 'Failed to update review'], 500);
        }
    }

    /**
     * Delete a review
     */
    public function destroy($reviewId)
    {
        try {
            $review = Review::where('id', $reviewId)
                ->where('tourist_id', Auth::guard('tourist')->id())
                ->firstOrFail();

            $review->delete();

            Log::info('Review deleted successfully', [
                'review_id' => $reviewId,
                'tourist_id' => Auth::guard('tourist')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting review', [
                'error' => $e->getMessage(),
                'review_id' => $reviewId,
                'tourist_id' => Auth::guard('tourist')->id()
            ]);

            return response()->json(['error' => 'Failed to delete review'], 500);
        }
    }
}
