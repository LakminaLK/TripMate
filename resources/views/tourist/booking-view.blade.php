<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Bookings - TripMate</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .booking-card {
            transition: all 0.3s ease;
        }
        .booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        .status-confirmed {
            background-color: #10b981;
            color: white;
        }
        .status-pending {
            background-color: #f59e0b;
            color: white;
        }
        .status-cancelled {
            background-color: #ef4444;
            color: white;
        }
        .status-completed {
            background-color: #8b5cf6;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('tourist.explore') }}" 
                       class="text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Explore
                    </a>
                    <div class="h-6 w-px bg-gray-300"></div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Trip<span class="text-blue-600">Mate</span>
                    </h1>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="text-sm text-gray-600">
                        Welcome, {{ Auth::guard('tourist')->user()->name }}
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('tourist.home') }}" 
                           class="text-gray-600 hover:text-gray-900 transition-colors">
                            <i class="fas fa-home mr-1"></i>
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900 transition-colors">
                                <i class="fas fa-sign-out-alt mr-1"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Success Message -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">My Bookings</h2>
                    <p class="text-gray-600">Manage and view your hotel reservations</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500">
                        Total Bookings: <span class="font-semibold text-gray-900">{{ $bookings->total() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings List -->
        @if($bookings->count() > 0)
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    <div class="booking-card bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $booking->hotel->name }}</h3>
                                        <span class="status-badge status-{{ strtolower($booking->status ?? $booking->booking_status) }}">
                                            {{ ucfirst($booking->status ?? $booking->booking_status) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center text-gray-600 mb-2">
                                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                        <span>{{ $booking->hotel->location->address ?? 'Location not available' }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Booking ID: #{{ $booking->id }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900 mb-1">
                                        ${{ number_format($booking->total_amount, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Total Amount
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 py-4 border-t border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-check text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Check-in</div>
                                        <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-times text-red-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Check-out</div>
                                        <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-moon text-green-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Duration</div>
                                        <div class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }} nights
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Status -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center space-x-2">
                                        @if($booking->payment_status === 'paid')
                                            <i class="fas fa-check-circle text-green-500"></i>
                                            <span class="text-sm font-medium text-green-700">Payment Completed</span>
                                        @elseif($booking->payment_status === 'pending')
                                            <i class="fas fa-clock text-yellow-500"></i>
                                            <span class="text-sm font-medium text-yellow-700">Payment Pending</span>
                                        @else
                                            <i class="fas fa-times-circle text-red-500"></i>
                                            <span class="text-sm font-medium text-red-700">Payment Failed</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Booked on {{ $booking->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('tourist.bookings.show', $booking) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-eye mr-2"></i>
                                        View Details
                                    </a>
                                    
                                    @if($booking->status === 'completed')
                                        @if($booking->review)
                                            <!-- Edit Review Button -->
                                            <button onclick="openEditReviewModal({{ $booking->id }}, {{ json_encode($booking->review) }})"
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                                <i class="fas fa-edit mr-2"></i>
                                                Edit Review
                                            </button>
                                        @else
                                            <!-- Write Review Button -->
                                            <button onclick="openReviewModal({{ $booking->id }}, '{{ $booking->hotel->name }}')"
                                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                                                <i class="fas fa-star mr-2"></i>
                                                Write Review
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-times text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Bookings Yet</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    You haven't made any bookings yet. Start exploring amazing hotels and destinations!
                </p>
                <a href="{{ route('tourist.explore') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Explore Hotels
                </a>
            </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="text-center text-gray-600">
                <p>&copy; {{ date('Y') }} TripMate. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="reviewModalTitle" class="text-lg font-semibold text-gray-900">Write Review</h3>
                    <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="reviewForm">
                    <input type="hidden" id="reviewBookingId" name="booking_id">
                    <input type="hidden" id="reviewId" name="review_id">
                    
                    <!-- Hotel Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hotel</label>
                        <p id="reviewHotelName" class="text-gray-900 font-medium"></p>
                    </div>
                    
                    <!-- Star Rating -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                        <div class="flex items-center space-x-1">
                            <div id="starRating" class="flex space-x-1">
                                <button type="button" class="star-btn text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="1">★</button>
                                <button type="button" class="star-btn text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="2">★</button>
                                <button type="button" class="star-btn text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="3">★</button>
                                <button type="button" class="star-btn text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="4">★</button>
                                <button type="button" class="star-btn text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="5">★</button>
                            </div>
                            <span id="ratingText" class="ml-2 text-sm text-gray-600"></span>
                        </div>
                        <input type="hidden" id="ratingValue" name="rating" required>
                    </div>
                    
                    <!-- Review Title -->
                    <div class="mb-4">
                        <label for="reviewTitle" class="block text-sm font-medium text-gray-700 mb-2">Review Title *</label>
                        <input type="text" id="reviewTitle" name="title" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Summarize your experience..." required maxlength="255">
                    </div>
                    
                    <!-- Review Description -->
                    <div class="mb-6">
                        <label for="reviewDescription" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea id="reviewDescription" name="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Share your detailed experience..." required minlength="10" maxlength="1000"></textarea>
                        <div class="text-xs text-gray-500 mt-1">
                            <span id="charCount">0</span>/1000 characters
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeReviewModal()" 
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="submitReviewBtn"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Submit Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        let currentRating = 0;
        let isEditMode = false;
        
        // Rating system
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-btn');
            const ratingValue = document.getElementById('ratingValue');
            const ratingText = document.getElementById('ratingText');
            
            stars.forEach(star => {
                star.addEventListener('click', function(e) {
                    e.preventDefault();
                    const rating = parseInt(this.dataset.rating);
                    setRating(rating);
                });
                
                star.addEventListener('mouseenter', function() {
                    const rating = parseInt(this.dataset.rating);
                    highlightStars(rating);
                });
            });
            
            document.getElementById('starRating').addEventListener('mouseleave', function() {
                highlightStars(currentRating);
            });
            
            // Character count
            const description = document.getElementById('reviewDescription');
            const charCount = document.getElementById('charCount');
            
            description.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
            
            // Form submission
            document.getElementById('reviewForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitReview();
            });
        });
        
        function setRating(rating) {
            currentRating = rating;
            document.getElementById('ratingValue').value = rating;
            highlightStars(rating);
            updateRatingText(rating);
        }
        
        function highlightStars(rating) {
            const stars = document.querySelectorAll('.star-btn');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }
        
        function updateRatingText(rating) {
            const texts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
            document.getElementById('ratingText').textContent = texts[rating] || '';
        }
        
        function openReviewModal(bookingId, hotelName) {
            isEditMode = false;
            document.getElementById('reviewModalTitle').textContent = 'Write Review';
            document.getElementById('reviewBookingId').value = bookingId;
            document.getElementById('reviewId').value = '';
            document.getElementById('reviewHotelName').textContent = hotelName;
            document.getElementById('submitReviewBtn').textContent = 'Submit Review';
            
            // Reset form
            document.getElementById('reviewForm').reset();
            setRating(0);
            document.getElementById('charCount').textContent = '0';
            
            // Show modal
            document.getElementById('reviewModal').classList.remove('hidden');
        }
        
        function openEditReviewModal(bookingId, review) {
            isEditMode = true;
            document.getElementById('reviewModalTitle').textContent = 'Edit Review';
            document.getElementById('reviewBookingId').value = bookingId;
            document.getElementById('reviewId').value = review.id;
            document.getElementById('reviewHotelName').textContent = review.hotel?.name || 'Hotel';
            document.getElementById('reviewTitle').value = review.title;
            document.getElementById('reviewDescription').value = review.description;
            document.getElementById('submitReviewBtn').textContent = 'Update Review';
            
            setRating(review.rating);
            document.getElementById('charCount').textContent = review.description.length;
            
            // Show modal
            document.getElementById('reviewModal').classList.remove('hidden');
        }
        
        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
        }
        
        async function submitReview() {
            const formData = new FormData(document.getElementById('reviewForm'));
            const submitBtn = document.getElementById('submitReviewBtn');
            
            // Disable button
            submitBtn.disabled = true;
            submitBtn.textContent = isEditMode ? 'Updating...' : 'Submitting...';
            
            try {
                let url, method;
                
                if (isEditMode) {
                    const reviewId = document.getElementById('reviewId').value;
                    url = `/reviews/${reviewId}`;
                    method = 'PUT';
                } else {
                    url = '/reviews';
                    method = 'POST';
                }
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        booking_id: formData.get('booking_id'),
                        rating: parseInt(formData.get('rating')),
                        title: formData.get('title'),
                        description: formData.get('description')
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    closeReviewModal();
                    
                    // Show success message
                    showSuccessMessage(data.message);
                    
                    // Reload page to show updated review status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.error || 'Failed to submit review');
                }
                
            } catch (error) {
                console.error('Review submission error:', error);
                alert('Error submitting review: ' + error.message);
            } finally {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = isEditMode ? 'Update Review' : 'Submit Review';
            }
        }
        
        function showSuccessMessage(message) {
            // Create success alert
            const alert = document.createElement('div');
            alert.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
            alert.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(alert);
            
            // Remove after 3 seconds
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }
    </script>

</body>
</html>
