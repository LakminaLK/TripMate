<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile | TripMate</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-300 min-h-screen">

<!-- Top Navbar -->
<div class="bg-white py-4 px-6 flex justify-between items-center shadow">
    <h1 class="text-2xl font-bold">TripMate</h1>
    <!-- Profile Dropdown -->
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open"
                class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 focus:outline-none">
            <img src="{{ asset('images/Profile.png') }}" alt="Profile"
                 class="w-8 h-8 rounded-full object-cover">
        </button>
        <div x-show="open" @click.away="open = false" x-transition
             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
            <a href="{{ route('admin.profile.edit') }}"
               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left px-4 py-2 text-red-700 hover:bg-gray-100">Logout</button>
            </form>
        </div>
    </div>
</div>

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

    <!-- Main Content -->
    <main class="flex-1 p-10">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h2 class="text-3xl font-bold tracking-tight">Admin Profile</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your account details and password.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- LEFT -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Account Details -->
                    <section class="bg-white rounded border p-6 shadow">
                        <h3 class="text-lg font-semibold mb-4">Account Details</h3>
                        <dl class="text-sm divide-y divide-gray-100">
                            <div class="flex items-center justify-between py-3">
                                <dt class="text-gray-600">Current Username</dt>
                                <dd class="font-medium">{{ $admin->username }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <dt class="text-gray-600">Admin ID</dt>
                                <dd class="font-medium">A{{ str_pad($admin->id, 3, '0', STR_PAD_LEFT) }}</dd>
                            </div>
                        </dl>
                    </section>

                    <!-- Change Username -->
                    <section class="bg-white rounded border p-6 shadow">
                        <h3 class="text-lg font-semibold mb-4">Change Username</h3>
                        @if ($errors->has('username'))
                            <div class="mb-3 text-sm bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded">
                                {{ $errors->first('username') }}
                            </div>
                        @endif
                        <form action="{{ route('admin.profile.username.update') }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-sm text-gray-700 mb-1" for="username">New Username</label>
                                <input type="text" id="username" name="username"
                                       value="{{ old('username', $admin->username) }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2"
                                       autocomplete="off" required>
                                <p class="text-xs text-gray-500 mt-1">Must be unique among admins.</p>
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded hover:bg-black transition">
                                Update Username
                            </button>
                        </form>
                    </section>
                </div>

                <!-- RIGHT -->
                <section class="bg-white rounded border p-6 shadow lg:col-span-1">
                    <h3 class="text-lg font-semibold mb-4">Change Password</h3>
                    <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm text-gray-700 mb-1" for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password"
                                   class="w-full border border-gray-300 rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1" for="password">New Password</label>
                            <input type="password" id="password" name="password"
                                   class="w-full border border-gray-300 rounded px-3 py-2" required>
                            <p class="text-xs text-gray-500 mt-1">At least 8 characters.</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1" for="password_confirmation">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full border border-gray-300 rounded px-3 py-2" required>
                        </div>
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded hover:bg-black transition">
                            Update Password
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </main>
</div>

<!-- Alpine -->
<script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
