<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activities | TripMate</title>
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

        <!-- Activities Content -->
        <div class="flex-1 p-10">
            <h2 class="text-2xl font-bold mb-6">Activities Management</h2>

            <!-- Button to add new activity -->
            <button id="add-activity-btn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-4 inline-block">
                Add New Activity
            </button>

            <!-- Activities Table -->
            <table class="min-w-full bg-white border border-gray-300 rounded overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">Activity ID</th>
                        <th class="p-4 text-left">Activity Name</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                        <tr class="border-t">
                            <td class="p-4">{{ 'A' . str_pad($activity->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-4">{{ $activity->name }}</td>
                            <td class="p-4">{{ $activity->status }}</td>
                            <td class="p-4 flex space-x-2">
                                <!-- Edit Activity Button -->
                                <button class="text-blue-500 hover:underline" id="edit-activity-btn-{{ $activity->id }}" data-activity="{{ json_encode($activity) }}">Edit</button>

                                <!-- Delete Activity Form -->
                                <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" style="display:inline;">
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

    <!-- Modal for Creating/Editing Activity -->
    <div id="activity-modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-md shadow-lg w-1/3">
            <h3 class="text-xl font-bold mb-4" id="modal-title">Add New Activity</h3>

            <!-- Activity Form (Create/Update) -->
            <form id="activity-form" action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Method Spoofing for Update (only when editing) -->
                <div id="method-field" class="hidden">
                    @method('PUT') <!-- This will make it a PUT request for editing -->
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Activity Name</label>
                    <input type="text" id="name" name="name" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" class="mt-1 p-2 w-full border border-gray-300 rounded-md"></textarea>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Activity Image</label>
                    <input type="file" id="image" name="image" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" id="submit-button">
                    Create Activity
                </button>
            </form>

            <button id="close-modal-btn" class="text-gray-500 hover:underline mt-4">Cancel</button>
        </div>
    </div>

    <script>
        // Open the modal for creating new activity
        document.getElementById('add-activity-btn').addEventListener('click', function () {
    document.getElementById('activity-modal').classList.remove('hidden');
    document.getElementById('modal-title').textContent = "Add New Activity";
    document.getElementById('activity-form').action = "{{ route('admin.activities.store') }}";
    document.getElementById('activity-form').reset();
    document.getElementById('submit-button').textContent = "Create Activity";
    document.getElementById('method-field').innerHTML = ""; // 👈 clear method override
});


        // Open the modal for editing an existing activity
        document.querySelectorAll('[id^="edit-activity-btn-"]').forEach(button => {
            button.addEventListener('click', function () {
    const activity = JSON.parse(this.dataset.activity);
    document.getElementById('activity-modal').classList.remove('hidden');
    document.getElementById('modal-title').textContent = "Edit Activity";
    document.getElementById('name').value = activity.name;
    document.getElementById('description').value = activity.description;
    document.getElementById('status').value = activity.status;
    document.getElementById('activity-form').action = `/admin/activities/${activity.id}`;
    document.getElementById('submit-button').textContent = "Edit Activity";

    // 👇 dynamically insert method override for PUT
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
});

        });

        // Close the modal
        document.getElementById('close-modal-btn').addEventListener('click', function () {
            document.getElementById('activity-modal').classList.add('hidden');
        });
    </script>

</body>
</html>
