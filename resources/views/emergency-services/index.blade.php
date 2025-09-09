<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Services - TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
    <style>
        [x-cloak] { display: none !important; }
        #map { height: 500px; width: 100%; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        

         /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #667eea; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #764ba2; }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Professional animations */
        .fade-in { animation: fadeIn 0.8s ease-out forwards; opacity: 0; }
        .slide-up { animation: slideUp 0.8s ease-out forwards; opacity: 0; transform: translateY(30px); }
        .slide-in-left { animation: slideInLeft 0.8s ease-out forwards; opacity: 0; transform: translateX(-50px); }
        .scale-in { animation: scaleIn 0.6s ease-out forwards; opacity: 0; transform: scale(0.9); }
        .float { animation: float 3s ease-in-out infinite; }
        
        /* Service type badges */
        .service-badge {
            padding: 0.5rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            white-space: nowrap;
            min-width: fit-content;
            text-align: center;
            line-height: 1.2;
        }
        
        .service-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .hospital-badge { background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); }
        .police-badge { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); }
        .fire-badge, .fire_station-badge { background: linear-gradient(135deg, #fb923c 0%, #f97316 100%); }
        .pharmacy-badge { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); }
        .ambulance-badge { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); }
        
        /* Cards and Containers */
        .service-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Smooth transitions */
        .transition-all { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .hover-scale:hover { transform: scale(1.02); }
        
        /* Professional gradients */
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass { 
            backdrop-filter: blur(16px); 
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Animations */
        @keyframes fadeIn { to { opacity: 1; } }
        @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }
        @keyframes slideInLeft { to { opacity: 1; transform: translateX(0); } }
        @keyframes scaleIn { to { opacity: 1; transform: scale(1); } }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        
        /* Enhanced Professional Styling */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .slider::-webkit-slider-thumb {
            appearance: none;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.4);
        }
        
        /* Simple, clean dropdown styling */
        select {
            background-image: none !important;
        }
        
        select:focus {
            outline: none;
        }
        
        /* Simple slider styling */
        .slider::-webkit-slider-thumb {
            appearance: none;
            height: 18px;
            width: 18px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
        }
        
        .slider::-moz-range-thumb {
            height: 18px;
            width: 18px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
        }
        
        .service-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
            border-color: rgba(59, 130, 246, 0.2);
        }
        
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ef4444, #10b981);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .service-card:hover::before {
            opacity: 1;
        }
        
        /* Loading animations */
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        @keyframes slideInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        
        .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        .animate-slide-in-up { animation: slideInUp 0.6s ease-out; }
        
        /* Counter Animation */
        .counter-number {
            font-variant-numeric: tabular-nums;
            transition: all 0.3s ease;
        }
        
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-count-up {
            animation: countUp 0.8s ease-out;
        }
    </style>
</head>
@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
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
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-user-circle mr-3 text-blue-600"></i>
                                    View Profile
                                </a>
                                <a href="#bookings" 
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
                    <a href="{{ route('login') }}" 
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

