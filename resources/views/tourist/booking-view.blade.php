<!DOCTYPE html>
<html lang="en">
<x-tourist.head title="My Bookings - TripMate" />
</head>
<body class="bg-gray-50 min-h-screen">

@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
@endphp

<x-tourist.header />
<header x-data="{ isOpen: false, scrolled: false }" 
        @scroll.window="scrolled = window.pageYOffset > 50"
        :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg' : 'bg-white/95 backdrop-blur-md shadow-lg'"
        class="fixed top-0 w-full z-50 transition-all duration-300">
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
                    <h1 class="text-xl font-bold text-gray-900">
                        Trip<span class="text-blue-600">Mate</span>
                    </h1>
                    <p class="text-xs text-gray-500">Your Travel Companion</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('landing') }}" 
                   class="text-gray-700 hover:text-blue-600 font-medium transition-colors relative group">
                    Home
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#about" 
                   class="text-gray-700 hover:text-blue-600 font-medium transition-colors relative group">
                    About
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="{{ route('tourist.explore') }}" 
                   class="text-gray-700 hover:text-blue-600 font-medium transition-colors relative group">
                    Explore
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="{{ route('emergency-services.index') }}" 
                   class="text-gray-700 hover:text-blue-600 font-medium transition-colors relative group">
                    Emergency
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#contact" 
                   class="text-gray-700 hover:text-blue-600 font-medium transition-colors relative group">
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
                                class="text-gray-700 hover:text-blue-600 w-10 h-10 rounded-full flex items-center justify-center hover:bg-white/10 transition-all duration-300">
                            <i class="fas fa-user-circle text-2xl"></i>
                        </button>

                        <div x-show="open" x-transition
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
                       class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-full font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        Sign Up
                    </a>
                @endif

                <!-- Mobile menu button -->
                <button @click="isOpen = !isOpen" 
                        class="text-gray-700 md:hidden p-2 rounded-lg transition-colors">
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
                <a href="#contact" class="block text-gray-700 hover:text-blue-600 font-medium">Contact</a>
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
    <div class="pt-20 min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/30">
        
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600/90 to-purple-600/90 text-white py-16 mb-8">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-6 backdrop-blur-sm">
                        <i class="fas fa-calendar-check text-3xl"></i>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 slide-in-left">My Bookings</h1>
                    <p class="text-xl text-blue-100 max-w-2xl mx-auto fade-in">
                        Track and manage your hotel reservations with ease
                    </p>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-6 mb-6">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl shadow-lg animate-pulse" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-green-900">Success!</h3>
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-6 pb-16" x-data="{ activeTab: 'all' }">
            
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover-lift transition-all">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</h3>
                            <p class="text-gray-600 text-sm">Total Bookings</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover-lift transition-all">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['confirmed'] }}</h3>
                            <p class="text-gray-600 text-sm">Confirmed</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover-lift transition-all">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</h3>
                            <p class="text-gray-600 text-sm">Pending</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover-lift transition-all">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['completed'] }}</h3>
                            <p class="text-gray-600 text-sm">Completed</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover-lift transition-all">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['cancelled'] }}</h3>
                            <p class="text-gray-600 text-sm">Cancelled</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white rounded-xl shadow-lg p-2 mb-8 border border-gray-100">
                <div class="flex flex-wrap gap-2">
                    <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'" 
                            class="px-6 py-3 rounded-lg font-medium transition-all duration-200">
                        <i class="fas fa-list mr-2"></i>All Bookings
                    </button>
                    <button @click="activeTab = 'confirmed'" :class="activeTab === 'confirmed' ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100'" 
                            class="px-6 py-3 rounded-lg font-medium transition-all duration-200">
                        <i class="fas fa-check-circle mr-2"></i>Confirmed
                    </button>
                    <button @click="activeTab = 'pending'" :class="activeTab === 'pending' ? 'bg-yellow-600 text-white' : 'text-gray-600 hover:bg-gray-100'" 
                            class="px-6 py-3 rounded-lg font-medium transition-all duration-200">
                        <i class="fas fa-clock mr-2"></i>Pending
                    </button>
                    <button @click="activeTab = 'completed'" :class="activeTab === 'completed' ? 'bg-purple-600 text-white' : 'text-gray-600 hover:bg-gray-100'" 
                            class="px-6 py-3 rounded-lg font-medium transition-all duration-200">
                        <i class="fas fa-star mr-2"></i>Completed
                    </button>
                    <button @click="activeTab = 'cancelled'" :class="activeTab === 'cancelled' ? 'bg-red-600 text-white' : 'text-gray-600 hover:bg-gray-100'" 
                            class="px-6 py-3 rounded-lg font-medium transition-all duration-200">
                        <i class="fas fa-times-circle mr-2"></i>Cancelled
                    </button>
                </div>
            </div>

        <!-- Bookings List -->
        @if($bookings->count() > 0)
            <div class="space-y-8">
                @foreach($bookings as $booking)
                    <div class="booking-card bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden hover-lift transition-all duration-300"
                         x-show="activeTab === 'all' || activeTab === '{{ strtolower($booking->status ?? $booking->booking_status) }}'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95">
                        <!-- Hotel Header with Image -->
                        <div class="relative h-48 md:h-32 bg-gradient-to-r from-blue-600 to-purple-600">
                            <div class="absolute inset-0 bg-black/20"></div>
                            <div class="relative h-full flex items-center justify-between p-6 text-white">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4 mb-3">
                                        <h3 class="text-2xl font-bold">{{ $booking->hotel->name }}</h3>
                                        <span class="status-badge status-{{ strtolower($booking->status ?? $booking->booking_status) }} shadow-lg">
                                            {{ ucfirst($booking->status ?? $booking->booking_status) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center text-blue-100 mb-2">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        <span>{{ $booking->hotel->location->address ?? 'Location not available' }}</span>
                                    </div>
                                    <div class="text-sm text-blue-200">
                                        Booking Reference: {{ $booking->booking_reference ?? 'BK' . str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}
                                    </div>
                                </div>
                                <div class="text-right hidden md:block">
                                    <div class="text-3xl font-bold mb-1">
                                        ${{ number_format($booking->total_amount, 2) }}
                                    </div>
                                    <div class="text-sm text-blue-200">
                                        Total Amount
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Details Card -->
                        <div class="p-8">
                            <!-- Mobile Price Display -->
                            <div class="md:hidden mb-6 text-center p-4 bg-gray-50 rounded-xl">
                                <div class="text-2xl font-bold text-gray-900 mb-1">
                                    ${{ number_format($booking->total_amount, 2) }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    Total Amount
                                </div>
                            </div>

                            <!-- Date Information Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                            <i class="fas fa-calendar-check text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-blue-800 mb-1">Check-in</div>
                                            <div class="text-lg font-bold text-blue-900">{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</div>
                                            <div class="text-sm text-blue-700">{{ \Carbon\Carbon::parse($booking->check_in)->format('l') }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gradient-to-br from-red-50 to-pink-100 p-6 rounded-xl border border-red-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-14 h-14 bg-red-600 rounded-xl flex items-center justify-center shadow-lg">
                                            <i class="fas fa-calendar-times text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-red-800 mb-1">Check-out</div>
                                            <div class="text-lg font-bold text-red-900">{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</div>
                                            <div class="text-sm text-red-700">{{ \Carbon\Carbon::parse($booking->check_out)->format('l') }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gradient-to-br from-green-50 to-emerald-100 p-6 rounded-xl border border-green-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-14 h-14 bg-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                            <i class="fas fa-moon text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-green-800 mb-1">Duration</div>
                                            <div class="text-lg font-bold text-green-900">
                                                {{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }} nights
                                            </div>
                                            <div class="text-sm text-green-700">Stay period</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment & Booking Info -->
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between p-6 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex items-center space-x-6 mb-4 lg:mb-0">
                                    <!-- Payment Status -->
                                    <div class="flex items-center space-x-3">
                                        @if(($booking->status ?? $booking->booking_status) === 'cancelled')
                                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-undo text-purple-600 text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-purple-800">Payment Refunded</div>
                                                <div class="text-sm text-purple-600">Amount returned</div>
                                            </div>
                                        @elseif($booking->payment_status === 'paid')
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-green-800">Payment Completed</div>
                                                <div class="text-sm text-green-600">Secure transaction</div>
                                            </div>
                                        @elseif($booking->payment_status === 'pending')
                                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-yellow-800">Payment Pending</div>
                                                <div class="text-sm text-yellow-600">Awaiting confirmation</div>
                                            </div>
                                        @else
                                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-times-circle text-red-600 text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-red-800">Payment Failed</div>
                                                <div class="text-sm text-red-600">Requires attention</div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Booking Date -->
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-calendar-plus text-gray-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">Booked on</div>
                                            <div class="text-sm text-gray-600">{{ $booking->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('tourist.booking.details', $booking) }}" 
                                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                        <i class="fas fa-receipt mr-2"></i>
                                        View Receipt
                                    </a>
                                    
                                    @if($booking->status === 'completed')
                                        <!-- Write Review Button -->
                                        <button onclick="openReviewModal({{ $booking->id }}, '{{ $booking->hotel->name }}')"
                                                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-medium rounded-xl hover:from-yellow-600 hover:to-orange-600 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                            <i class="fas fa-star mr-2"></i>
                                            Write Review
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <!-- No results found message for filtering -->
                <div x-show="false" x-ref="noResults" class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-search text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No bookings found</h3>
                        <p class="text-gray-600 mb-6">
                            There are no bookings with the selected status.
                        </p>
                        <button @click="activeTab = 'all'" 
                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-list mr-2"></i>
                            Show All Bookings
                        </button>
                    </div>
                </div>
            </div>

            <!-- Enhanced Pagination -->
            @if($bookings->hasPages())
                <div class="mt-12 flex justify-center">
                    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
                        {{ $bookings->links() }}
                    </div>
                </div>
            @endif

        @else
            <!-- Enhanced Empty State -->
            <div class="text-center py-20">
                <div class="max-w-md mx-auto">
                    <!-- Animated Icon -->
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-8 float">
                        <i class="fas fa-calendar-times text-6xl text-gray-400"></i>
                    </div>
                    
                    <div class="scale-in">
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">No Bookings Yet</h3>
                        <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                            Ready to start your adventure? Explore our amazing hotels and destinations to make your first booking!
                        </p>
                        
                        <div class="space-y-4">
                            <a href="{{ route('tourist.explore') }}" 
                               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                                <i class="fas fa-search mr-3"></i>
                                Explore Hotels
                            </a>
                            
                            <div class="text-sm text-gray-500">
                                <p>Discover your perfect stay from hundreds of verified hotels</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        </main>
    </div>

<x-tourist.footer />

    <!-- Enhanced Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 transition-all duration-300">
        <div class="flex items-center justify-center min-h-screen p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] shadow-2xl border border-gray-100 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 flex flex-col" id="reviewModalContent">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 id="reviewModalTitle" class="text-2xl font-bold">Write Review</h3>
                            <p class="text-blue-100 mt-1">Share your experience with other travelers</p>
                        </div>
                        <button onclick="closeReviewModal()" class="text-white/80 hover:text-white transition-colors p-2 rounded-full hover:bg-white/10">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <div class="p-8 overflow-y-auto flex-1">
                    <form id="reviewForm" class="space-y-6">
                        <input type="hidden" id="reviewBookingId" name="booking_id">
                        <input type="hidden" id="reviewId" name="review_id">
                        
                        <!-- Hotel Name Display -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-hotel text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Reviewing</label>
                                    <p id="reviewHotelName" class="text-lg font-semibold text-gray-900"></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced Star Rating -->
                        <div class="space-y-3">
                            <label class="block text-lg font-semibold text-gray-900">How was your experience? *</label>
                            <div class="flex items-center justify-center space-x-2 p-6 bg-gray-50 rounded-xl">
                                <div id="starRating" class="flex space-x-2">
                                    <button type="button" class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition-all duration-200 transform hover:scale-110" data-rating="1">★</button>
                                    <button type="button" class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition-all duration-200 transform hover:scale-110" data-rating="2">★</button>
                                    <button type="button" class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition-all duration-200 transform hover:scale-110" data-rating="3">★</button>
                                    <button type="button" class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition-all duration-200 transform hover:scale-110" data-rating="4">★</button>
                                    <button type="button" class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition-all duration-200 transform hover:scale-110" data-rating="5">★</button>
                                </div>
                            </div>
                            <div class="text-center">
                                <span id="ratingText" class="text-lg font-medium text-gray-600"></span>
                            </div>
                            <input type="hidden" id="ratingValue" name="rating" required>
                        </div>
                        
                        <!-- Review Title -->
                        <div class="space-y-2">
                            <label for="reviewTitle" class="block text-lg font-semibold text-gray-900">
                                Review Title *
                            </label>
                            <input type="text" id="reviewTitle" name="title" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Give your review a catchy title..." required maxlength="255">
                            <p class="text-sm text-gray-500">Help others understand your experience at a glance</p>
                        </div>
                        
                        <!-- Review Description -->
                        <div class="space-y-2">
                            <label for="reviewDescription" class="block text-lg font-semibold text-gray-900">
                                Tell us more about your stay *
                            </label>
                            <textarea id="reviewDescription" name="description" rows="6"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                                      placeholder="Share details about the service, cleanliness, location, amenities, and overall experience..." required minlength="10" maxlength="1000"></textarea>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-500">Your detailed feedback helps other travelers make informed decisions</p>
                                <div class="text-sm text-gray-500">
                                    <span id="charCount" class="font-medium">0</span>/1000 characters
                                </div>
                            </div>
                        </div>
                        
                        <!-- Review Tips -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                                <i class="fas fa-lightbulb mr-2"></i>
                                Tips for a great review
                            </h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Be specific about what you liked or didn't like</li>
                                <li>• Mention the staff service and hotel amenities</li>
                                <li>• Include details about location and accessibility</li>
                                <li>• Be honest and constructive in your feedback</li>
                            </ul>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <button type="button" onclick="closeReviewModal()" 
                                    class="flex-1 px-6 py-3 text-gray-700 bg-gray-100 border border-gray-300 rounded-xl hover:bg-gray-200 transition-all duration-200 font-medium">
                                Cancel
                            </button>
                            <button type="submit" id="submitReviewBtn"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-semibold shadow-lg transform hover:scale-105">
                                <span class="flex items-center justify-center">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Submit Review
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        let currentRating = 0;
        
        // Rating system
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize filtering system
            initializeBookingFilter();
            
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
            document.getElementById('reviewModalTitle').textContent = 'Write Review';
            document.getElementById('reviewBookingId').value = bookingId;
            document.getElementById('reviewId').value = '';
            document.getElementById('reviewHotelName').textContent = hotelName;
            document.getElementById('submitReviewBtn').innerHTML = `
                <span class="flex items-center justify-center">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit Review
                </span>
            `;
            
            // Reset form
            document.getElementById('reviewForm').reset();
            setRating(0);
            document.getElementById('charCount').textContent = '0';
            
            // Lock background scrolling
            document.body.style.overflow = 'hidden';
            
            // Show modal with animation
            const modal = document.getElementById('reviewModal');
            const modalContent = document.getElementById('reviewModalContent');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        function closeReviewModal() {
            const modal = document.getElementById('reviewModal');
            const modalContent = document.getElementById('reviewModalContent');
            
            // Unlock background scrolling
            document.body.style.overflow = '';
            
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
        
        async function submitReview() {
            const formData = new FormData(document.getElementById('reviewForm'));
            const submitBtn = document.getElementById('submitReviewBtn');
            
            // Validate rating
            if (!currentRating) {
                alert('Please select a rating');
                return;
            }
            
            // Disable button with loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Submitting...
                </span>
            `;
            
            try {
                const response = await fetch('/reviews', {
                    method: 'POST',
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
                    
                    // Show enhanced success message
                    showEnhancedSuccessMessage(data.message);
                    
                    // Reload page to show updated review status
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    throw new Error(data.error || 'Failed to submit review');
                }
                
            } catch (error) {
                console.error('Review submission error:', error);
                showErrorMessage('Error submitting review: ' + error.message);
            } finally {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <span class="flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Review
                    </span>
                `;
            }
        }
        
        function showEnhancedSuccessMessage(message) {
            // Create enhanced success alert
            const alert = document.createElement('div');
            alert.className = 'fixed top-4 right-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 transform translate-x-full transition-transform duration-300';
            alert.innerHTML = `
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold">Success!</h4>
                        <p class="text-sm opacity-90">${message}</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(alert);
            
            // Animate in
            setTimeout(() => {
                alert.classList.remove('translate-x-full');
                alert.classList.add('translate-x-0');
            }, 100);
            
            // Remove after 4 seconds
            setTimeout(() => {
                alert.classList.remove('translate-x-0');
                alert.classList.add('translate-x-full');
                setTimeout(() => alert.remove(), 300);
            }, 4000);
        }
        
        function showErrorMessage(message) {
            // Create error alert
            const alert = document.createElement('div');
            alert.className = 'fixed top-4 right-4 bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 transform translate-x-full transition-transform duration-300';
            alert.innerHTML = `
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold">Error</h4>
                        <p class="text-sm opacity-90">${message}</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(alert);
            
            // Animate in
            setTimeout(() => {
                alert.classList.remove('translate-x-full');
                alert.classList.add('translate-x-0');
            }, 100);
            
            // Remove after 5 seconds
            setTimeout(() => {
                alert.classList.remove('translate-x-0');
                alert.classList.add('translate-x-full');
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        }
        
        // Initialize booking filter system
        function initializeBookingFilter() {
            // Add mutation observer to watch for Alpine.js changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                        checkFilterResults();
                    }
                });
            });
            
            // Observe all booking cards
            const bookingCards = document.querySelectorAll('.booking-card');
            bookingCards.forEach(card => {
                observer.observe(card, { attributes: true, attributeFilter: ['style'] });
            });
            
            // Initial check
            setTimeout(() => checkFilterResults(), 100);
        }
        
        function checkFilterResults() {
            // Check if we're on a filtered view
            const mainElement = document.querySelector('main[x-data]');
            if (!mainElement) return;
            
            // Use Alpine's internal state - need to access it through Alpine store or similar
            setTimeout(() => {
                const visibleCards = document.querySelectorAll('.booking-card:not([style*="display: none"])');
                const noResultsDiv = document.querySelector('[x-ref="noResults"]');
                
                // Get current active tab by checking button states
                const activeButton = document.querySelector('button[class*="bg-"][class*="text-white"]:not([class*="bg-blue-600"])');
                const isFiltered = activeButton && !activeButton.textContent.includes('All Bookings');
                
                if (noResultsDiv) {
                    if (isFiltered && visibleCards.length === 0) {
                        noResultsDiv.style.display = 'block';
                        noResultsDiv.setAttribute('x-show', 'true');
                    } else {
                        noResultsDiv.style.display = 'none';
                        noResultsDiv.setAttribute('x-show', 'false');
                    }
                }
            }, 50);
        }
    </script>

</body>
</html>
