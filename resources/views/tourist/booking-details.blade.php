<!DOCTYPE html>
<html lang="en">
<x-tourist.head title="Booking Receipt - TripMate" />
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/30 min-h-screen">
    
    @php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
    @endphp

    <!-- Professional Navbar -->
    <x-tourist.header />

    <!-- Main Content with top padding for fixed header -->
    <div class="pt-20">
        <!-- Simple Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-8 mb-6 no-print">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h1 class="text-3xl font-bold mb-2">Booking Receipt</h1>
                <p class="text-blue-100">Booking Reference: {{ $booking->booking_reference ?? 'BK' . str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-6 pb-16">
            
            <!-- Screen Display -->
            <div class="no-print">
                <!-- Basic Info Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6 interactive-card hover-lift transition-all fade-in">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $booking->hotel->name }}</h2>
                            <p class="text-gray-600">{{ $booking->hotel->address }}</p>
                        </div>
                        <div class="text-right mt-4 md:mt-0">
                            <p class="text-3xl font-bold text-green-600">${{ number_format($booking->total_amount, 2) }}</p>
                            <p class="text-sm text-gray-500">Total Amount</p>
                        </div>
                    </div>
                    
                    <!-- Status Badges -->
                    <div class="flex flex-wrap gap-3 mb-4">
                        <span class="status-badge status-{{ strtolower($booking->status ?? $booking->booking_status) }} px-4 py-2 rounded-full text-sm font-semibold transition-all hover:scale-105">
                            {{ ucfirst($booking->status ?? $booking->booking_status) }}
                        </span>
                        <span class="status-badge @if(($booking->status ?? $booking->booking_status) === 'cancelled') status-completed @elseif($booking->payment_status === 'paid') status-confirmed @else status-pending @endif px-4 py-2 rounded-full text-sm font-semibold transition-all hover:scale-105">
                            @if(($booking->status ?? $booking->booking_status) === 'cancelled')
                                Refunded
                            @else
                                Payment {{ ucfirst($booking->payment_status) }}
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Stay Details -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6 interactive-card hover-lift transition-all slide-up">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Stay Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg hover-scale transition-all">
                            <p class="text-sm text-blue-600 font-medium">Check-in</p>
                            <p class="text-lg font-bold text-blue-900">{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</p>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg hover-scale transition-all">
                            <p class="text-sm text-red-600 font-medium">Check-out</p>
                            <p class="text-lg font-bold text-red-900">{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg hover-scale transition-all">
                            <p class="text-sm text-green-600 font-medium">Duration</p>
                            <p class="text-lg font-bold text-green-900">{{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }} nights</p>
                        </div>
                    </div>
                </div>

                <!-- Room & Payment Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Room Details -->
                    @if($booking->booking_details)
                    <div class="bg-white rounded-xl shadow-lg p-6 interactive-card hover-lift transition-all slide-in-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Room Details</h3>
                        @foreach(json_decode($booking->booking_details, true) as $roomId => $room)
                            <div class="bg-gray-50 rounded-lg p-4 mb-3 hover-scale transition-all">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $room['roomName'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $room['roomCount'] }} room(s) × {{ $room['nights'] }} night(s)</p>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900">${{ number_format($room['subtotal'], 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <!-- Payment Info -->
                    <div class="bg-white rounded-xl shadow-lg p-6 interactive-card hover-lift transition-all scale-in">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Payment Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between hover:bg-gray-50 p-2 rounded transition-all">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-semibold">{{ $booking->payment_method ?? 'Credit Card' }}</span>
                            </div>
                            @if($booking->payment_details)
                                @php $paymentDetails = json_decode($booking->payment_details, true); @endphp
                                @if(isset($paymentDetails['card_last_four']))
                                <div class="flex justify-between hover:bg-gray-50 p-2 rounded transition-all">
                                    <span class="text-gray-600">Card:</span>
                                    <span class="font-semibold">**** {{ $paymentDetails['card_last_four'] }}</span>
                                </div>
                                @endif
                            @endif
                            <div class="flex justify-between hover:bg-gray-50 p-2 rounded transition-all">
                                <span class="text-gray-600">Booking Date:</span>
                                <span class="font-semibold">{{ $booking->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center space-y-4">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button onclick="printReceipt()" 
                                class="btn-enhanced inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover-lift">
                            <i class="fas fa-print mr-2"></i>
                            Print Receipt
                        </button>
                        
                        <button onclick="copyToClipboard('{{ $booking->booking_reference ?? 'BK' . str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}', this)"
                                class="btn-enhanced inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-teal-700 transition-all duration-300 shadow-lg hover-lift">
                            <i class="fas fa-copy mr-2"></i>
                            Copy Reference
                        </button>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('tourist.bookings.view') }}" 
                           class="btn-enhanced inline-flex items-center justify-center px-6 py-2 border-2 border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-600 hover:text-white transition-all duration-300">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to My Bookings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Print Receipt Format -->
            <div class="print-only hidden">
                <div class="receipt-container">
                    <!-- Receipt Header -->
                    <div class="receipt-header">
                        <div class="receipt-title">TRIPMATE</div>
                        <div class="receipt-subtitle">Booking Receipt</div>
                        <div style="font-size: 10px; margin-top: 5px;">
                            {{ now()->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <!-- Basic Info -->
                    <div class="receipt-section">
                        <div class="receipt-row">
                            <span class="receipt-label">REF:</span>
                            <span>{{ $booking->booking_reference ?? 'BK' . str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="receipt-row">
                            <span class="receipt-label">STATUS:</span>
                            <span>{{ strtoupper($booking->status ?? $booking->booking_status) }}</span>
                        </div>
                    </div>

                    <!-- Hotel -->
                    <div class="receipt-section">
                        <div style="font-weight: bold; margin-bottom: 5px;">{{ $booking->hotel->name }}</div>
                        <div style="font-size: 10px;">{{ $booking->hotel->address }}</div>
                    </div>

                    <!-- Stay -->
                    <div class="receipt-section">
                        <div class="receipt-row">
                            <span>Check-in:</span>
                            <span>{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</span>
                        </div>
                        <div class="receipt-row">
                            <span>Check-out:</span>
                            <span>{{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</span>
                        </div>
                        <div class="receipt-row">
                            <span>Nights:</span>
                            <span>{{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }}</span>
                        </div>
                    </div>

                    <!-- Rooms -->
                    @if($booking->booking_details)
                    <div class="receipt-section">
                        @foreach(json_decode($booking->booking_details, true) as $roomId => $room)
                        <div style="margin-bottom: 5px;">
                            <div>{{ $room['roomName'] }}</div>
                            <div class="receipt-row">
                                <span>{{ $room['roomCount'] }}x {{ $room['nights'] }} nights</span>
                                <span>${{ number_format($room['subtotal'], 2) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Total -->
                    <div class="receipt-total">
                        <div class="receipt-row">
                            <span class="receipt-label">TOTAL:</span>
                            <span>${{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                        <div style="margin-top: 5px; font-size: 10px;">
                            @if(($booking->status ?? $booking->booking_status) === 'cancelled')
                                Payment: Refunded
                            @else
                                Payment: {{ $booking->payment_status }}
                            @endif
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="receipt-footer">
                        <div>Thank you for choosing TripMate!</div>
                        <div style="font-size: 8px; margin-top: 5px;">
                            www.tripmate.com
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <x-tourist.footer />

<!-- Enhanced JavaScript for Interactive Features -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for navbar links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Copy to clipboard functionality
        window.copyToClipboard = function(text, element) {
            navigator.clipboard.writeText(text).then(function() {
                // Visual feedback
                const originalText = element.innerHTML;
                element.innerHTML = '<i class="fas fa-check text-green-500"></i> Copied!';
                element.classList.add('copy-success');
                
                setTimeout(() => {
                    element.innerHTML = originalText;
                    element.classList.remove('copy-success');
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                const originalText = element.innerHTML;
                element.innerHTML = '<i class="fas fa-check text-green-500"></i> Copied!';
                setTimeout(() => {
                    element.innerHTML = originalText;
                }, 2000);
            });
        };
        
        // Enhanced print functionality
        window.printReceipt = function() {
            window.print();
        };
        
        // Add animation delays to elements
        const animatedElements = document.querySelectorAll('.fade-in, .slide-up, .slide-in-left, .scale-in');
        animatedElements.forEach((element, index) => {
            element.style.animationDelay = `${index * 0.1}s`;
        });
        
        // Status badge hover effects
        const statusBadges = document.querySelectorAll('.status-badge');
        statusBadges.forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });
            
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
        
        // Card hover effects
        const interactiveCards = document.querySelectorAll('.interactive-card');
        interactiveCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.zIndex = '10';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.zIndex = '1';
            });
        });
        
        // Navbar background on scroll
        const navbar = document.querySelector('header');
        if (navbar) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-glass');
                    navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                } else {
                    navbar.classList.remove('navbar-glass');
                    navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
                }
            });
        }
        
        // Enhanced button interactions
        const enhancedButtons = document.querySelectorAll('.btn-enhanced');
        enhancedButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Create ripple effect
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
        
        // Auto-hide success messages
        const successMessages = document.querySelectorAll('.alert-success');
        successMessages.forEach(message => {
            setTimeout(() => {
                message.style.opacity = '0';
                setTimeout(() => {
                    message.remove();
                }, 300);
            }, 5000);
        });
    });
    
    // Alpine.js global data
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingDetails', () => ({
            showFullDescription: false,
            
            toggleDescription() {
                this.showFullDescription = !this.showFullDescription;
            },
            
            formatCurrency(amount) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(amount);
            }
        }));
    });
</script>

<!-- Additional CSS for ripple effect -->
<style>
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
</style>

</body>
</html>