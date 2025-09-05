<!-- Top Navbar -->
<div class="bg-white h-20 px-6 flex justify-between items-center shadow-lg fixed top-0 w-full z-30 border-b border-gray-100">
    <!-- Logo + Menu Area -->
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Button -->
        <button class="md:hidden p-2 rounded-lg hover:bg-gradient-to-r hover:from-amber-50 hover:to-yellow-50 transition-all duration-200" onclick="toggleSidebar()">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center">
                <img src="{{ asset('images/tm1.png') }}" alt="TripMate Logo" class="h-8 w-8">
            </div>
            <h1 class="text-2xl font-bold text-black">TripMate</h1>
        </div>
    </div>

    <!-- Profile Dropdown -->
    <div x-data="{ open: false }" class="relative">
        <!-- Profile Icon Button -->
        <button @click="open = !open" 
                class="inline-flex items-center justify-center w-12 h-12 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200">
            <img src="{{ asset('images/Profile.png') }}" 
                alt="Profile" 
                class="w-8 h-8 rounded-full object-cover">
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
            @click.away="open = false" 
            x-transition 
            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
            <a href="{{ route('admin.profile.edit') }}" 
            class="flex items-center px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-amber-50 hover:to-yellow-50 transition-all duration-200">
                <i class="fas fa-user mr-3 text-gray-500"></i>
                Profile
            </a>
            
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center text-left px-4 py-3 text-amber-700 hover:bg-amber-50 transition-all duration-200 border-t border-gray-100">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
