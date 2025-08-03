<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Dashboard | TripMate</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-300 min-h-screen">

    <!-- Top Navbar -->
    <div class="bg-white py-4 px-6 flex justify-between items-center shadow">
        <h1 class="text-2xl font-bold">TripMate</h1>
        <div class="flex items-center space-x-4">
            <span class="material-icons">account_circle</span>
            <form action="{{ route('hotel.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="text-red-600 hover:underline">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-200 h-screen p-4 space-y-4 text-sm font-medium">
            <div class="bg-white rounded-md p-2 text-center font-semibold">Hotel Dashboard</div>
            <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">My Hotel Info</a>
            <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">Bookings</a>
            <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">Messages</a>
            <a href="#" class="block px-2 py-1 hover:bg-gray-100 rounded">Reviews</a>
        </div>

        <!-- Dashboard Content -->
        <div class="flex-1 p-10">
            <h2 class="text-xl font-bold text-center mb-6">Welcome to Hotel Dashboard</h2>
            <div class="grid grid-cols-2 gap-6 text-center text-gray-800">
                <div class="bg-white p-6 rounded border shadow">
                    <h3 class="text-lg font-semibold">Upcoming Bookings</h3>
                    <p class="text-2xl mt-2">12</p>
                </div>
                <div class="bg-white p-6 rounded border shadow">
                    <h3 class="text-lg font-semibold">Messages</h3>
                    <p class="text-2xl mt-2">3</p>
                </div>
                <div class="bg-white p-6 rounded border shadow">
                    <h3 class="text-lg font-semibold">Total Reviews</h3>
                    <p class="text-2xl mt-2">24</p>
                </div>
                <div class="bg-white p-6 rounded border shadow">
                    <h3 class="text-lg font-semibold">Rating</h3>
                    <p class="text-2xl mt-2">4.5 ★</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
