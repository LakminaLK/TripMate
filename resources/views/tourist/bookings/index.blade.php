<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - TripMate</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <a href="{{ route('tourist.home') }}" 
                       class="text-blue-600 hover:text-blue-700 transition-colors">
                        <i class="fas fa-home mr-1"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        
        <!-- Success Message -->
        @if(session('success'))
        <div id="success-message" class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8 transition-all duration-500 ease-in-out">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-green-900">Booking Confirmed!</h3>
                        <p class="text-green-800">{{ session('success') }}</p>
                        @if(session('new_booking_id'))
                            <p class="text-sm text-green-700 mt-1">
                                Booking ID: <span class="font-mono font-semibold">#{{ session('new_booking_id') }}</span>
                            </p>
                        @endif
                    </div>
                </div>
                <button onclick="hideSuccessMessage()" class="text-green-600 hover:text-green-800 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        <!-- Page Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">My Bookings</h2>
            <p class="text-gray-600">Manage and view all your hotel reservations</p>
        </div>

        @if($bookings->count() > 0)
            <!-- Bookings Grid -->
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-2xl shadow-lg p-6 
                        {{ session('new_booking_id') == $booking->id ? 'ring-2 ring-green-500 bg-green-50' : '' }}">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                            
                            <!-- Booking Info -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $booking->hotel->name }}</h3>
                                        <p class="text-gray-600 text-sm mt-1">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ $booking->hotel->address }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Booking ID: <span class="font-mono">#{{ $booking->id }}</span>
                                        </p>
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <div class="flex flex-col items-end space-y-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($booking->booking_status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->booking_status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->booking_status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            <i class="fas fa-circle mr-1 text-xs"></i>
                                            {{ ucfirst($booking->booking_status) }}
                                        </span>
                                        
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($booking->payment_status === 'paid') bg-green-100 text-green-800
                                            @elseif($booking->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->payment_status === 'failed') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            <i class="fas fa-credit-card mr-1 text-xs"></i>
                                            Payment {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Booking Details -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Check-in:</span>
                                        <div class="font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Check-out:</span>
                                        <div class="font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Duration:</span>
                                        <div class="font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }} nights
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Total Amount:</span>
                                        <div class="font-semibold text-green-600 text-lg">
                                            ${{ number_format($booking->total_amount, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="lg:w-48 flex flex-col space-y-3">
                                <a href="{{ route('tourist.bookings.show', $booking->id) }}" 
                                   class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center font-medium">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Details
                                </a>
                                
                                <a href="{{ route('tourist.hotels.show', $booking->hotel->id) }}" 
                                   class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors text-center font-medium">
                                    <i class="fas fa-hotel mr-2"></i>
                                    View Hotel
                                </a>
                                
                                @if($booking->booking_status === 'confirmed' && \Carbon\Carbon::parse($booking->check_in)->isFuture())
                                <button class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors font-medium">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancel Booking
                                </button>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Room Details (if available) -->
                        @if($booking->booking_details)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">Room Details:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(json_decode($booking->booking_details, true) as $roomId => $room)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $room['roomName'] }} × {{ $room['roomCount'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
            
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-times text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Bookings Yet</h3>
                <p class="text-gray-500 mb-6">You haven't made any hotel reservations yet</p>
                <a href="{{ route('tourist.explore') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-search mr-2"></i>
                    Explore Hotels
                </a>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-gray-400">© 2025 TripMate. All rights reserved.</p>
        </div>
    </footer>

    <!-- JavaScript for Success Message Auto-Hide -->
    <script>
        function hideSuccessMessage() {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.opacity = '0';
                successMessage.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 500);
            }
        }

        // Auto-hide success message after 5 seconds
        @if(session('success'))
        setTimeout(() => {
            hideSuccessMessage();
        }, 5000);
        @endif
    </script>
</body>
</html>
