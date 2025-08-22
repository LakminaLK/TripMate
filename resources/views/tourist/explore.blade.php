<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore | TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #667eea; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #764ba2; }
    </style>
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col">

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
                <a href="#emergency" 
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

<!-- Hero + filters -->
<section class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white relative overflow-hidden mt-[72px]">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="max-w-7xl mx-auto px-6 py-16 relative z-10">
        <div class="animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Explore Activities</h1>
            <p class="text-white/90 text-lg max-w-2xl">Discover amazing experiences and activities across beautiful destinations. Search, filter by location, and find the perfect adventure for you.</p>
        </div>

        <form method="GET" x-data="{ location: '{{ $location ?? '' }}' }" 
              class="mt-8 grid grid-cols-1 md:grid-cols-12 gap-4 animate-slide-up" 
              x-init="setTimeout(() => $el.classList.add('opacity-100'), 100)">
      <div class="md:col-span-5 relative">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search activities…"
               class="w-full rounded-xl border-0 px-4 py-3 focus:ring-2 focus:ring-white/60 text-gray-800" />
        <svg class="w-5 h-5 absolute right-3 top-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z"/>
        </svg>
      </div>

      <div class="md:col-span-4">
        <select name="location" class="w-full rounded-xl border-0 px-4 py-3 text-gray-800 focus:ring-2 focus:ring-white/60">
          <option value="">All locations</option>
          @foreach($locations as $loc)
            <option value="{{ $loc->id }}" @selected(($location ?? null) == $loc->id)>{{ $loc->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="md:col-span-2">
        <select name="sort" class="w-full rounded-xl border-0 px-4 py-3 text-gray-800 focus:ring-2 focus:ring-white/60">
          <option value="">Sort: Latest</option>
          <option value="price_asc"  @selected(($priceSort ?? '')==='price_asc')>Price: Low → High</option>
          <option value="price_desc" @selected(($priceSort ?? '')==='price_desc')>Price: High → Low</option>
        </select>
      </div>

      <div class="md:col-span-1">
        <button class="w-full rounded-xl bg-white text-blue-700 font-semibold px-4 py-3 hover:bg-blue-50">
          Go
        </button>
      </div>
    </form>
  </div>
</section>

<!-- Grid -->
<main class="max-w-7xl mx-auto px-6 -mt-8 pb-16">
    @if($activities->count())
        <div x-data="{ 
            init() {
                let observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('opacity-100', 'translate-y-0');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });

                document.querySelectorAll('.activity-card').forEach(card => {
                    observer.observe(card);
                    card.classList.add('opacity-0', 'translate-y-4', 'transition-all', 'duration-700');
                });
            }
        }" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($activities as $a)
                @php
                    $raw  = $a->image;
                    $path = $raw ? (strpos($raw, 'public/') === 0 ? substr($raw, 7) : $raw) : null;
                    $img  = $path
                        ? (preg_match('#^https?://#', $path) || strpos($path, '/') === 0
                            ? $path
                            : asset('storage/'.ltrim($path, '/')))
                        : asset('images/placeholder.jpg');
                @endphp

                <article class="activity-card bg-white rounded-2xl shadow-lg hover:shadow-xl ring-1 ring-black/5 overflow-hidden group animate-scale">
                    <a href="{{ route('tourist.activity.show', $a->id) }}" class="block">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $img }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500" 
                                alt="{{ $a->name }}">
                            @if(!is_null($a->price))
                                <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur rounded-full px-3 py-1 text-sm font-semibold transform group-hover:scale-105 transition-transform">
                                    ${{ number_format($a->price, 2) }}
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $a->name }}</h3>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $a->description }}</p>
                            <div class="mt-3 flex items-center justify-between">
                                <div class="text-xs text-gray-500">
                                    {{ $a->locations->pluck('name')->take(2)->join(', ') }}
                                    @if($a->locations->count() > 2) +{{ $a->locations->count() - 2 }} @endif
                                </div>
                                <span class="text-blue-600 text-sm font-medium">View Details →</span>
                            </div>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $activities->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl p-8 text-center shadow ring-1 ring-black/5 animate-fade-in">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p class="text-gray-600">No activities found. Try adjusting your filters.</p>
            <a href="{{ route('tourist.explore') }}" class="mt-4 inline-block text-blue-600 hover:underline">Clear all filters</a>
        </div>
    @endif
</main>

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

<script>
    // Smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Add scroll-triggered animations
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.animate-on-scroll');
        elements.forEach(el => {
            const rect = el.getBoundingClientRect();
            const isVisible = rect.top <= window.innerHeight * 0.8;
            if (isVisible) {
                el.classList.add('animate-fade-in');
            }
        });
    };

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Initial check
</script>

</body>
</html>
