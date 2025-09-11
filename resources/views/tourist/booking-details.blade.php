<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt - TripMate</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Professional animations */
        .fade-in { animation: fadeIn 0.8s ease-out forwards; opacity: 0; }
        .slide-up { animation: slideUp 0.8s ease-out forwards; opacity: 0; transform: translateY(30px); }
        .slide-in-left { animation: slideInLeft 0.8s ease-out forwards; opacity: 0; transform: translateX(-50px); }
        .scale-in { animation: scaleIn 0.6s ease-out forwards; opacity: 0; transform: scale(0.9); }
        .float { animation: float 3s ease-in-out infinite; }
        
        /* Smooth transitions */
        .transition-all { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 25px 50px rgba(0,0,0,0.15); }
        .hover-scale:hover { transform: scale(1.02); }
        
        /* Professional gradients */
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass { backdrop-filter: blur(16px); background: rgba(255, 255, 255, 0.1); }
        
        @keyframes fadeIn { to { opacity: 1; } }
        @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }
        @keyframes slideInLeft { to { opacity: 1; transform: translateX(0); } }
        @keyframes scaleIn { to { opacity: 1; transform: scale(1); } }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #667eea; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #764ba2; }
        
        /* Interactive Elements */
        .interactive-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .interactive-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        /* Status badge animations */
        .status-badge {
            position: relative;
            overflow: hidden;
        }
        
        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .status-badge:hover::before {
            left: 100%;
        }
        
        /* Button enhancements */
        .btn-enhanced {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-enhanced:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-enhanced:hover:before {
            left: 100%;
        }
        
        .btn-enhanced:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        /* Navbar enhancement */
        .navbar-glass {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Copy animation */
        .copy-success {
            animation: copySuccess 0.3s ease-out;
        }
        
        @keyframes copySuccess {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Print Styles for Receipt Format */
        @media print {
            body { 
                background: white !important; 
                -webkit-print-color-adjust: exact; 
                font-family: 'Courier New', monospace;
                font-size: 12px;
                line-height: 1.4;
                margin: 0;
                padding: 0;
            }
            
            .no-print { 
                display: none !important; 
            }
            
            .print-only {
                display: block !important;
            }
            
            .receipt-container {
                width: 80mm !important;
                max-width: 80mm !important;
                margin: 0 auto;
                padding: 10mm;
                background: white;
                border: none;
                box-shadow: none;
            }
            
            .receipt-header {
                text-align: center;
                border-bottom: 2px dashed #000;
                padding-bottom: 10px;
                margin-bottom: 15px;
            }
            
            .receipt-title {
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 5px;
            }
            
            .receipt-subtitle {
                font-size: 10px;
                margin-bottom: 10px;
            }
            
            .receipt-section {
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 1px dashed #ccc;
            }
            
            .receipt-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 3px;
            }
            
            .receipt-label {
                font-weight: bold;
            }
            
            .receipt-total {
                border-top: 2px solid #000;
                padding-top: 8px;
                margin-top: 10px;
                font-weight: bold;
                font-size: 14px;
            }
            
            .receipt-footer {
                text-align: center;
                margin-top: 20px;
                font-size: 10px;
                border-top: 2px dashed #000;
                padding-top: 10px;
            }
            
            .qr-code {
                width: 50px;
                height: 50px;
                margin: 10px auto;
                border: 1px solid #000;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 8px;
            }
        }
        
        /* Screen Styles */
        .receipt-preview {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border: 2px dashed #e5e7eb;
            border-radius: 12px;
            padding: 2rem;
            font-family: 'Courier New', monospace;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .status-confirmed { background: linear-gradient(135deg, #10b981, #059669); color: white; }
        .status-pending { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
        .status-cancelled { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
        .status-completed { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/30 min-h-screen">
    
    @php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
    @endphp

    <!-- Professional Navbar -->
    <header x-data="{ isOpen: false, scrolled: false }" 
            @scroll.window="scrolled = window.pageYOffset > 50"
            :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg' : 'bg-white/95 backdrop-blur-md shadow-lg'"
            class="fixed top-0 w-full z-50 transition-all duration-300 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo & Brand -->
                <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl blur opacity-75 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative bg-white p-2 rounded-xl">
                            <img src="{{ asset('images/logoo.png') }}" alt="TripMate" class="h-8 w-8">
                        </div>
                    </div>
                <div>
                    <h1 :class="scrolled ? 'text-gray-900' : 'text-gray-900'" 
                        class="text-xl font-bold transition-colors">
                        Trip<span class="text-blue-600">Mate</span>
                    </h1>
                    <p :class="scrolled ? 'text-gray-500' : 'text-gray-500'" 
                       class="text-xs transition-colors">Your Travel Companion</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('landing') }}" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-gray-700 hover:text-blue-600'"
                   class="font-medium transition-colors relative group">
                    Home
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#about" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-gray-700 hover:text-blue-600'"
                   class="font-medium transition-colors relative group">
                    About
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="{{ route('tourist.explore') }}" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-gray-700 hover:text-blue-600'"
                   class="font-medium transition-colors relative group">
                    Explore
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="{{ route('emergency-services.index') }}" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-gray-700 hover:text-blue-600'"
                   class="font-medium transition-colors relative group">
                    Emergency
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#contact" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-gray-700 hover:text-blue-600'"
                   class="font-medium transition-colors relative group">
                    Contact us
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
            </nav>

            <!-- Auth Section -->
            <div class="flex items-center space-x-4">
                @if ($tourist)
                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative" @click.away="open = false">
                        <button @click="open = !open"
                                :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-gray-700 hover:text-blue-600'"
                                class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-white/10 transition-all duration-300">
                            <i class="fas fa-user-circle text-2xl"></i>
                        </button>                            <div x-show="open" x-transition
                                 class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                                <div class="p-4 bg-gradient-to-r from-blue-50 to-purple-50">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-12 w-12 rounded-full overflow-hidden flex items-center justify-center bg-white shadow-inner">
                                            @if($tourist->profile_photo_path)
                                                <img src="{{ asset('storage/' . $tourist->profile_photo_path) }}" 
                                                     alt="{{ $tourist->name }}" 
                                                     class="h-full w-full object-cover">
                                            @else
                                                <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold h-full w-full flex items-center justify-center text-xl">
                                                    {{ strtoupper(substr($tourist->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $tourist->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $tourist->email ?? 'Travel Enthusiast' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-2">
                                    <a href="{{ route('tourist.profile.show') }}" 
                                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors">
                                        <i class="fas fa-user-circle mr-3 text-blue-600"></i>
                                        View Profile
                                    </a>
                                    <a href="{{ route('tourist.bookings.view') }}" 
                                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors">
                                        <i class="fas fa-calendar-alt mr-3 text-blue-600"></i>
                                        My Bookings
                                    </a>
                                    <hr class="my-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full flex items-center px-4 py-3 text-red-600 hover:bg-red-50 transition-colors">
                                            <i class="fas fa-sign-out-alt mr-3"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" 
                           :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-gray-700 hover:text-blue-600'"
                           class="font-medium transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-full font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                            Sign Up
                        </a>
                    @endif

                    <!-- Mobile menu button -->
                    <button @click="isOpen = !isOpen" 
                            :class="scrolled ? 'text-gray-700' : 'text-gray-700'"
                            class="md:hidden p-2 rounded-lg transition-colors">
                        <i class="fas fa-bars text-xl" x-show="!isOpen"></i>
                        <i class="fas fa-times text-xl" x-show="isOpen" x-cloak></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div x-show="isOpen" x-transition class="md:hidden bg-white rounded-b-2xl shadow-lg border-t">
                <div class="px-4 py-6 space-y-4">
                    <a href="{{ route('landing') }}" class="block text-gray-700 hover:text-blue-600 font-medium">Home</a>
                    <a href="#about" class="block text-gray-700 hover:text-blue-600 font-medium">About</a>
                    <a href="{{ route('tourist.explore') }}" class="block text-gray-700 hover:text-blue-600 font-medium">Explore</a>
                    <a href="{{ route('emergency-services.index') }}" class="block text-gray-700 hover:text-blue-600 font-medium">Emergency</a>
                    <a href="#contact" class="block text-gray-700 hover:text-blue-600 font-medium">Contact us</a>
                    @guest
                        <hr class="my-4">
                        <a href="{{ route('login') }}" class="block text-gray-700 hover:text-blue-600 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-full text-center font-medium">Sign Up</a>
                    @endguest
                </div>
            </div>
        </div>
    </header>

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

    <!-- ✅ Professional Footer -->
<footer class="bg-gradient-to-b from-gray-900 to-gray-950 text-white pt-16 pb-8 mt-auto relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-500/10 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-purple-500/10 rounded-full filter blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <!-- Top Section with Logo and Newsletter -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16 pb-16 border-b border-gray-800">
            <!-- Brand Section -->
            <div class="space-y-8">
                <div class="flex items-center space-x-4">
                    <!-- <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl blur opacity-75"></div>
                        <div class="relative bg-gray-900 p-2 rounded-xl">
                            <img src="{{ asset('images/logoo.png') }}" alt="TripMate" class="h-10 w-10">
                        </div>
                    </div> -->
                    <div>
                        <h3 class="text-2xl font-bold">Trip<span class="text-blue-500">Mate</span></h3>
                        <p class="text-gray-400 text-sm">Your Ultimate Travel Companion</p>
                    </div>
                </div>
                <p class="text-gray-400 max-w-md">
                    Discover Sri Lanka's hidden gems with TripMate. We're dedicated to creating unforgettable travel experiences 
                    that connect you with the heart and soul of this beautiful island.
                </p>
                <!-- Social Links -->
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-twitter text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-linkedin-in text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="lg:pl-12">
                <h4 class="text-xl font-semibold mb-6">Subscribe to Our Newsletter</h4>
                <p class="text-gray-400 mb-6">
                    Stay updated with travel tips, local insights, and exclusive offers.
                </p>
                <form class="space-y-4">
                    <div class="flex gap-4">
                        <input type="email" 
                               placeholder="Enter your email" 
                               class="flex-1 bg-gray-800 rounded-lg px-4 py-3 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                            Subscribe
                        </button>
                    </div>
                    <p class="text-gray-500 text-sm">
                        By subscribing, you agree to our Privacy Policy and consent to receive updates.
                    </p>
                </form>
            </div>
        </div>

        <!-- Main Footer Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <!-- Company Info -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Company</h4>
                <ul class="space-y-3 text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Our Team</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Press Kit</a></li>
                </ul>
            </div>

            <!-- Explore -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Explore</h4>
                <ul class="space-y-3 text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors">Destinations</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Activities</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Local Guides</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Travel Blog</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Support</h4>
                <ul class="space-y-3 text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Safety Tips</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Cancellation Options</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">COVID-19 Updates</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                <ul class="space-y-3 text-gray-400">
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-map-marker-alt text-blue-500"></i>
                        <span>Colombo, Sri Lanka</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-phone text-blue-500"></i>
                        <span>+94 11 234 5678</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-blue-500"></i>
                        <span>hello@tripmate.com</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 border-t border-gray-800">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-gray-400 text-sm">
                    © 2025 TripMate. All rights reserved.
                </div>
                <div class="flex items-center space-x-6 text-sm text-gray-400">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Cookie Settings</a>
                    <a href="#" class="hover:text-white transition-colors">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>

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