<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer List | TripMate</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-300 min-h-screen">
    <div class="mt-16"> <!-- Added wrapper with top margin -->

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
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-200 fixed top-0 left-0 h-full p-4 space-y-4 text-sm font-medium pt-20 overflow-y-auto hidden md:block">
      <a href="{{ route('admin.dashboard') }}"
         class="{{ request()->routeIs('admin.dashboard') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
        Dashboard
      </a>            <a href="{{ route('admin.customers') }}"
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

            <a href="{{ route('admin.hotels.index') }}"
                class="{{ request()->routeIs('admin.hotels.*') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
                Hotels
            </a>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Bookings</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Reviews</span>
        </div>

        <!-- Mobile Sidebar Toggle -->
        <button class="md:hidden fixed top-4 left-4 z-50 bg-gray-800 text-white p-2 rounded-lg" onclick="toggleSidebar()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Mobile Sidebar -->
        <div id="mobileSidebar" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()">
            <div class="w-64 bg-gray-200 h-full p-4 space-y-4 text-sm font-medium pt-20" onclick="event.stopPropagation()">
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
                <a href="{{ route('admin.hotels.index') }}"
                   class="{{ request()->routeIs('admin.hotels.*') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
                    Hotels
                </a>
                <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Bookings</span>
                <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Reviews</span>
            </div>
        </div>

        <!-- Customers Content -->
        <div class="flex-1 md:ml-64 p-4 md:p-10 pt-32">
            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-lg shadow-sm mb-6">
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <h2 class="text-xl md:text-2xl font-bold">Customer Management</h2>
                        <!-- Filter pills -->
                        
                    </div>
                    <!-- Search -->
                    <div class="flex items-center gap-3">
                        <form method="GET" class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search Customer..."
                                   class="pl-9 pr-3 py-2 w-full sm:w-64 rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400"
                            />
                            <svg class="w-5 h-5 text-gray-400 absolute left-2.5 top-2.5" viewBox="0 0 24 24" fill="none">
                                <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </form>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-4 rounded mb-4 border border-green-300">
                        {{ session('success') }}
                    </div>
                @endif

                
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-xl overflow-hidden shadow">
                    <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
                        <tr>
                            <th class="px-4 md:px-6 py-4 text-left">Customer ID</th>
                            <th class="px-4 md:px-6 py-4 text-left">Name</th>
                            <th class="hidden md:table-cell px-6 py-4 text-left">Email</th>
                            <th class="hidden md:table-cell px-6 py-4 text-left">Mobile</th>
                            <th class="hidden md:table-cell px-6 py-4 text-left">Location</th>
                            <th class="px-4 md:px-6 py-4 text-left">Bookings</th>
                            <th class="hidden md:table-cell px-6 py-4 text-left">Total Spent</th>
                            <th class="px-4 md:px-6 py-4 text-left">Action</th>
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

    <!-- Mobile Sidebar Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            sidebar.classList.toggle('hidden');
        }
    </script>
</body>
</html>