<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer List | TripMate</title>
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

            <!-- NEW: Location Management link -->
            <a href="{{ route('admin.locations.index') }}"
            class="{{ request()->routeIs('admin.locations.*') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
                Locations
            </a>

            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Hotels (coming soon)</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Bookings</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Reviews</span>
        </div>

        <!-- Customers Content -->
        <div class="flex-1 p-10">
            <h2 class="text-2xl font-bold mb-6">Customer List</h2>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4 border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <table class="min-w-full bg-white border border-gray-300 rounded-xl overflow-hidden shadow">
                <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Customer ID</th>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Mobile</th>
                        <th class="px-6 py-4 text-left">Location</th>
                        <th class="px-6 py-4 text-left">Bookings</th>
                        <th class="px-6 py-4 text-left">Total Spent</th>
                        <th class="px-6 py-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 text-sm">
                    @forelse($customers as $customer)
                        <tr class="border-t hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 font-medium">{{ $customer->customer_id }}</td>
                            <td class="px-6 py-4">{{ $customer->name }}</td>
                            <td class="px-6 py-4">{{ $customer->email }}</td>
                            <td class="px-6 py-4">{{ $customer->mobile ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $customer->location ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $customer->bookings_count }}</td>
                            <td class="px-6 py-4">{{ $customer->total_spent }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.customers.delete', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
<!-- Add Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
