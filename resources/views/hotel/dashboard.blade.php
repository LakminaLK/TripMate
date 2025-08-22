<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Dashboard | TripMate</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-300 min-h-screen">
    <div class="mt-16">
    <!-- Top Navbar -->
    <div class="bg-white h-16 px-6 flex justify-between items-center shadow fixed top-0 w-full z-30">
        <!-- Logo + Menu Area -->
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Button -->
            <button class="md:hidden p-2 rounded-lg hover:bg-gray-100" onclick="toggleSidebar()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/tm1.png') }}" alt="TripMate Logo" class="h-8 w-8">
                <h1 class="text-2xl font-bold">TripMate</h1>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <!-- Profile Icon Button -->
            <button @click="open = !open" 
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 focus:outline-none">
                <img src="{{ asset('images/Profile.png') }}" 
                    alt="Profile" 
                    class="w-8 h-8 rounded-full object-cover">
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false" 
                 x-transition 
                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                <!-- ✅ Profile link now points to the correct route -->
                <a href="{{ route('hotel.profile.edit') }}" 
                   @click="open = false"
                   class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                
                <form action="{{ route('hotel.logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full text-left px-4 py-2 text-red-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="flex min-h-screen mt-16">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-200 fixed left-0 h-full p-4 space-y-4 text-sm font-medium pt-4 overflow-y-auto hidden md:block">
            <a href="#"
               class="bg-white font-semibold block px-2 py-1 hover:bg-gray-100 rounded">
                Dashboard
            </a>
            <a href="#"
               class="block px-2 py-1 hover:bg-gray-100 rounded">
                My Hotel Info
            </a>
            <a href="#"
               class="block px-2 py-1 hover:bg-gray-100 rounded">
                Bookings
            </a>
            <a href="#"
               class="block px-2 py-1 hover:bg-gray-100 rounded">
                Messages
            </a>
            <a href="#"
               class="block px-2 py-1 hover:bg-gray-100 rounded">
                Reviews
            </a>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobileSidebar" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()">
            <div class="w-64 bg-gray-200 h-full p-4 space-y-4 text-sm font-medium pt-20" onclick="event.stopPropagation()">
                <a href="#" class="bg-white font-semibold block px-2 py-1 hover:bg-gray-100 rounded">
                    Dashboard
                </a>
                <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">
                    My Hotel Info
                </a>
                <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">
                    Bookings
                </a>
                <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">
                    Messages
                </a>
                <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">
                    Reviews
                </a>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="flex-1 md:ml-64 p-4 md:p-10">
            <div class="mb-6 mt-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-lg shadow-sm mb-6">
                    <h2 class="text-xl md:text-2xl font-bold">Dashboard Overview</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-center text-gray-800">
                    <div class="bg-white p-4 rounded border">
                        <div class="text-lg font-semibold">Upcoming Bookings</div>
                        <div class="text-2xl mt-1">12</div>
                    </div>
                    <div class="bg-white p-4 rounded border">
                        <div class="text-lg font-semibold">Messages</div>
                        <div class="text-2xl mt-1">3</div>
                    </div>
                    <div class="bg-white p-4 rounded border">
                        <div class="text-lg font-semibold">Total Reviews</div>
                        <div class="text-2xl mt-1">24</div>
                    </div>
                    <div class="bg-white p-4 rounded border">
                        <div class="text-lg font-semibold">Rating</div>
                        <div class="text-2xl mt-1">4.5 ★</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div> <!-- Close wrapper -->
    <!-- Add Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            sidebar.classList.toggle('hidden');
        }
    </script>
</body>
</html>
