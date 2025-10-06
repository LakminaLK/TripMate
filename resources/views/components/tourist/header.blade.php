@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
@endphp

<!-- ✅ Professional Navbar with Scroll Animation -->
<header x-data="{ 
            isOpen: false, 
            scrolled: false, 
            lastScrollY: 0, 
            scrollDirection: 'up',
            headerVisible: true 
        }" 
        x-cloak
        @scroll.window="
            let currentScrollY = window.pageYOffset;
            scrolled = currentScrollY > 50;
            
            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                // Scrolling down
                scrollDirection = 'down';
                headerVisible = false;
                isOpen = false; // Close mobile menu when hiding header
            } else if (currentScrollY < lastScrollY) {
                // Scrolling up
                scrollDirection = 'up';
                headerVisible = true;
            }
            
            lastScrollY = currentScrollY;
        "
        :class="{
            'bg-white/95 backdrop-blur-md shadow-lg': scrolled || !{{ $transparent ?? false ? 'true' : 'false' }},
            'bg-transparent': !scrolled && {{ $transparent ?? false ? 'true' : 'false' }},
            '-translate-y-full': !headerVisible,
            'translate-y-0': headerVisible
        }"
        class="fixed top-0 w-full z-50 transition-all duration-500 ease-in-out transform">
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
                    <h1 :class="(scrolled || !{{ $transparent ?? false ? 'true' : 'false' }}) ? 'text-gray-900' : 'text-white'" 
                        class="text-xl font-bold transition-colors">
                        Trip<span class="text-blue-600">Mate</span>
                    </h1>
                    <p :class="(scrolled || !{{ $transparent ?? false ? 'true' : 'false' }}) ? 'text-gray-500' : 'text-white/70'" 
                       class="text-xs transition-colors">Your Travel Companion</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('landing') }}" 
                   :class="(scrolled || !{{ $transparent ?? false ? 'true' : 'false' }}) ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    Home
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#about" 
                   :class="(scrolled || !{{ $transparent ?? false ? 'true' : 'false' }}) ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    About
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="{{ route('tourist.explore') }}" 
                   :class="(scrolled || !{{ $transparent ?? false ? 'true' : 'false' }}) ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    Explore
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="{{ route('emergency-services.index') }}" 
                   :class="(scrolled || !{{ $transparent ?? false ? 'true' : 'false' }}) ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    Emergency
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#contact" 
                   :class="(scrolled || !{{ $transparent ?? false ? 'true' : 'false' }}) ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    Contact us
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
            </nav>

            <!-- Auth Section -->
            <div class="flex items-center space-x-2">
                @if ($tourist)
                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" x-cloak class="relative" @click.away="open = false">
                        <button @click="open = !open"
                                :class="(scrolled || !{{ $transparent ?? false ? 'true' : 'false' }}) ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
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
                                <div class="border-t border-gray-100 mt-2 pt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="flex items-center w-full px-4 py-3 text-red-600 hover:bg-red-50 transition-colors">
                                            <i class="fas fa-sign-out-alt mr-3"></i>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Guest Actions - Hidden on Mobile, shown only on desktop -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}" 
                           :class="(scrolled || !{{ $transparent ?? false ? 'true' : 'false' }}) ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                           class="font-medium transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-medium">
                            Sign Up
                        </a>
                    </div>
                @endif

                <!-- Mobile Menu Button -->
                <button @click="isOpen = !isOpen" 
                        :class="(scrolled || !{{ $transparent ?? false ? 'true' : 'false' }}) ? 'text-gray-700' : 'text-white'"
                        class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-all duration-300">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="isOpen" x-transition class="md:hidden bg-white/95 backdrop-blur-md rounded-b-2xl shadow-xl border-t border-gray-100">
            <div class="px-4 py-6 space-y-3">
                <a href="{{ route('landing') }}" class="block text-gray-700 hover:text-blue-600 font-medium transition-colors py-2 px-2 rounded-lg hover:bg-blue-50">
                    <i class="fas fa-home mr-3 text-blue-600 w-5"></i>Home
                </a>
                <a href="#about" class="block text-gray-700 hover:text-blue-600 font-medium transition-colors py-2 px-2 rounded-lg hover:bg-blue-50">
                    <i class="fas fa-info-circle mr-3 text-blue-600 w-5"></i>About
                </a>
                <a href="{{ route('tourist.explore') }}" class="block text-gray-700 hover:text-blue-600 font-medium transition-colors py-2 px-2 rounded-lg hover:bg-blue-50">
                    <i class="fas fa-compass mr-3 text-blue-600 w-5"></i>Explore
                </a>
                <a href="{{ route('emergency-services.index') }}" class="block text-gray-700 hover:text-blue-600 font-medium transition-colors py-2 px-2 rounded-lg hover:bg-blue-50">
                    <i class="fas fa-ambulance mr-3 text-blue-600 w-5"></i>Emergency
                </a>
                <a href="#contact" class="block text-gray-700 hover:text-blue-600 font-medium transition-colors py-2 px-2 rounded-lg hover:bg-blue-50">
                    <i class="fas fa-envelope mr-3 text-blue-600 w-5"></i>Contact us
                </a>
                @if (!$tourist)
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}" 
                           class="block w-full text-center py-3 text-blue-600 hover:text-blue-800 font-medium transition-colors border border-blue-200 rounded-lg hover:bg-blue-50">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="block w-full text-center py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300 font-medium">
                            <i class="fas fa-user-plus mr-2"></i>Sign Up
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>