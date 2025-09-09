@extends('tourist.layouts.app')

@section('title', 'Write a Review')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Write a Review</h1>
            <p class="text-gray-600">Share your experience with other travelers</p>
        </div>

        <!-- Review Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking }}">

                <!-- Rating -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Overall Rating</label>
                    <div class="flex items-center space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-rating text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none" data-rating="{{ $i }}">
                                ⭐
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating" value="">
                </div>

                <!-- Review Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Review Title</label>
                    <input type="text" id="title" name="title" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                           placeholder="Summarize your experience">
                </div>

                <!-- Review Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                    <textarea id="content" name="content" rows="6" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" 
                              placeholder="Tell us about your stay..."></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.star-rating').forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.dataset.rating;
        document.getElementById('rating').value = rating;
        
        // Update star display
        document.querySelectorAll('.star-rating').forEach((s, index) => {
            if (index < rating) {
                s.classList.remove('text-gray-300');
                s.classList.add('text-yellow-400');
            } else {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-gray-300');
            }
        });
    });
});
</script>
@endsection
