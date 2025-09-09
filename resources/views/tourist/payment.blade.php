<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - TripMate</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    
    
    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-6 py-8">
        
        <!-- Payment Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Booking</h2>
            <p class="text-gray-600">Secure payment for your stay at {{ $hotel->name }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column - Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    
                    <!-- Payment Form -->
                    <form action="{{ route('tourist.payment.process') }}" method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="booking_data" value="{{ json_encode($bookingData) }}">
                        
                        <!-- Payment Method Section -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-credit-card text-blue-600 mr-3"></i>
                                Payment Method
                            </h3>
                            
                            <div class="space-y-4">
                                <!-- Card Selection -->
                                <div class="border border-blue-200 rounded-lg p-4 bg-blue-50">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="payment_method" value="card" checked class="text-blue-600 focus:ring-blue-500">
                                        <div class="ml-3 flex items-center">
                                            <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                                            <span class="font-medium text-gray-900">Credit/Debit Card</span>
                                        </div>
                                    </label>
                                    <div class="flex space-x-2 mt-2 ml-6">
                                        <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                                        <i class="fab fa-cc-mastercard text-2xl text-red-500"></i>
                                        <i class="fab fa-cc-amex text-2xl text-blue-500"></i>
                                        <i class="fab fa-cc-discover text-2xl text-orange-500"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Details Section -->
                        <div class="mb-8" id="card-details">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                                Card Details
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Card Number -->
                                <div class="md:col-span-2">
                                    <label for="card_number" class="block text-sm font-medium text-gray-700 mb-2">
                                        Card Number
                                    </label>
                                    <input type="text" 
                                           id="card_number" 
                                           name="card_number" 
                                           placeholder="1234 5678 9012 3456"
                                           maxlength="19"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           required>
                                    @error('card_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Cardholder Name -->
                                <div class="md:col-span-2">
                                    <label for="card_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Cardholder Name
                                    </label>
                                    <input type="text" 
                                           id="card_name" 
                                           name="card_name" 
                                           placeholder="John Doe"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           required>
                                    @error('card_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Expiry Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Expiry Date
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <select name="expiry_month" 
                                                class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                            <option value="">Month</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                            @endfor
                                        </select>
                                        <select name="expiry_year" 
                                                class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                            <option value="">Year</option>
                                            @for($i = date('Y'); $i <= date('Y') + 15; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    @error('expiry_month')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    @error('expiry_year')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- CVV -->
                                <div>
                                    <label for="cvv" class="block text-sm font-medium text-gray-700 mb-2">
                                        CVV
                                    </label>
                                    <input type="text" 
                                           id="cvv" 
                                           name="cvv" 
                                           placeholder="123"
                                           maxlength="3"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           required>
                                    @error('cvv')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Billing Information -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-3"></i>
                                Billing Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Billing Address -->
                                <div class="md:col-span-2">
                                    <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                                        Address
                                    </label>
                                    <input type="text" 
                                           id="billing_address" 
                                           name="billing_address" 
                                           placeholder="123 Main Street"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           required>
                                    @error('billing_address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- City -->
                                <div>
                                    <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-2">
                                        City
                                    </label>
                                    <input type="text" 
                                           id="billing_city" 
                                           name="billing_city" 
                                           placeholder="Colombo"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           required>
                                    @error('billing_city')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Postal Code -->
                                <div>
                                    <label for="billing_postal" class="block text-sm font-medium text-gray-700 mb-2">
                                        Postal Code
                                    </label>
                                    <input type="text" 
                                           id="billing_postal" 
                                           name="billing_postal" 
                                           placeholder="00100"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                           required>
                                    @error('billing_postal')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Terms & Submit -->
                        <div class="space-y-6">
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" 
                                       id="terms" 
                                       class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       required>
                                <label for="terms" class="text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a> 
                                    and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                                </label>
                            </div>
                            
                            <button type="submit" 
                                    id="submit-payment"
                                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-semibold text-lg">
                                <i class="fas fa-lock mr-2"></i>
                                Pay ${{ number_format($bookingData['total'], 2) }} - Complete Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column - Booking Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-receipt text-blue-600 mr-3"></i>
                        Booking Summary
                    </h3>
                    
                    <!-- Hotel Info -->
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <h4 class="font-semibold text-gray-900">{{ $hotel->name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $hotel->address }}
                        </p>
                    </div>
                    
                    <!-- Dates -->
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Check-in:</span>
                                <div class="font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($bookingData['check_in'])->format('M d, Y') }}
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-600">Check-out:</span>
                                <div class="font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($bookingData['check_out'])->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 text-sm">
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-semibold text-gray-900 ml-1">
                                {{ $bookingData['nights'] }} {{ $bookingData['nights'] == 1 ? 'night' : 'nights' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Room Details -->
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <h5 class="font-semibold text-gray-900 mb-3">Selected Rooms</h5>
                        <div class="space-y-3">
                            @foreach($bookingData['rooms'] as $roomId => $room)
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex justify-between items-start mb-2">
                                        <h6 class="font-medium text-gray-900">{{ $room['roomName'] }}</h6>
                                        <span class="text-sm font-semibold text-gray-900">
                                            ${{ number_format($room['subtotal'], 2) }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        {{ $room['roomCount'] }} {{ $room['roomCount'] == 1 ? 'room' : 'rooms' }} × 
                                        {{ $room['nights'] }} {{ $room['nights'] == 1 ? 'night' : 'nights' }} × 
                                        ${{ number_format($room['pricePerNight'], 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Total -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-lg font-bold text-gray-900">
                            <span>Total Amount:</span>
                            <span class="text-green-600">${{ number_format($bookingData['total'], 2) }}</span>
                        </div>
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Secure payment processed by TripMate
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript -->
    <script>
        // Card number formatting
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        // CVV validation
        document.getElementById('cvv').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });

        // Form submission
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submit-payment');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing Payment...';
        });
    </script>
</body>
</html>
