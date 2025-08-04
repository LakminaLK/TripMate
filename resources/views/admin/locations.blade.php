<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Locations | TripMate</title>
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
        <!-- Sidebar (same as before) -->

        <!-- Locations Content -->
        <div class="flex-1 p-10">
            <h2 class="text-2xl font-bold mb-6">Locations Management</h2>

            <!-- Button to add new location -->
            <a href="{{ route('admin.locations.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-4 inline-block">
                Add New Location
            </a>

            <!-- Locations Table -->
            <table class="min-w-full bg-white border border-gray-300 rounded overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">Location Name</th>
                        <th class="p-4 text-left">Description</th>
                        <th class="p-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $location)
                        <tr class="border-t">
                            <td class="p-4">{{ $location->name }}</td>
                            <td class="p-4">{{ $location->description ?? 'No description' }}</td>
                            <td class="p-4 flex space-x-2">
                                <!-- Edit Location Button -->
                                <a href="{{ route('admin.locations.edit', $location->id) }}" class="text-blue-500 hover:underline">Edit</a>

                                <!-- Delete Location Form -->
                                <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
