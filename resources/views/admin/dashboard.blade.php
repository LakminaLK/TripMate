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
        <div class="flex items-center space-x-4">
            <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" class="text-red-600 hover:underline">Logout</button>
</form>

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
               class="{{ request()->routeIs('admin.activities.index') ? 'bg-white font-semibold' : '' }} block px-2 py-1 hover:bg-gray-100 rounded">
                Activities
            </a>

            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Hotels (coming soon)</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Bookings</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Reviews</span>
        </div>


        <!-- Dashboard Content -->
        <div class="flex-1 p-10">
            <h2 class="text-xl font-bold text-center mb-6">Overview</h2>
            <div class="grid grid-cols-3 gap-4 text-center text-gray-800">
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

</body>
</html>
