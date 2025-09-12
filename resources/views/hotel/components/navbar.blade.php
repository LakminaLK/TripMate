<!-- Top Navbar -->
<div class="bg-white dark:bg-dark-100 h-20 px-6 flex justify-between items-center shadow-lg fixed top-0 w-full z-30 border-b border-gray-100 dark:border-dark-200 transition-colors duration-300">
    <!-- Logo + Menu Area -->
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Button -->
        <button class="md:hidden p-2 rounded-lg hover:bg-gradient-to-r hover:from-green-50 hover:to-teal-50 dark:hover:from-dark-200 dark:hover:to-dark-300 transition-all duration-200" onclick="toggleSidebar()">
            <svg class="w-6 h-6 text-gray-700 dark:text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center">
                <img src="{{ asset('images/tm1.png') }}" alt="TripMate Logo" class="h-8 w-8">
            </div>
            <h1 class="text-2xl font-bold text-black dark:text-white">TripMate</h1>
            <span class="text-sm bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-2 py-1 rounded-full font-medium">Hotel</span>
        </div>
    </div>

    <!-- Right Side - Notifications and Profile -->
    <div class="flex items-center gap-3">
        <!-- Notifications Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <!-- Notification Icon Button -->
            <button @click="open = !open" 
                    class="relative inline-flex items-center justify-center w-12 h-12 rounded-full hover:bg-gray-100 dark:hover:bg-dark-200 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-dark-400 focus:ring-offset-2 transition-all duration-200">
                <i class="fas fa-bell text-gray-600 dark:text-dark-500 text-lg"></i>
                <!-- Notification Badge -->
                <span class="absolute top-2 right-2 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                    3
                </span>
            </button>

            <!-- Notification Dropdown -->
            <div x-show="open" 
                @click.away="open = false" 
                x-transition 
                class="absolute right-0 mt-2 w-80 bg-white dark:bg-dark-100 rounded-xl shadow-xl border border-gray-100 dark:border-dark-200 z-50 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-dark-200">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-dark-700">Notifications</h3>
                    <p class="text-xs text-gray-500 dark:text-dark-400">You have 3 new notifications</p>
                </div>
                
                <div class="max-h-96 overflow-y-auto">
                    <!-- New Booking Notification -->
                    <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-dark-200 transition-colors border-b border-gray-50 dark:border-dark-200">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-dark-700">New Booking Received</p>
                                <p class="text-xs text-gray-600 dark:text-dark-500">Booking #BK0012 from John Doe</p>
                                <p class="text-xs text-gray-400 dark:text-dark-400 mt-1">2 minutes ago</p>
                            </div>
                            <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>
                        </div>
                    </div>
                    
                    <!-- New Review Notification -->
                    <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-dark-200 transition-colors border-b border-gray-50 dark:border-dark-200">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                <i class="fas fa-star text-yellow-600 dark:text-yellow-400 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-dark-700">New Review Posted</p>
                                <p class="text-xs text-gray-600 dark:text-dark-500">5-star review from Sarah Wilson</p>
                                <p class="text-xs text-gray-400 dark:text-dark-400 mt-1">1 hour ago</p>
                            </div>
                            <div class="w-2 h-2 bg-yellow-500 rounded-full flex-shrink-0"></div>
                        </div>
                    </div>
                    
                    <!-- Booking Status Update -->
                    <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-dark-200 transition-colors border-b border-gray-50 dark:border-dark-200">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-dark-700">Booking Confirmed</p>
                                <p class="text-xs text-gray-600 dark:text-dark-500">Payment received for Booking #BK0011</p>
                                <p class="text-xs text-gray-400 dark:text-dark-400 mt-1">3 hours ago</p>
                            </div>
                            <div class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0"></div>
                        </div>
                    </div>
                </div>
                
                <div class="px-4 py-3 border-t border-gray-100 dark:border-dark-200 bg-gray-50 dark:bg-dark-200">
                    <a href="#" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">View all notifications</a>
                </div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div x-data="{ open: false }" class="relative">
        <!-- Profile Icon Button -->
        <button @click="open = !open" 
                class="inline-flex items-center justify-center w-12 h-12 rounded-full hover:bg-gray-100 dark:hover:bg-dark-200 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-dark-400 focus:ring-offset-2 transition-all duration-200">
            <img src="{{ asset('images/Profile.png') }}" 
                alt="Profile" 
                class="w-8 h-8 rounded-full object-cover">
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
            @click.away="open = false" 
            x-transition 
            class="absolute right-0 mt-2 w-48 bg-white dark:bg-dark-100 rounded-xl shadow-xl border border-gray-100 dark:border-dark-200 z-50 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 dark:border-dark-200">
                <p class="text-sm font-medium text-gray-900 dark:text-dark-700">{{ auth('hotel')->user()->name ?? 'Hotel' }}</p>
            </div>
            
            <a href="{{ route('hotel.profile.edit') ?? '#' }}" 
            class="flex items-center px-4 py-3 text-gray-700 dark:text-dark-500 hover:bg-gradient-to-r hover:from-green-50 hover:to-teal-50 dark:hover:from-dark-200 dark:hover:to-dark-300 transition-all duration-200">
                <i class="fas fa-user mr-3 text-gray-500 dark:text-dark-400"></i>
                Profile
            </a>
            
            <!-- Dark Mode Toggle -->
            <div class="px-4 py-3 border-b border-gray-100 dark:border-dark-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-moon mr-3 text-gray-500 dark:text-dark-400"></i>
                        <span class="text-gray-700 dark:text-dark-500">Dark Mode</span>
                    </div>
                    <button @click="$store.darkMode.toggle(); console.log('Dark mode toggled:', $store.darkMode.on)" 
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                            :class="$store.darkMode.on ? 'bg-green-600' : 'bg-gray-200'">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                              :class="$store.darkMode.on ? 'translate-x-6' : 'translate-x-1'"></span>
                    </button>
                </div>
            </div>
            
            <form method="POST" action="{{ route('hotel.logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center text-left px-4 py-3 text-green-700 dark:text-green-400 hover:bg-green-50 dark:hover:bg-dark-200 transition-all duration-200">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
    </div>
</div>
