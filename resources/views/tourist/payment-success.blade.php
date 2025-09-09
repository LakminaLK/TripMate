<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - TripMate</title>
    
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
                <h1 class="text-2xl font-bold text-gray-900">
                    Trip<span class="text-blue-600">Mate</span>
                </h1>
                
                <a href="{{ route('tourist.home') }}" 
                   class="text-blue-600 hover:text-blue-700 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Go to Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-12">
        
        <!-- Success Message -->
        <div class="text-center mb-12">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>
            
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Payment Successful!</h1>
            <p class="text-xl text-gray-600 mb-2">Your booking has been confirmed</p>
            <p class="text-gray-500">Booking ID: <span class="font-mono font-semibold">#{{ $booking->id }}</span></p>
        </div>

        <!-- Booking Details Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-hotel text-blue-600 mr-3"></i>
                Booking Details
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Hotel Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Hotel Information</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-600">Hotel:</span>
                            <span class="font-semibold text-gray-900 ml-2">{{ $booking->hotel->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Address:</span>
                            <span class="text-gray-900 ml-2">{{ $booking->hotel->address }}</span>
                        </div>
                        @if($booking->hotel->phone)
                        <div>
                            <span class="text-gray-600">Phone:</span>
                            <span class="text-gray-900 ml-2">{{ $booking->hotel->phone }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Booking Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Information</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-600">Check-in:</span>
                            <span class="font-semibold text-gray-900 ml-2">
                                {{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Check-out:</span>
                            <span class="font-semibold text-gray-900 ml-2">
                                {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Duration:</span>
                            <span class="text-gray-900 ml-2">
                                {{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }} nights
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-receipt text-blue-600 mr-3"></i>
                Payment Summary
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-semibold text-green-600 ml-2 text-xl">
                                ${{ number_format($booking->total_amount, 2) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Payment Method:</span>
                            <span class="text-gray-900 ml-2 capitalize">
                                <i class="fas fa-credit-card mr-1"></i>
                                {{ $booking->payment_method }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Payment Status:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Transaction Date:</span>
                            <span class="text-gray-900 ml-2">
                                {{ $booking->created_at->format('M d, Y h:i A') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Room Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Room Details</h3>
                    <div class="space-y-3">
                        @if($booking->booking_details)
                            @foreach(json_decode($booking->booking_details, true) as $roomId => $room)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-medium text-gray-900">{{ $room['roomName'] }}</h4>
                                        <span class="text-sm font-semibold text-gray-900">
                                            ${{ number_format($room['subtotal'], 2) }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ $room['roomCount'] }} {{ $room['roomCount'] == 1 ? 'room' : 'rooms' }} × 
                                        {{ $room['nights'] }} {{ $room['nights'] == 1 ? 'night' : 'nights' }}
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('tourist.hotels.show', $booking->hotel->id) }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Hotel
            </a>
            
            <button onclick="window.print()" 
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-print mr-2"></i>
                Print Confirmation
            </button>
            
            <a href="{{ route('tourist.home') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-home mr-2"></i>
                Go to Dashboard
            </a>
        </div>

        <!-- Important Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Important Information
            </h3>
            <ul class="text-blue-800 space-y-2 text-sm">
                <li class="flex items-start">
                    <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                    Please arrive at the hotel on your check-in date with a valid ID
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                    A confirmation email has been sent to your registered email address
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                    For any changes or cancellations, please contact the hotel directly
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                    Keep this confirmation for your records
                </li>
            </ul>
        </div>
        
        <!-- Action Buttons -->
        <div class="text-center space-y-4 mb-8">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('tourist.bookings.view') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-calendar-check mr-2"></i>
                    View My Bookings
                </a>
                
                <a href="{{ route('tourist.home') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-8 py-3 rounded-lg font-semibold transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-home mr-2"></i>
                    Back to Dashboard
                </a>
                
                <button onclick="window.print()" 
                        class="bg-green-100 hover:bg-green-200 text-green-700 px-8 py-3 rounded-lg font-semibold transition-colors inline-flex items-center justify-center no-print">
                    <i class="fas fa-print mr-2"></i>
                    Print Confirmation
                </button>
            </div>
        </div>
    </main>

    <!-- Print Styles -->
    <style>
        @media print {
            body { 
                background: white; 
                -webkit-print-color-adjust: exact; 
            }
            .no-print { 
                display: none; 
            }
        }
    </style>
</body>
</html>
