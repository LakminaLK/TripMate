<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $hotel->name }} | TripMate</title>
    <x-tourist.head />
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
        .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .hover-scale:hover { transform: scale(1.02); }
        
        @keyframes fadeIn { to { opacity: 1; } }
        @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }
        @keyframes slideInLeft { to { opacity: 1; transform: translateX(0); } }
        @keyframes scaleIn { to { opacity: 1; transform: scale(1); } }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        
        /* Button loading animations */
        .btn-loading { 
            position: relative; 
            pointer-events: none; 
        }
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #667eea; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #764ba2; }
        
        /* Image gallery custom styles */
        .gallery-overlay {
            background: linear-gradient(45deg, rgba(0,0,0,0.7), rgba(0,0,0,0.3));
        }
        
        /* Enhanced image hover effects */
        .image-hover-container {
            position: relative;
            overflow: hidden;
        }
        
        .image-hover-container img {
            transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .image-hover-container:hover img {
            transform: scale(1.1);
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Notification styles */
        .notification {
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .notification.show {
            transform: translateX(0);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 blue-scrollbar">

@php
    $tourist = auth()->guard('tourist')->user();
@endphp

<!-- ✅ Professional Navbar -->
<header x-data="{ isOpen: false }" 
        class="fixed top-0 w-full z-50 bg-white/95 backdrop-blur-md shadow-lg transition-all duration-300">
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
                                class="w-10 h-10 rounded-full flex items-center justify-center text-gray-700 hover:text-blue-600 hover:bg-gray-100 transition-all duration-300">
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
                    <a href="{{ route('login', ['redirect' => urlencode(request()->fullUrl())]) }}" 
                       class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-full font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        Sign Up
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="pt-20">
    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-6 py-6">
        <nav class="flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('landing') }}" class="hover:text-blue-600 transition-colors">Home</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('tourist.explore') }}" class="hover:text-blue-600 transition-colors">Explore</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('tourist.location.show', $hotel->location) }}" class="hover:text-blue-600 transition-colors">{{ $hotel->location->name }}</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">{{ $hotel->name }}</span>
        </nav>
    </div>

    <!-- Hotel Header -->
    <section class="max-w-7xl mx-auto px-6 pb-8">
        <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
            <!-- Hotel Image Gallery -->
            <div x-data="{ currentImage: 0, showGallery: false }" class="relative">
                <div class="relative h-80 md:h-96 overflow-hidden">
                    @if($hotel->main_image)
                        <img src="{{ asset('storage/' . $hotel->main_image) }}" 
                             alt="{{ $hotel->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-blue-600 to-purple-600 flex items-center justify-center">
                            <div class="text-center text-white">
                                <i class="fas fa-hotel text-6xl mb-4"></i>
                                <h3 class="text-2xl font-bold">{{ $hotel->name }}</h3>
                            </div>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                    
                    <!-- Gallery Button -->
                    @if($hotel->images->count() > 0)
                        <button @click="showGallery = true" 
                                class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-md px-4 py-2 rounded-lg text-gray-800 hover:bg-white transition-all">
                            <i class="fas fa-images mr-2"></i>
                            View Gallery ({{ $hotel->images->count() }})
                        </button>
                    @endif
                </div>

                <!-- Image Gallery Modal -->
                <div x-show="showGallery" x-cloak 
                     class="fixed inset-0 z-50 bg-black bg-opacity-90 flex items-center justify-center p-4"
                     @click.self="showGallery = false">
                    <div class="relative max-w-4xl w-full">
                        <button @click="showGallery = false" 
                                class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 z-10">
                            <i class="fas fa-times"></i>
                        </button>
                        
                        @if($hotel->images->count() > 0)
                            <div class="relative">
                                <img :src="'{{ asset('storage/') }}/' + [
                                    @foreach($hotel->images as $image)
                                        '{{ $image->image_path }}'{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                ][currentImage]"
                                     class="w-full h-96 object-cover rounded-lg">
                                
                                @if($hotel->images->count() > 1)
                                    <button @click="currentImage = currentImage > 0 ? currentImage - 1 : {{ $hotel->images->count() - 1 }}"
                                            class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 backdrop-blur-md text-white p-2 rounded-full hover:bg-white/30 transition-all">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button @click="currentImage = currentImage < {{ $hotel->images->count() - 1 }} ? currentImage + 1 : 0"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 backdrop-blur-md text-white p-2 rounded-full hover:bg-white/30 transition-all">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                @endif
                            </div>
                            
                            <!-- Thumbnail Navigation -->
                            @if($hotel->images->count() > 1)
                                <div class="flex justify-center mt-4 space-x-2 overflow-x-auto">
                                    @foreach($hotel->images as $index => $image)
                                        <button @click="currentImage = {{ $index }}"
                                                :class="currentImage === {{ $index }} ? 'ring-2 ring-white' : ''"
                                                class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden hover:opacity-80 transition-all">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                 class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Hotel Info -->
            <div class="p-8">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8">
                    <!-- Left Column - Basic Info -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $hotel->name }}</h1>
                                <div class="flex items-center text-gray-600 mb-4">
                                    <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                                    <span>{{ $hotel->location->name ?? 'Location not specified' }}</span>
                                    @if($hotel->address)
                                        <span class="ml-2 text-gray-500">• {{ $hotel->address }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Star Rating -->
                            @if($hotel->star_rating)
                                <div class="flex flex-col items-end space-y-3">
                                    <div class="flex items-center bg-yellow-50 px-3 py-2 rounded-lg">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $hotel->star_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="ml-2 text-sm font-medium text-gray-700">{{ $hotel->star_rating }}-Star Hotel</span>
                                    </div>
                                    
                                    <!-- View on Map Button -->
                                    @if($hotel->map_url || ($hotel->latitude && $hotel->longitude))
                                        <a href="{{ $hotel->map_url ?: 'https://www.google.com/maps/search/?api=1&query=' . $hotel->latitude . ',' . $hotel->longitude }}" 
                                           target="_blank"
                                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2 text-sm">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>View on Map</span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Description -->
                        @if($hotel->description)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">About This Hotel</h3>
                                <p class="text-gray-600 leading-relaxed">{{ $hotel->description }}</p>
                            </div>
                        @endif

                        <!-- Facilities -->
                        @if($hotel->facilities->count() > 0)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Facilities & Amenities</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($hotel->facilities as $facility)
                                        <div class="flex items-center space-x-2 bg-blue-50 text-blue-800 px-3 py-2 rounded-lg">
                                            <i class="fas fa-check text-blue-600"></i>
                                            <span class="text-sm font-medium">{{ $facility->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Room Types -->
                        @if($availableRooms->count() > 0)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                                    <i class="fas fa-bed text-blue-600 mr-2"></i>
                                    Our Room Types
                                    <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        {{ $availableRooms->count() }} {{ Str::plural('Type', $availableRooms->count()) }}
                                    </span>
                                </h3>
                                
                                <div class="space-y-4">
                                    @foreach($availableRooms as $room)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-300">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                                <!-- Room Info -->
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900 mb-1">{{ $room->roomType->name }}</h4>
                                                    @if($room->roomType->description)
                                                        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($room->roomType->description, 80) }}</p>
                                                    @endif
                                                    
                                                    <!-- Room Features (Compact) -->
                                                    <div class="flex flex-wrap gap-3 text-xs text-gray-500">
                                                        @if($room->roomType->max_occupancy)
                                                            <span class="flex items-center">
                                                                <i class="fas fa-users text-blue-500 mr-1"></i>
                                                                {{ $room->roomType->max_occupancy }} guests
                                                            </span>
                                                        @endif
                                                        <span class="flex items-center">
                                                            <i class="fas fa-door-open text-blue-500 mr-1"></i>
                                                            {{ $room->room_count }} rooms
                                                        </span>
                                                        @if($room->roomType->base_price)
                                                            <span class="flex items-center">
                                                                <i class="fas fa-tag text-blue-500 mr-1"></i>
                                                                from ${{ number_format($room->roomType->base_price, 0) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Price & Action -->
                                                <div class="text-right">
                                                    <div class="text-lg font-bold text-gray-900">${{ number_format($room->price_per_night, 0) }}</div>
                                                    <div class="text-xs text-gray-500 mb-2">per night</div>
                                                    <button onclick="scrollToSearch()" 
                                                            class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition-colors">
                                                        Check Availability
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column - Guest Reviews -->
                    <div class="lg:w-80 space-y-6">
                        <!-- Guest Reviews Section -->
                        @if($totalReviews > 0)
                            <div class="bg-white rounded-2xl p-6 border border-gray-200">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Guest Reviews</h3>
                                    <div class="flex items-center space-x-2">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($averageRating))
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $averageRating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-600">{{ $averageRating }}/5 ({{ $totalReviews }} {{ $totalReviews === 1 ? 'review' : 'reviews' }})</span>
                                    </div>
                                </div>

                                <!-- Latest Reviews -->
                                <div class="space-y-3">
                                    @foreach($reviews as $review)
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="flex items-start justify-between mb-2">
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <span class="text-sm font-medium text-blue-600">{{ substr($review->tourist->name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ $review->tourist->name }}</p>
                                                        <div class="flex text-yellow-400 text-xs">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->rating)
                                                                    <i class="fas fa-star"></i>
                                                                @else
                                                                    <i class="far fa-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-1">{{ $review->title }}</h4>
                                            <p class="text-xs text-gray-600 leading-relaxed">{{ Str::limit($review->description, 100) }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- See All Reviews Button -->
                                @if($totalReviews > 6)
                                    <button onclick="openAllReviewsModal()"
                                            class="w-full mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium py-2 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                                        See All {{ $totalReviews }} Reviews
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="bg-white rounded-2xl p-6 border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Guest Reviews</h3>
                                <div class="text-center py-4">
                                    <i class="fas fa-star text-gray-300 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-500">No reviews yet</p>
                                    <p class="text-xs text-gray-400">Be the first to leave a review!</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Available Rooms Section -->
    <section class="max-w-7xl mx-auto px-6 pb-12">
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <!-- Header with Search Form -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Check Room Availability</h2>
                
                <!-- Availability Search Form -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 border border-blue-200">
                    <form id="availability-form" action="{{ route('tourist.hotels.check-availability', $hotel) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Check-in Date -->
                            <div>
                                <label for="check_in" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt text-blue-600 mr-1"></i>
                                    Check-in Date
                                </label>
                                <input type="date" 
                                       id="check_in" 
                                       name="check_in" 
                                       min="{{ date('Y-m-d') }}"
                                       value="{{ request('check_in', date('Y-m-d')) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       required>
                            </div>
                            
                            <!-- Check-out Date -->
                            <div>
                                <label for="check_out" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-check text-blue-600 mr-1"></i>
                                    Check-out Date
                                </label>
                                <input type="date" 
                                       id="check_out" 
                                       name="check_out" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       required>
                            </div>
                            
                            <!-- Search Button -->
                            <div class="flex items-end">
                                <button type="submit" 
                                        id="check-availability-btn"
                                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-semibold flex items-center justify-center">
                                    <i class="fas fa-search mr-2"></i>
                                    Check Availability
                                </button>
                            </div>
                        </div>
                        
                        <!-- Quick Info -->
                        <div class="mt-4 flex items-center justify-center text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            <span>Find available rooms and exact pricing for your travel dates</span>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Availability Results -->
            <div id="availability-results">
                @if(isset($availabilityCheck))
                    <!-- Display Search Results -->
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-green-800">
                                <i class="fas fa-check-circle mr-2"></i>
                                Availability Results
                            </h3>
                            <div class="text-sm text-green-700">
                                {{ $availabilityCheck['check_in']->format('M d, Y') }} - {{ $availabilityCheck['check_out']->format('M d, Y') }}
                                ({{ $availabilityCheck['nights'] }} {{ Str::plural('night', $availabilityCheck['nights']) }})
                            </div>
                        </div>
                        
                        <div class="grid gap-4">
                            @foreach($availabilityCheck['results'] as $result)
                                <div class="bg-white border border-gray-200 rounded-xl p-6 
                                    {{ $result['can_accommodate'] ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50' }}">
                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                        <!-- Room Details -->
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-3">
                                                <div>
                                                    <h4 class="text-xl font-semibold text-gray-900">{{ $result['room_type_name'] }}</h4>
                                                    @if($result['room_details']->roomType->description)
                                                        <p class="text-gray-600 text-sm mt-1">{{ $result['room_details']->roomType->description }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-2xl font-bold text-gray-900">${{ number_format($result['price_per_night'], 2) }}</div>
                                                    <div class="text-sm text-gray-500">per night</div>
                                                </div>
                                            </div>
                                            
                                            <!-- Availability Info -->
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                                <div class="text-sm">
                                                    <span class="text-gray-500">Total Rooms:</span>
                                                    <span class="font-semibold ml-1">{{ $result['total_rooms'] }}</span>
                                                </div>
                                                <div class="text-sm">
                                                    <span class="text-gray-500">Booked:</span>
                                                    <span class="font-semibold ml-1 text-red-600">{{ $result['booked_rooms'] }}</span>
                                                </div>
                                                <div class="text-sm">
                                                    <span class="text-gray-500">Available:</span>
                                                    <span class="font-semibold ml-1 text-green-600">{{ $result['available_rooms'] }}</span>
                                                </div>
                                                <div class="text-sm">
                                                    <span class="text-gray-500">You need:</span>
                                                    <span class="font-semibold ml-1">{{ $availabilityCheck['requested_rooms'] }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Status -->
                                            @if($result['can_accommodate'])
                                                <div class="inline-flex items-center text-green-700 bg-green-100 px-3 py-2 rounded-lg">
                                                    <i class="fas fa-check-circle mr-2"></i>
                                                    <span class="font-medium">Available for your dates</span>
                                                </div>
                                            @else
                                                <div class="inline-flex items-center text-red-700 bg-red-100 px-3 py-2 rounded-lg">
                                                    <i class="fas fa-times-circle mr-2"></i>
                                                    <span class="font-medium">Not enough rooms available</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Booking Actions -->
                                        @if($result['can_accommodate'])
                                            <div class="lg:w-48 flex flex-col space-y-3">
                                                <div class="text-center mb-2">
                                                    <div class="text-lg font-bold text-gray-900">
                                                        ${{ number_format($result['price_per_night'] * $availabilityCheck['nights'], 2) }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        Total for {{ $availabilityCheck['nights'] }} {{ Str::plural('night', $availabilityCheck['nights']) }}
                                                    </div>
                                                </div>
                                                <button class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-medium">
                                                    Book Now
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Default Room List -->
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Select Your Dates</h3>
                        <p>Choose your check-in and check-out dates above to see room availability and pricing</p>
                    </div>
                @endif
            </div>

            @if($availableRooms->count() == 0)
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bed text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Rooms Available</h3>
                    <p class="text-gray-500">This hotel currently has no available room types</p>
                </div>
            @endif
        </div>
    </section>

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
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="email" placeholder="Enter your email"
                               class="flex-1 px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                            Subscribe
                        </button>
                    </div>
                    <p class="text-xs text-gray-500">
                        We respect your privacy. Unsubscribe at any time.
                    </p>
                </form>
            </div>
        </div>

        <!-- Links Section -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
            <!-- Company -->
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

<!-- Enhanced Availability Check Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('availability-form');
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const checkButton = document.getElementById('check-availability-btn');
    const resultsContainer = document.getElementById('availability-results');

    // Set minimum date for check-out based on check-in
    checkInInput.addEventListener('change', function() {
        const checkInDate = new Date(this.value);
        const nextDay = new Date(checkInDate);
        nextDay.setDate(nextDay.getDate() + 1);
        
        checkOutInput.min = nextDay.toISOString().split('T')[0];
        
        // If check-out is before or equal to check-in, set it to next day
        if (checkOutInput.value <= this.value) {
            checkOutInput.value = nextDay.toISOString().split('T')[0];
        }
    });

    // AJAX form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Update button state
        checkButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Checking...';
        checkButton.disabled = true;
        
        // Show loading state
        resultsContainer.innerHTML = `
            <div class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Checking Availability...</h3>
                <p class="text-gray-500">Please wait while we search for available rooms</p>
            </div>
        `;

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayResults(data);
            } else {
                showError(data.error || 'Failed to check availability. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while checking availability. Please try again.');
        })
        .finally(() => {
            // Reset button
            checkButton.innerHTML = '<i class="fas fa-search mr-2"></i>Check Availability';
            checkButton.disabled = false;
        });
    });

    function displayResults(data) {
        const checkInDate = new Date(data.check_in);
        const checkOutDate = new Date(data.check_out);
        const formatOptions = { year: 'numeric', month: 'short', day: 'numeric' };
        
        let hasAvailable = data.availability.some(room => room.available_rooms > 0);
        let headerClass = hasAvailable ? 'bg-green-50 border-green-200' : 'bg-orange-50 border-orange-200';
        let headerIcon = hasAvailable ? 'fas fa-check-circle text-green-600' : 'fas fa-exclamation-triangle text-orange-600';
        let headerText = hasAvailable ? 'Available Rooms Found!' : 'No Rooms Available';

        let html = `
            <div class="` + headerClass + ` border rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="` + headerIcon + ` mr-2"></i>
                        ` + headerText + `
                    </h3>
                    <div class="text-sm text-gray-700">
                        ` + checkInDate.toLocaleDateString('en-US', formatOptions) + ` - ` + checkOutDate.toLocaleDateString('en-US', formatOptions) + `
                        (` + data.nights + ` ` + (data.nights === 1 ? 'night' : 'nights') + `)
                    </div>
                </div>
                
                <div class="grid gap-6">
        `;

        data.availability.forEach(room => {
            let statusClass = room.available_rooms > 0 ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50';
            let totalPrice = room.price_per_night * data.nights;
            
            html += `
                <div class="bg-white border border-gray-200 rounded-xl p-6 ` + statusClass + `">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <!-- Room Details -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-900">` + room.room_type_name + `</h4>
                                    ` + (room.room_type_description ? `<p class="text-gray-600 text-sm mt-1">` + room.room_type_description + `</p>` : '') + `
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900">$` + room.price_per_night.toFixed(2) + `</div>
                                    <div class="text-sm text-gray-500">per night</div>
                                </div>
                            </div>
                            
                            <!-- Room Features -->
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                                <div class="text-sm">
                                    <span class="text-gray-500"><i class="fas fa-users text-blue-600 mr-1"></i>Capacity:</span>
                                    <span class="font-semibold ml-1">` + room.room_type_features.max_occupancy + ` guests</span>
                                </div>
                                <div class="text-sm">
                                    <span class="text-gray-500"><i class="fas fa-door-open text-blue-600 mr-1"></i>Available:</span>
                                    <span class="font-semibold ml-1 text-green-600">` + room.available_rooms + ` rooms</span>
                                </div>
                                <div class="text-sm">
                                    <span class="text-gray-500"><i class="fas fa-dollar-sign text-blue-600 mr-1"></i>Price:</span>
                                    <span class="font-semibold ml-1">$` + room.price_per_night.toFixed(2) + ` / night</span>
                                </div>
                            </div>
                            
                            <!-- Availability Status -->
                            <div class="mb-4">
                                ` + (room.available_rooms > 0 ? `
                                <div class="inline-flex items-center text-green-700 bg-green-100 px-3 py-2 rounded-lg">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span class="font-medium">` + room.available_rooms + ` rooms available</span>
                                </div>
                                ` : `
                                <div class="inline-flex items-center text-red-700 bg-red-100 px-3 py-2 rounded-lg">
                                    <i class="fas fa-times-circle mr-2"></i>
                                    <span class="font-medium">No rooms available</span>
                                </div>
                                `) + `
                            </div>
                        </div>
                        
                        <!-- Room Selection & Booking -->
                        ` + (room.available_rooms > 0 ? `
                        <div class="lg:w-80 border-l border-gray-200 pl-6">
                            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                                <h5 class="font-semibold text-gray-900 mb-3">Select Rooms</h5>
                                <div class="space-y-3">
                                    <div>
                                        <label for="room_count_` + room.room_type_id + `" class="block text-sm font-medium text-gray-700 mb-1">
                                            Number of Rooms
                                        </label>
                                        <select id="room_count_` + room.room_type_id + `" 
                                                class="room-count-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                data-room-type="` + room.room_type_id + `"
                                                data-room-name="` + room.room_type_name + `"
                                                data-price="` + room.price_per_night + `"
                                                data-nights="` + data.nights + `">
                                        </select>
                                    </div>
                                    
                                    <div class="border-t border-blue-200 pt-3">
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>Subtotal:</span>
                                            <span class="subtotal-` + room.room_type_id + `">$0.00</span>
                                        </div>
                                        <div class="flex justify-between text-lg font-bold text-gray-900">
                                            <span>Total:</span>
                                            <span class="total-` + room.room_type_id + `">$0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button class="book-room-btn w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-semibold"
                                    data-room-type="` + room.room_type_id + `"
                                    data-room-name="` + room.room_type_name + `"
                                    data-price="` + room.price_per_night + `"
                                    data-check-in="` + data.check_in + `"
                                    data-check-out="` + data.check_out + `">
                                <i class="fas fa-plus mr-2"></i>
                                Add to Booking
                            </button>
                        </div>
                        ` : '') + `
                    </div>
                </div>
            `;
            
            // Add room count options after creating the select element
            setTimeout(() => {
                const select = document.getElementById('room_count_' + room.room_type_id);
                if (select && select.children.length === 0) {
                    // Add option for 0 rooms first
                    const optionZero = document.createElement('option');
                    optionZero.value = 0;
                    optionZero.textContent = '0 Rooms';
                    select.appendChild(optionZero);
                    
                    // Add options for 1 to available rooms (no limit of 5)
                    for (let i = 1; i <= room.available_rooms; i++) {
                        const option = document.createElement('option');
                        option.value = i;
                        option.textContent = i + ' ' + (i === 1 ? 'Room' : 'Rooms');
                        select.appendChild(option);
                    }
                }
            }, 100);
        });

        html += `
                </div>
                
                <!-- Combined Booking Section -->
                <div id="combined-booking-section" class="bg-white border border-gray-200 rounded-xl p-6 mt-6 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-shopping-cart text-blue-600 mr-3"></i>
                            Your Booking Summary
                        </h4>
                        <button id="clear-selection-btn" class="text-sm text-gray-500 hover:text-red-600 transition-colors px-3 py-1 rounded-lg hover:bg-red-50">
                            <i class="fas fa-trash mr-1"></i>Clear All
                        </button>
                    </div>
                    
                    <!-- Booking Details Header -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Check-in:</span>
                                <div class="font-semibold text-gray-900 check-in-date">` + checkInDate.toLocaleDateString('en-US', formatOptions) + `</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Check-out:</span>
                                <div class="font-semibold text-gray-900 check-out-date">` + checkOutDate.toLocaleDateString('en-US', formatOptions) + `</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Duration:</span>
                                <div class="font-semibold text-gray-900">` + data.nights + ` ` + (data.nights === 1 ? 'night' : 'nights') + `</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Hotel:</span>
                                <div class="font-semibold text-gray-900">` + data.hotel_name + `</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Selected Rooms -->
                    <div class="mb-6">
                        <h5 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-bed text-blue-600 mr-2"></i>
                            Selected Rooms
                        </h5>
                        <div id="booking-items" class="space-y-3">
                            <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                <i class="fas fa-info-circle text-gray-400 text-2xl mb-3"></i>
                                <p class="text-gray-500 font-medium">Select rooms above to add them to your booking</p>
                                <p class="text-gray-400 text-sm mt-1">Choose your preferred room types and quantities</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Booking Total -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm text-gray-600 mb-1">Total Amount</div>
                                    <div class="text-3xl font-bold text-gray-900" id="grand-total">$0.00</div>
                                    <div class="text-sm text-gray-500">for ` + data.nights + ` ` + (data.nights === 1 ? 'night' : 'nights') + `</div>
                                </div>
                                <button id="proceed-booking-btn" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        disabled>
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Proceed to Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        resultsContainer.innerHTML = html;
        
        // Store booking data
        let selectedRooms = {};
        let grandTotal = 0;
        
        // Add event listeners for room count changes
        setTimeout(() => {
            document.querySelectorAll('.room-count-select').forEach(select => {
                select.addEventListener('change', function() {
                    const roomType = this.dataset.roomType;
                    const price = parseFloat(this.dataset.price);
                    const nights = parseInt(this.dataset.nights);
                    const rooms = parseInt(this.value) || 0;
                    const roomName = this.dataset.roomName; // Get room name from data attribute
                    
                    const subtotal = price * rooms * nights;
                    const subtotalEl = document.querySelector('.subtotal-' + roomType);
                    const totalEl = document.querySelector('.total-' + roomType);
                    
                    if (subtotalEl) subtotalEl.textContent = '$' + subtotal.toFixed(2);
                    if (totalEl) totalEl.textContent = '$' + subtotal.toFixed(2);
                    
                    // Update selected rooms object
                    if (rooms > 0) {
                        selectedRooms[roomType] = {
                            roomName: roomName,
                            roomCount: rooms,
                            pricePerNight: price,
                            subtotal: subtotal,
                            nights: nights
                        };
                    } else {
                        // Remove from selected rooms if 0 or negative
                        delete selectedRooms[roomType];
                    }
                    
                    updateBookingSummary();
                });
            });
            
            // Combined booking functionality
            function updateBookingSummary() {
                const bookingItemsContainer = document.getElementById('booking-items');
                const grandTotalEl = document.getElementById('grand-total');
                const proceedBtn = document.getElementById('proceed-booking-btn');
                
                if (Object.keys(selectedRooms).length === 0) {
                    bookingItemsContainer.innerHTML = `
                        <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-bed text-gray-400 text-3xl mb-3"></i>
                            <p class="text-gray-600 font-medium">No rooms selected</p>
                            <p class="text-gray-500 text-sm">Select rooms above to add them to your booking</p>
                        </div>
                    `;
                    grandTotal = 0;
                    proceedBtn.disabled = true;
                } else {
                    let html = '';
                    grandTotal = 0;
                    
                    Object.values(selectedRooms).forEach(room => {
                        grandTotal += room.subtotal;
                        html += `
                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h6 class="font-semibold text-gray-900">${room.roomName}</h6>
                                        <p class="text-sm text-gray-500">${room.roomCount} ${room.roomCount === 1 ? 'room' : 'rooms'} × ${room.nights} ${room.nights === 1 ? 'night' : 'nights'}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xl font-bold text-gray-900">$${room.subtotal.toFixed(2)}</div>
                                        <div class="text-sm text-gray-500">$${room.pricePerNight.toFixed(2)} per night</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    bookingItemsContainer.innerHTML = html;
                    proceedBtn.disabled = false;
                }
                
                grandTotalEl.textContent = '$' + grandTotal.toFixed(2);
            }
            
            // Clear selection button
            document.getElementById('clear-selection-btn').addEventListener('click', function() {
                selectedRooms = {};
                document.querySelectorAll('.room-count-select').forEach(select => {
                    select.value = 0;
                    const roomType = select.dataset.roomType;
                    const subtotalEl = document.querySelector('.subtotal-' + roomType);
                    const totalEl = document.querySelector('.total-' + roomType);
                    
                    if (subtotalEl) subtotalEl.textContent = '$0.00';
                    if (totalEl) totalEl.textContent = '$0.00';
                });
                updateBookingSummary();
            });
            
            // Proceed to booking button
            document.getElementById('proceed-booking-btn').addEventListener('click', function() {
                if (Object.keys(selectedRooms).length === 0) {
                    alert('Please select at least one room to proceed with booking.');
                    return;
                }
                
                // Check if user is authenticated
                @auth('tourist')
                    // User is logged in, proceed to payment
                    const bookingData = {
                        hotel_id: data.hotel_id,
                        hotel_name: data.hotel_name,
                        check_in: data.check_in,
                        check_out: data.check_out,
                        nights: data.nights,
                        rooms: selectedRooms,
                        total: grandTotal
                    };
                    
                    // Create a form and submit it with booking data
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("tourist.payment.create") }}';
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Add booking data
                    const bookingInput = document.createElement('input');
                    bookingInput.type = 'hidden';
                    bookingInput.name = 'booking_data';
                    bookingInput.value = JSON.stringify(bookingData);
                    form.appendChild(bookingInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                @else
                    // User is not logged in, redirect to login
                    const loginUrl = '{{ route("login") }}';
                    const currentUrl = window.location.href;
                    const loginWithRedirect = loginUrl + '?redirect=' + encodeURIComponent(currentUrl);
                    
                    // Show message and redirect
                    this.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i>Login Required';
                    this.classList.remove('bg-green-600', 'hover:bg-green-700');
                    this.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = loginWithRedirect;
                    }, 1000);
                @endauth
            });
            
            // Enhanced "Add to Booking" buttons functionality
            document.querySelectorAll('.book-room-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const roomType = this.dataset.roomType;
                    const roomName = this.dataset.roomName;
                    const select = document.getElementById('room_count_' + roomType);
                    
                    // Check if user has selected a room count
                    if (!select.value || select.value === '0') {
                        // Show message to select room amount
                        this.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Please Select Room Amount';
                        this.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-indigo-600');
                        this.classList.add('bg-orange-500');
                        
                        // Highlight the room selector
                        select.style.border = '2px solid #f59e0b';
                        select.style.boxShadow = '0 0 0 3px rgba(245, 158, 11, 0.1)';
                        
                        // Reset button and selector after 3 seconds
                        setTimeout(() => {
                            this.innerHTML = '<i class="fas fa-plus mr-2"></i>Add to Booking';
                            this.classList.remove('bg-orange-500');
                            this.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-indigo-600');
                            
                            // Reset selector border
                            select.style.border = '';
                            select.style.boxShadow = '';
                        }, 3000);
                        
                        // Focus on the selector to guide user
                        select.focus();
                        
                        return; // Don't proceed with booking
                    }
                    
                    // If room count is selected, show success feedback
                    this.innerHTML = '<i class="fas fa-check mr-2"></i>Added to Booking';
                    this.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-indigo-600');
                    this.classList.add('bg-green-600');
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-plus mr-2"></i>Add to Booking';
                        this.classList.remove('bg-green-600');
                        this.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-indigo-600');
                    }, 2000);
                    
                    // Scroll to booking summary
                    setTimeout(() => {
                        document.getElementById('combined-booking-section').scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'nearest' 
                        });
                    }, 100);
                });
            });
        }, 200);
        
        // Scroll to results
        resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function showError(message) {
        resultsContainer.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl mb-4"></i>
                <h3 class="text-lg font-semibold text-red-800 mb-2">Error</h3>
                <p class="text-red-700">${message}</p>
            </div>
        `;
    }
});

// Helper function to scroll to search form
function scrollToSearch() {
    document.getElementById('availability-form').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'center' 
    });
    
    // Focus on check-in date
    setTimeout(() => {
        document.getElementById('check_in').focus();
    }, 500);
}
</script>

<!-- All Reviews Modal -->
<div id="allReviewsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">All Reviews</h3>
                    <div class="flex items-center space-x-2 mt-1">
                        <div class="flex text-yellow-400 text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($averageRating))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $averageRating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600">{{ $averageRating }}/5 ({{ $totalReviews }} {{ $totalReviews === 1 ? 'review' : 'reviews' }})</span>
                    </div>
                </div>
                <button onclick="closeAllReviewsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="allReviewsContent" class="p-6 overflow-y-auto max-h-[60vh]">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-blue-600 text-2xl mb-2"></i>
                    <p class="text-gray-600">Loading reviews...</p>
                </div>
            </div>
            
            <div id="reviewsPagination" class="hidden p-4 border-t border-gray-200">
                <!-- Pagination will be inserted here -->
            </div>
        </div>
    </div>
</div>

<!-- Reviews JavaScript -->
<script>
    let currentReviewPage = 1;
    let isLoadingReviews = false;

    function openAllReviewsModal() {
        document.getElementById('allReviewsModal').classList.remove('hidden');
        loadAllReviews(1);
    }

    function closeAllReviewsModal() {
        document.getElementById('allReviewsModal').classList.add('hidden');
        document.getElementById('allReviewsContent').innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-blue-600 text-2xl mb-2"></i>
                <p class="text-gray-600">Loading reviews...</p>
            </div>
        `;
        document.getElementById('reviewsPagination').classList.add('hidden');
    }

    async function loadAllReviews(page = 1) {
        if (isLoadingReviews) return;
        
        isLoadingReviews = true;
        currentReviewPage = page;

        try {
            const response = await fetch(`/explore/hotels/{{ $hotel->id }}/reviews?page=${page}`);
            const data = await response.json();

            if (data.success) {
                renderAllReviews(data.reviews);
                renderPagination(data.pagination);
            } else {
                throw new Error('Failed to load reviews');
            }
        } catch (error) {
            console.error('Error loading reviews:', error);
            document.getElementById('allReviewsContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl mb-2"></i>
                    <p class="text-gray-600">Failed to load reviews. Please try again.</p>
                    <button onclick="loadAllReviews(${page})" class="mt-2 text-blue-600 hover:text-blue-700 underline">Retry</button>
                </div>
            `;
        } finally {
            isLoadingReviews = false;
        }
    }

    function renderAllReviews(reviews) {
        const content = document.getElementById('allReviewsContent');
        
        if (reviews.length === 0) {
            content.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-star text-gray-300 text-2xl mb-2"></i>
                    <p class="text-gray-600">No reviews found.</p>
                </div>
            `;
            return;
        }

        const reviewsHtml = reviews.map(review => {
            const starsHtml = Array.from({length: 5}, (_, i) => {
                return i < review.rating 
                    ? '<i class="fas fa-star text-yellow-400"></i>'
                    : '<i class="far fa-star text-yellow-400"></i>';
            }).join('');

            const reviewDate = new Date(review.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });

            return `
                <div class="border border-gray-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-600">${review.tourist.name.charAt(0)}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">${review.tourist.name}</p>
                                <div class="flex text-sm">${starsHtml}</div>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">${reviewDate}</span>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">${review.title}</h4>
                    <p class="text-gray-600 leading-relaxed">${review.description}</p>
                </div>
            `;
        }).join('');

        content.innerHTML = reviewsHtml;
    }

    function renderPagination(pagination) {
        const paginationDiv = document.getElementById('reviewsPagination');
        
        if (pagination.last_page <= 1) {
            paginationDiv.classList.add('hidden');
            return;
        }

        let paginationHtml = '<div class="flex justify-center items-center space-x-2">';
        
        // Previous button
        if (pagination.current_page > 1) {
            paginationHtml += `
                <button onclick="loadAllReviews(${pagination.current_page - 1})" 
                        class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50">
                    Previous
                </button>
            `;
        }

        // Page numbers
        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

        for (let i = startPage; i <= endPage; i++) {
            const isActive = i === pagination.current_page;
            paginationHtml += `
                <button onclick="loadAllReviews(${i})" 
                        class="px-3 py-1 text-sm border rounded ${isActive 
                            ? 'bg-blue-600 text-white border-blue-600' 
                            : 'border-gray-300 hover:bg-gray-50'}">
                    ${i}
                </button>
            `;
        }

        // Next button
        if (pagination.current_page < pagination.last_page) {
            paginationHtml += `
                <button onclick="loadAllReviews(${pagination.current_page + 1})" 
                        class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50">
                    Next
                </button>
            `;
        }

        paginationHtml += '</div>';
        paginationDiv.innerHTML = paginationHtml;
        paginationDiv.classList.remove('hidden');
    }
</script>

</body>
</html>
