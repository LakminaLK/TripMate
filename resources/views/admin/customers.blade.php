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

            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Hotels (coming soon)</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Locations</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Tour Types</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Bookings</span>
            <span class="block px-2 py-1 text-gray-400 cursor-not-allowed">Reviews</span>
        </div>

        <!-- Customers Content -->
        <div class="flex-1 p-10">
            <h2 class="text-2xl font-bold mb-6">Customer List</h2>

            <table class="min-w-full bg-white border border-gray-300 rounded overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">Customer ID</th>
                        <th class="p-4 text-left">Name</th>
                        <th class="p-4 text-left">Email</th>
                        <th class="p-4 text-left">Mobile</th> {{-- ✅ New --}}
                        <th class="p-4 text-left">Location</th>
                        <th class="p-4 text-left">Bookings</th>
                        <th class="p-4 text-left">Total Spent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr class="border-t">
                            <td class="p-4">{{ $customer->customer_id }}</td>
                            <td class="p-4">{{ $customer->name }}</td>
                            <td class="p-4">{{ $customer->email }}</td>
                            <td class="p-4">{{ $customer->mobile ?? 'N/A' }}</td> {{-- ✅ New --}}
                            <td class="p-4">{{ $customer->location ?? 'N/A' }}</td>
                            <td class="p-4">{{ $customer->bookings_count }}</td>
                            <td class="p-4">{{ $customer->total_spent }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
