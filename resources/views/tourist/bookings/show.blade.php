<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - TripMate</title>
    
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
                    <a href="{{ route('tourist.bookings.index') }}" 
                       class="text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Bookings
                    </a>
                    <div class="h-6 w-px bg-gray-300"></div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Trip<span class="text-blue-600">Mate</span>
                    </h1>
                </div>
                
                <div class="text-sm text-gray-600">
                    Booking #{{ $booking->id }}
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-8">
        
        <!-- Booking Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Booking Details</h2>
                <p class="text-gray-600">Booking ID: <span class="font-mono font-semibold">#{{ $booking->id }}</span></p>
            </div>
            
            <!-- Status Badges -->
            <div class="flex justify-center space-x-4 mb-6">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @if($booking->booking_status === 'confirmed') bg-green-100 text-green-800
                    @elseif($booking->booking_status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800 @endif">
                    <i class="fas fa-circle mr-2 text-xs"></i>
                    {{ ucfirst($booking->booking_status) }}
                </span>
                
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @if($booking->payment_status === 'paid') bg-green-100 text-green-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    <i class="fas fa-credit-card mr-2"></i>
                    Payment {{ ucfirst($booking->payment_status) }}
                </span>
            </div>
        </div>

        <!-- Hotel & Booking Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Hotel Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-hotel text-blue-600 mr-3"></i>
                    Hotel Information
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-gray-600">Hotel Name:</span>
                        <div class="font-semibold text-gray-900">{{ $booking->hotel->name }}</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Address:</span>
                        <div class="text-gray-900">{{ $booking->hotel->address }}</div>
                    </div>
                    @if($booking->hotel->phone)
                    <div>
                        <span class="text-gray-600">Phone:</span>
                        <div class="text-gray-900">{{ $booking->hotel->phone }}</div>
                    </div>
                    @endif
                    @if($booking->hotel->email)
                    <div>
                        <span class="text-gray-600">Email:</span>
                        <div class="text-gray-900">{{ $booking->hotel->email }}</div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Booking Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calendar text-blue-600 mr-3"></i>
                    Booking Information
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-gray-600">Check-in Date:</span>
                        <div class="font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($booking->check_in)->format('l, F d, Y') }}
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-600">Check-out Date:</span>
                        <div class="font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($booking->check_out)->format('l, F d, Y') }}
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-600">Duration:</span>
                        <div class="text-gray-900">
                            {{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }} nights
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-600">Booking Date:</span>
                        <div class="text-gray-900">
                            {{ $booking->created_at->format('F d, Y h:i A') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Details -->
        @if($booking->booking_details)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-bed text-blue-600 mr-3"></i>
                Room Details
            </h3>
            
            <div class="space-y-4">
                @foreach(json_decode($booking->booking_details, true) as $roomId => $room)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-900">{{ $room['roomName'] }}</h4>
                            <span class="text-lg font-bold text-gray-900">
                                ${{ number_format($room['subtotal'], 2) }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $room['roomCount'] }} {{ $room['roomCount'] == 1 ? 'room' : 'rooms' }} × 
                            {{ $room['nights'] }} {{ $room['nights'] == 1 ? 'night' : 'nights' }} × 
                            ${{ number_format($room['pricePerNight'], 2) }} per night
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Payment Information -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-receipt text-blue-600 mr-3"></i>
                Payment Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div>
                        <span class="text-gray-600">Total Amount:</span>
                        <div class="text-2xl font-bold text-green-600">
                            ${{ number_format($booking->total_amount, 2) }}
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-600">Payment Method:</span>
                        <div class="text-gray-900 capitalize">
                            <i class="fas fa-credit-card mr-1"></i>
                            {{ $booking->payment_method ?? 'Card' }}
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-gray-600">Payment Status:</span>
                        <div class="font-semibold text-gray-900 capitalize">
                            {{ $booking->payment_status }}
                        </div>
                    </div>
                    @if($booking->payment_details)
                        @php $paymentDetails = json_decode($booking->payment_details, true); @endphp
                        @if(isset($paymentDetails['card_last_four']))
                        <div>
                            <span class="text-gray-600">Card Used:</span>
                            <div class="text-gray-900">
                                **** **** **** {{ $paymentDetails['card_last_four'] }}
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('tourist.hotels.show', $booking->hotel->id) }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-hotel mr-2"></i>
                View Hotel
            </a>
            
            <button onclick="window.print()" 
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-print mr-2"></i>
                Print Details
            </button>
            
            <a href="{{ route('tourist.bookings.index') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-list mr-2"></i>
                All Bookings
            </a>
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
