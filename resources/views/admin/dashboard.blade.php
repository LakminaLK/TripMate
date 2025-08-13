<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | TripMate</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-300 min-h-screen">

    <!-- Top Navbar -->
    <div class="bg-white py-4 px-6 flex justify-between items-center shadow">
        <h1 class="text-2xl font-bold">TripMate</h1>
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
                <a href="{{ route('admin.profile.edit') }}" 
                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                
                <form method="POST" action="{{ route('admin.logout') }}">
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
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-200 h-screen p-4 space-y-4 text-sm font-medium">
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
                Dashboard
            </a>

            <a href="{{ route('admin.customers') }}"
               class="{{ request()->routeIs('admin.customers') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
                Customers
            </a>

            <a href="{{ route('admin.activities.index') }}"
               class="{{ request()->routeIs('admin.activities.*') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
                Activities
            </a>

            <a href="{{ route('admin.locations.index') }}"
               class="{{ request()->routeIs('admin.locations.*') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
                Locations
            </a>

            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Hotels (coming soon)</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Bookings</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Reviews</span>
        </div>

        <!-- Dashboard Content -->
        <div class="flex-1 p-10">
            <h2 class="text-xl font-bold text-center mb-6">Overview</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-center text-gray-800">
                @foreach ([
                    'Total Hotels' => 234,
                    'Total Bookings' => 833,
                    'Total Tour Types' => 833,
                    'Total Locations' => 133,
                    'Total Customers' => 355,
                    'Total Reviews' => 833
                ] as $label => $count)
                    <div class="bg-white p-4 rounded border">
                        <div class="text-lg font-semibold">{{ $label }}</div>
                        <div class="text-2xl mt-1">{{ $count }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Sales Chart Placeholder -->
            <h3 class="text-lg font-semibold text-center mt-10 mb-4">Sales Chart</h3>
            <div class="bg-white p-6 rounded shadow-md">
                <img src="https://via.placeholder.com/800x300?text=Sales+Chart" class="w-full" alt="Chart">
            </div>
        </div>
    </div>
<!-- Add Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
