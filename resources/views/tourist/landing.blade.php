<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripMate - Your Ultimate Travel Companion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    </style>
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-white text-gray-800 font-sans">

@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
@endphp

<!-- ✅ Professional Navbar -->
<header x-data="{ isOpen: false, scrolled: false }" 
        @scroll.window="scrolled = window.pageYOffset > 50"
        :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg' : 'bg-transparent'"
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
                    <h1 :class="scrolled ? 'text-gray-900' : 'text-white'" 
                        class="text-xl font-bold transition-colors">
                        Trip<span class="text-blue-600">Mate</span>
                    </h1>
                    <p :class="scrolled ? 'text-gray-500' : 'text-white/70'" 
                       class="text-xs transition-colors">Your Travel Companion</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('landing') }}" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    Home
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#about" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    About
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="{{ route('tourist.explore') }}" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    Explore
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="{{ route('emergency-services.index') }}" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    Emergency
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#contact" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
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
                                :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                                class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-white/10 transition-all duration-300">
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
                       :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
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
                        :class="scrolled ? 'text-gray-700' : 'text-white'"
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

<!-- ✅ Hero Section -->
<section class="relative h-[70vh] bg-cover bg-center flex items-center justify-center text-white"
         style="background-image: url('/images/2.jpeg');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative z-10 text-center px-4" x-data x-init="$el.classList.add('animate-fade-in')">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-slide-up">
            Weaving your Dreams into Unforgettable Adventure
        </h1>
        <p class="max-w-2xl mx-auto mb-6 animate-fade-in">
            From beachside resorts to the most unique stays. See the top 1% of hotels from Traveler’s Choice.
        </p>
        <a href="{{ route('tourist.explore') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition animate-pop">
            See more...
        </a>
    </div>
</section>

<!-- ✅ Welcome Section -->
<section class="py-12 px-6 max-w-7xl mx-auto text-center">
    <h2 class="text-3xl font-bold mb-4">Welcome to Trip Mate</h2>
    <p class="text-gray-600 max-w-3xl mx-auto mb-8">
        Like you, we are travelers. Exploration runs in our blood. It’s who we are, and why we do what we do. We are passionate, curious and deeply committed to sustainably exploring our incredible world. Like you, we are part of a global community, excited to embrace and discover our planet, our home and uncover the rich cultures, histories, wildlife and natural beauty that make our travels so special. At Trip Mate, we create transformative travel experiences that fulfill that deep-seated urge for connecting and learning. So, ask yourself this – where will your passion for travel take you?
    </p>
    <!-- <a href="{{ route('tourist.explore') }}" class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Explore Now</a> -->
</section>

<!-- ✅ Popular Activities with Animation -->
<section class="max-w-7xl mx-auto px-6 pb-16" x-data="{ 
    shownActivities: [],
    init() {
        this.observeActivities();
    },
    observeActivities() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.shownActivities.push(entry.target.dataset.id);
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.activity-card').forEach(card => {
            observer.observe(card);
        });
    }
}">
    <!-- <div class="flex flex-col items-center text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            Discover Amazing Experiences
        </h2>
        <p class="text-gray-600 max-w-2xl mb-8">
            Explore our handpicked selection of unforgettable activities and create memories that last a lifetime.
        </p>
    </div> -->

    @php $list = ($homeActivities ?? collect())->take(6); @endphp
    @if($list->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach($list as $index => $a)
                @php
                    $raw = $a->image;
                    $path = $raw ? (strpos($raw, 'public/') === 0 ? substr($raw, 7) : $raw) : null;
                    $img = $path
                        ? (preg_match('#^https?://#', $path) || strpos($path, '/') === 0
                            ? $path
                            : asset('storage/'.ltrim($path, '/')))
                        : asset('images/placeholder.jpg');
                @endphp
                
                <a href="{{ route('tourist.activity.show', ['activity' => $a->id]) }}" 
                   class="activity-card group relative block rounded-2xl overflow-hidden shadow-lg transform transition-all duration-500 hover:shadow-2xl hover:-translate-y-2"
                   data-id="{{ $a->id }}"
                   :class="{ 'opacity-0 translate-y-8': !shownActivities.includes('{{ $a->id }}'), 'opacity-100 translate-y-0': shownActivities.includes('{{ $a->id }}') }"
                   style="transition-delay: {{ $index * 100 }}ms">
                    <!-- Image Container -->
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $img }}" 
                             alt="{{ $a->name }}"
                             class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
                        
                        <!-- Overlay with gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent opacity-70 transition-opacity group-hover:opacity-90"></div>

                        <!-- Price Badge -->
                        @if(!is_null($a->price))
                            <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full shadow-lg">
                                <span class="text-blue-600 font-semibold">${{ number_format($a->price, 2) }}</span>
                            </div>
                        @endif

                        <!-- Content overlay -->
                        <div class="absolute inset-0 p-6 flex flex-col justify-end text-white">
                            <h3 class="text-xl font-bold mb-2 transform transition-transform group-hover:-translate-y-2">
                                {{ $a->name }}
                            </h3>
                            <p class="text-white/90 text-sm line-clamp-2 transform transition-transform group-hover:-translate-y-2 transition-delay-75">
                                {{ $a->description }}
                            </p>

                            <!-- Animated arrow -->
                            <div class="mt-4 inline-flex items-center text-blue-400 transform translate-y-8 opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">
                                <span class="font-medium mr-2">Explore Locations</span>
                                <i class="fas fa-arrow-right transform transition-transform group-hover:translate-x-2"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="{{ route('tourist.explore') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full 
                      font-medium hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <span>Explore All Activities</span>
                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center shadow-lg">
            <i class="fas fa-hiking text-6xl text-blue-200 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Activities Available</h3>
            <p class="text-gray-600">We're working on adding exciting new activities. Check back soon!</p>
        </div>
    @endif
</section>

<!-- ✅ FAQs -->
<section class="bg-gray-50 py-12 px-6">
    <h3 class="text-2xl font-bold text-center mb-8">Frequently Asked Questions [FAQs]</h3>
    <div class="max-w-3xl mx-auto space-y-4">
        @foreach ([
            'How do I create a booking?',
            'What is the cancellation Policy?',
            'How can I find out more about Sri Lankan destinations?',
            'How do I contact you for support?'
        ] as $faq)
            <div x-data="{ open: false }" class="bg-white shadow rounded-md p-4">
                <button @click="open = !open" class="w-full text-left font-semibold">
                    {{ $faq }}
                </button>
                <p x-show="open" x-transition class="mt-2 text-sm text-gray-600">
                    Answer coming soon. You can update this later!
                </p>
            </div>
        @endforeach
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

<!-- ✅ Custom Tailwind Animations -->
<style>
    .animate-fade-in { animation: fadeIn 1s ease-out forwards; opacity: 0; }
    .animate-slide-up { animation: slideUp 1s ease-out forwards; opacity: 0; }
    .animate-pop { animation: pop 0.3s ease-out forwards; }

    @keyframes fadeIn { to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes pop { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
</style>

</body>
</html>