<body class="bg-gray-50 min-h-screen pt-20">
    <!-- Hero Section -->
    <section class="relative h-[60vh] bg-cover bg-center flex items-center justify-center text-white mb-12 fade-in"
             style="background-image: linear-gradient(135deg, rgba(37, 99, 235, 0.8), rgba(79, 70, 229, 0.8)), url('/images/emergency.jpg');">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/80 via-indigo-600/80 to-purple-700/80"></div>
        
        <!-- Background decorative elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl float"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl float" style="animation-delay: 1.5s;"></div>
        </div>

        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto slide-up">
                <!-- <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-6 py-2 mb-6">
                    <i class="fas fa-shield-alt text-red-400 mr-2"></i>
                    <span class="text-sm font-medium">Emergency Services Locator</span>
                </div> -->
            
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                Find Emergency Services 
                <span class="bg-gradient-to-r from-yellow-400 to-red-400 bg-clip-text text-transparent">
                    Near You
                </span>
            </h1>
            
            <p class="text-xl md:text-2xl mb-8 text-white/90 max-w-3xl mx-auto leading-relaxed">
                Quick access to hospitals, police stations, fire departments, and pharmacies in your area. 
                Emergency help is just a click away.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 max-w-md mx-auto">
                <a href="#services" 
                   onclick="event.preventDefault(); smoothScrollTo('services');" 
                   class="bg-white text-blue-600 px-8 py-4 rounded-full font-semibold hover:bg-blue-50 transition-all duration-500 transform hover:scale-105 shadow-xl flex items-center justify-center hover:shadow-2xl group">
                    <i class="fas fa-search mr-3 transition-transform duration-300 group-hover:scale-110"></i> Find Services
                </a>
                <a href="#map-section" 
                   onclick="event.preventDefault(); smoothScrollTo('map-section');" 
                   class="bg-white/20 backdrop-blur-sm border-2 border-white/30 text-white px-8 py-4 rounded-full font-semibold hover:bg-white/30 transition-all duration-500 flex items-center justify-center hover:border-white/50 hover:shadow-lg group">
                    <i class="fas fa-map-marker-alt mr-3 transition-transform duration-300 group-hover:scale-110"></i> View Map
                </a>
            </div>

            <!-- Quick Stats -->
            <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-2xl mx-auto">
                <div class="text-center">
                    <div class="text-3xl font-bold text-white counter-number animate-count-up" data-target="{{ count($services->where('type', 'hospital')) > 0 ? count($services->where('type', 'hospital')) : 1500 }}">0</div>
                    <div class="text-white/80 text-sm">Hospitals</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white counter-number animate-count-up" data-target="{{ count($services->where('type', 'police')) > 0 ? count($services->where('type', 'police')) : 600 }}">0</div>
                    <div class="text-white/80 text-sm">Police Stations</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white counter-number animate-count-up" data-target="{{ count($services->whereIn('type', ['fire_station', 'fire'])) > 0 ? count($services->whereIn('type', ['fire_station', 'fire'])) : 30 }}">0</div>
                    <div class="text-white/80 text-sm">Fire Stations</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white counter-number animate-count-up" data-target="{{ count($services->where('type', 'pharmacy')) > 0 ? count($services->where('type', 'pharmacy')) : 3100 }}">0</div>
                    <div class="text-white/80 text-sm">Pharmacies</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Filter Section -->
        <div class="mb-12 scale-in" id="services">
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-3">Emergency Services Filter</h2>
                    <p class="text-gray-600">Filter emergency services by type and location to find what you need quickly.</p>
                </div>

                <form id="searchForm" action="{{ route('emergency-services.index') }}" method="GET" class="space-y-6">
                    <!-- Filter Options -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">
                            <!-- Service Type -->
                            <div class="space-y-3">
                                <label for="type" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-hospital mr-2 text-blue-500"></i>Service Type
                                </label>
                                <div class="relative">
                                    <select name="type" id="type" 
                                            class="w-full rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 py-3 px-4 pr-10 bg-white appearance-none cursor-pointer transition-all duration-200 text-gray-700 font-normal">
                                        <option value="">All Services</option>
                                        <option value="hospital" {{ $selectedType === 'hospital' ? 'selected' : '' }}>🏥 Hospitals</option>
                                        <option value="police" {{ $selectedType === 'police' ? 'selected' : '' }}>👮 Police Stations</option>
                                        <option value="fire_station" {{ $selectedType === 'fire_station' ? 'selected' : '' }}>🚒 Fire Stations</option>
                                        <option value="pharmacy" {{ $selectedType === 'pharmacy' ? 'selected' : '' }}>💊 Pharmacies</option>
                                        <option value="ambulance" {{ $selectedType === 'ambulance' ? 'selected' : '' }}>🚑 Ambulance Services</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Radius Slider -->
                            <div class="space-y-3">
                                <label for="radius" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>Search Radius
                                </label>
                                <div class="bg-white rounded-xl p-4 border border-gray-300">
                                    <input type="range" name="radius" id="radius" 
                                           value="{{ $radius ?? 5 }}" min="1" max="50" step="1"
                                           oninput="updateRadius(this.value)"
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider">
                                    <div class="flex justify-between text-sm text-gray-500 mt-2">
                                        <span>1 km</span>
                                        <div class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs">
                                            <span id="radiusValue">{{ $radius ?? 5 }}</span> km
                                        </div>
                                        <span>50 km</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center mt-6">
                            <button type="submit" 
                                    class="inline-flex justify-center items-center px-8 py-3 rounded-xl text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 shadow-sm">
                                <i class="fas fa-filter mr-2"></i>
                                Apply Filters
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="latitude" id="latitude" value="{{ isset($currentLat) ? $currentLat : '' }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ isset($currentLng) ? $currentLng : '' }}">
                </form>
            </div>

            <!-- Location Info with enhanced styling -->
            <div id="locationInfo" class="mb-8 {{ isset($currentLat) ? '' : 'hidden' }}">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 p-6 rounded-2xl flex items-center shadow-lg">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-location-arrow text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-semibold text-blue-900 text-lg mb-1">Location Detected</h3>
                        <p class="text-blue-700">
                            Showing emergency services near your current location. 
                            <!-- <span class="font-medium">{{ count($services) }} services found</span> -->
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <button onclick="updateLocationAndSearch()" 
                                class="bg-blue-500 text-white px-6 py-3 rounded-xl hover:bg-blue-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 font-medium">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Update Location
                        </button>
                    </div>
                </div>
            </div>
        </div>

            <!-- Map Section -->
            <!-- Services Section -->
            <div class="grid lg:grid-cols-3 gap-6" id="map-section">
                <!-- Map Container -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                        <div id="map" style="height: 500px; width: 100%; border-radius: 0.5rem;"></div>
                    </div>
                </div>

                <!-- Services List -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold mb-4">Nearby Emergency Services</h3>
                        <div id="servicesContainer" class="space-y-4 max-h-[450px] overflow-y-auto">
                            <!-- Services will be dynamically added here -->
                        </div>
                    </div>
                </div>
            </div>
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

    <!-- Scripts -->
    <script src="{{ asset('js/maps.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places,geometry&callback=initMap" async defer></script>
    <script>
        // Smooth scroll function
        function smoothScrollTo(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Add a subtle highlight effect to the target section
                element.style.transition = 'all 0.6s ease-in-out';
                element.style.transform = 'scale(1.02)';
                element.style.boxShadow = '0 20px 40px rgba(59, 130, 246, 0.1)';
                
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                    element.style.boxShadow = '';
                }, 600);
            }
        }

        function updateRadius(value) {
            document.getElementById('radiusValue').textContent = value;
            // Only update the display, no automatic search
        }

        function updateLocationAndSearch() {
            // Show loading indicator
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
            button.disabled = true;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                        
                        // Update the map center and search for nearby places
                        if (typeof google !== 'undefined' && window.map) {
                            const newCenter = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                            window.map.setCenter(newCenter);
                            if (typeof searchNearbyPlaces === 'function') {
                                searchNearbyPlaces();
                            }
                        }
                        
                        // Submit form to refresh the list
                        document.getElementById('searchForm').submit();
                    },
                    function(error) {
                        alert('Error getting your location: ' + error.message);
                        // Reset button state
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else {
                alert('Geolocation is not supported by your browser');
                // Reset button state
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        }

        // Initial location check on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (!document.getElementById('latitude').value || !document.getElementById('longitude').value) {
                updateLocationAndSearch();
            }
            
            // Initialize counter animations
            initCounterAnimations();
        });
        
        // Professional counter animation
        function initCounterAnimations() {
            const counters = document.querySelectorAll('.counter-number');
            
            const animateCounter = (counter) => {
                const target = parseInt(counter.getAttribute('data-target'));
                const duration = 2000; // 2 seconds
                const step = target / (duration / 16); // 60fps
                let current = 0;
                
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                        counter.textContent = target + '+';
                    } else {
                        counter.textContent = Math.floor(current);
                    }
                }, 16);
            };
            
            // Start animations with delay
            counters.forEach((counter, index) => {
                setTimeout(() => {
                    animateCounter(counter);
                }, index * 200); // Stagger animation by 200ms
            });
        }
    </script>
</body>
</html>
