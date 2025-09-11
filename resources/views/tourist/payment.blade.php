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
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
    <style>
        [x-cloak] { display: none !important; }
        .custom-shadow { box-shadow: 0 0 50px -12px rgb(0 0 0 / 0.25); }
        
        /* Professional animations */
        .fade-in { animation: fadeIn 0.8s ease-out forwards; opacity: 0; }
        .slide-up { animation: slideUp 0.8s ease-out forwards; opacity: 0; transform: translateY(30px); }
        .slide-in-left { animation: slideInLeft 0.8s ease-out forwards; opacity: 0; transform: translateX(-50px); }
        .scale-in { animation: scaleIn 0.6s ease-out forwards; opacity: 0; transform: scale(0.9); }
        .float { animation: float 3s ease-in-out infinite; }
        
        /* Smooth transitions */
        .transition-all { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .hover-scale:hover { transform: scale(1.02); }
        
        @keyframes fadeIn { to { opacity: 1; } }
        @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }
        @keyframes slideInLeft { to { opacity: 1; transform: translateX(0); } }
        @keyframes scaleIn { to { opacity: 1; transform: scale(1); } }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        
        /* Payment Processing Animations */
        .animate-scale-in { 
            animation: scaleInModal 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards; 
        }
        .animate-bounce-in { 
            animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards; 
        }
        .animate-step-complete {
            animation: stepComplete 0.5s ease-out forwards;
        }
        
        @keyframes scaleInModal {
            0% { transform: scale(0.9); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }
        
        @keyframes stepComplete {
            0% { transform: translateX(-10px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        
        /* Pulse effect for processing */
        .animate-pulse-slow {
            animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Ripple effect */
        .animate-ripple {
            animation: ripple 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        
        @keyframes ripple {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(2.4); opacity: 0; }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #667eea; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #764ba2; }
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

    <!-- Payment Processing Overlay -->
    <div id="payment-processing-overlay" class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full mx-4 text-center shadow-2xl transform scale-95 animate-scale-in">
            <!-- Processing Stage -->
            <div id="processing-stage" class="space-y-6">
                <!-- Animated Payment Icon -->
                <div class="relative mx-auto w-24 h-24 mb-6">
                    <div class="absolute inset-0 rounded-full border-4 border-blue-200 animate-pulse"></div>
                    <div class="absolute inset-2 rounded-full border-4 border-blue-500 border-t-transparent animate-spin"></div>
                    <div class="absolute inset-4 rounded-full bg-blue-500 flex items-center justify-center">
                        <i class="fas fa-credit-card text-white text-xl"></i>
                    </div>
                </div>
                
                <!-- Processing Text -->
                <div class="space-y-3">
                    <h3 class="text-2xl font-bold text-gray-900">Processing Payment</h3>
                    <p class="text-gray-600">Please wait while we securely process your payment...</p>
                    
                    <!-- Progress Steps -->
                    <div class="space-y-2 mt-6">
                        <div id="step-validate" class="flex items-center text-sm">
                            <div class="w-4 h-4 rounded-full bg-blue-500 mr-3 flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span class="text-green-600 font-medium">Validating payment information</span>
                        </div>
                        <div id="step-process" class="flex items-center text-sm opacity-50">
                            <div class="w-4 h-4 rounded-full border-2 border-gray-300 mr-3 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse hidden"></div>
                            </div>
                            <span class="text-gray-600">Processing with secure gateway</span>
                        </div>
                        <div id="step-confirm" class="flex items-center text-sm opacity-50">
                            <div class="w-4 h-4 rounded-full border-2 border-gray-300 mr-3"></div>
                            <span class="text-gray-600">Confirming booking</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Stage -->
            <div id="success-stage" class="hidden space-y-6">
                <!-- Success Animation -->
                <div class="relative mx-auto w-24 h-24 mb-6">
                    <div class="absolute inset-0 rounded-full bg-green-500 animate-bounce-in">
                        <div class="absolute inset-0 rounded-full bg-green-500 animate-ping opacity-75"></div>
                        <div class="relative h-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-3xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Success Text -->
                <div class="space-y-3">
                    <h3 class="text-2xl font-bold text-green-600">Payment Successful!</h3>
                    <p class="text-gray-600">Your booking has been confirmed successfully.</p>
                    
                    <!-- Success Details -->
                    <div class="bg-green-50 rounded-lg p-4 mt-4">
                        <div class="flex items-center justify-center space-x-2 text-green-700">
                            <i class="fas fa-calendar-check"></i>
                            <span class="font-medium">Booking Confirmed</span>
                        </div>
                        <p class="text-sm text-green-600 mt-2">Redirecting to your booking details...</p>
                    </div>
                </div>
                
                <!-- Countdown -->
                <div class="text-sm text-gray-500">
                    Redirecting in <span id="countdown" class="font-bold text-blue-600">3</span> seconds
                </div>
            </div>
        </div>
    </div>

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

        // Form submission with payment processing animation
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            // Show payment processing overlay
            showPaymentProcessing();
            
            // Submit form data via AJAX
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showPaymentSuccess(data.booking_id);
                } else {
                    showPaymentError(data.message || 'Payment failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Payment error:', error);
                showPaymentError('An error occurred. Please try again.');
            });
        });

        // Show payment processing animation
        function showPaymentProcessing() {
            const overlay = document.getElementById('payment-processing-overlay');
            overlay.style.display = 'flex';
            
            // Animate processing steps
            setTimeout(() => animateStep('step-process'), 1500);
            setTimeout(() => animateStep('step-confirm'), 3000);
        }

        // Animate processing steps
        function animateStep(stepId) {
            const step = document.getElementById(stepId);
            const circle = step.querySelector('.w-4.h-4');
            const text = step.querySelector('span');
            const pulse = step.querySelector('.animate-pulse');
            
            // Remove opacity and show pulse
            step.classList.remove('opacity-50');
            if (pulse) pulse.classList.remove('hidden');
            
            // After animation, complete the step
            setTimeout(() => {
                circle.className = 'w-4 h-4 rounded-full bg-blue-500 mr-3 flex items-center justify-center';
                circle.innerHTML = '<i class="fas fa-check text-white text-xs"></i>';
                text.className = 'text-green-600 font-medium';
                step.classList.add('animate-step-complete');
                if (pulse) pulse.classList.add('hidden');
            }, 1000);
        }

        // Show payment success
        function showPaymentSuccess(bookingId) {
            // Clear form modification flag to prevent "leave site" warning
            formModified = false;
            
            setTimeout(() => {
                // Hide processing stage
                document.getElementById('processing-stage').style.display = 'none';
                
                // Show success stage
                const successStage = document.getElementById('success-stage');
                successStage.classList.remove('hidden');
                successStage.style.display = 'block';
                
                // Start countdown and redirect
                startCountdownAndRedirect(bookingId);
            }, 4500); // Show processing for 4.5 seconds total
        }

        // Start countdown and redirect to booking view
        function startCountdownAndRedirect(bookingId) {
            let countdown = 3;
            const countdownElement = document.getElementById('countdown');
            
            const timer = setInterval(() => {
                countdown--;
                countdownElement.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(timer);
                    // Redirect to booking details page
                    window.location.href = `/booking-details/${bookingId}`;
                }
            }, 1000);
        }

        // Show payment error
        function showPaymentError(message) {
            // Hide overlay
            document.getElementById('payment-processing-overlay').style.display = 'none';
            
            // Reset form
            const submitBtn = document.getElementById('submit-payment');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-lock mr-2"></i>Pay ${{ number_format($bookingData["total"], 2) }} - Complete Booking';
            
            // Show error message
            alert('Payment Error: ' + message);
        }

        // Redirect to landing page function
        function redirectToLanding() {
            // Create a form to POST to the clear-and-redirect route
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("tourist.payment.clear-and-redirect") }}';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        }

        // Prevent back button from showing cached payment page
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Page was loaded from cache, redirect to hotel details
                redirectToHotel();
            }
        });

        // COMPREHENSIVE BACK BUTTON PREVENTION
        // Push state to prevent back navigation
        history.pushState(null, null, location.href);
        
        // Listen for popstate events (back/forward button)
        window.addEventListener('popstate', function(event) {
            // Push state again to prevent going back
            history.pushState(null, null, location.href);
            
            // Redirect to landing page for better UX
            window.location.replace('{{ route("landing") }}');
        });

        // Prevent browser history navigation entirely
        window.addEventListener('beforeunload', function() {
            // Replace current history entry to prevent back navigation
            history.replaceState(null, null, location.href);
        });

        // Warn before leaving page if payment form has been modified
        let formModified = false;
        const formInputs = document.querySelectorAll('#payment-form input, #payment-form select');
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                formModified = true;
            });
        });

        window.addEventListener('beforeunload', function(e) {
            if (formModified) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });

        // Strict refresh detection - immediate redirect to landing page
        (function() {
            // Use performance API to detect page refresh
            if (performance.navigation && performance.navigation.type === performance.navigation.TYPE_RELOAD) {
                // This is a page refresh - immediately redirect to landing page
                sessionStorage.clear(); // Clear all session storage
                window.location.replace('{{ route("landing") }}');
                return;
            }
            
            // Fallback: Use sessionStorage to detect refresh
            if (sessionStorage.getItem('payment-page-accessed')) {
                // Page was accessed before - this is likely a refresh
                sessionStorage.clear();
                window.location.replace('{{ route("landing") }}');
                return;
            }
            
            // Mark this page as accessed
            sessionStorage.setItem('payment-page-accessed', 'true');
            
            // Clear marker when leaving page normally
            window.addEventListener('beforeunload', function() {
                // Only clear if not refreshing
                if (!performance.navigation || performance.navigation.type !== performance.navigation.TYPE_RELOAD) {
                    sessionStorage.removeItem('payment-page-accessed');
                }
            });
        })();

        // Handle browser back/forward buttons - redirect to landing
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Page was loaded from cache (back/forward button)
                sessionStorage.clear();
                window.location.replace('{{ route("landing") }}');
            }
        });

        // Additional security: prevent right-click and F12
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        
        document.addEventListener('keydown', function(e) {
            // Prevent F12, Ctrl+Shift+I, Ctrl+U, F5, Ctrl+R
            if (e.key === 'F12' || 
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.key === 'u') ||
                e.key === 'F5' ||
                (e.ctrlKey && e.key === 'r')) {
                e.preventDefault();
                // Redirect to landing page
                sessionStorage.clear();
                window.location.replace('{{ route("landing") }}');
            }
        });
    </script>
</body>
</html>
