<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile | TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>[x-cloak] { display: none !important; }</style>
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-white text-gray-800 font-sans">

@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
    $imageUrl = $tourist->profile_image ? asset('storage/' . $tourist->profile_image) : null;
@endphp

<header class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-9xl mx-auto px-6 py-4 flex justify-between items-center">
        <div><img src="{{ asset('images/logoo.png') }}" alt="Trip Mate Logo" class="h-16 w-16 object-contain"></div>
        <nav class="flex items-center gap-6 text-sm font-medium">
            <a href="{{ route('landing') }}" class="hover:text-blue-600 transition">Home</a>
            <a href="#" class="hover:text-blue-600 transition">About</a>
            <a href="#" class="hover:text-blue-600 transition">Explore</a>
            <a href="#" class="hover:text-blue-600 transition">Emergency Info</a>
            <a href="#" class="hover:text-blue-600 transition">Contact Us</a>

            <div x-data="{ open: false }" class="relative" @click.away="open = false">
                <button @click="open = !open"
                        class="flex items-center gap-2 bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700 transition">
                    <div class="bg-white text-blue-600 font-bold h-8 w-8 rounded-full flex items-center justify-center">
                        {{ strtoupper(substr($tourist->name, 0, 1)) }}
                    </div>
                    <span class="hidden md:inline">{{ $tourist->name }}</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition x-cloak class="absolute right-0 mt-2 w-44 bg-white border rounded shadow-md z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">View Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
</header>

<div class="py-12 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- ✅ Profile Info -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">Profile Information</h2>
                <p class="text-sm text-gray-500 mb-4">Update your name and profile picture.</p>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <!-- ✅ Profile Image -->
                    <div class="relative w-20 h-20 mb-4">
                        <label for="profile_image"
                            class="cursor-pointer group block w-20 h-20 rounded-full border overflow-hidden shadow-lg">
                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}?{{ now()->timestamp }}" alt="Profile Image"
                                     class="object-cover w-full h-full transition-transform duration-200 group-hover:scale-105" />
                            @else
                                <div class="bg-blue-600 text-white flex items-center justify-center w-full h-full text-2xl font-bold">
                                    {{ strtoupper(substr($tourist->name, 0, 1)) }}
                                </div>
                            @endif
                        </label>
                        <input type="file" name="profile_image" id="profile_image" accept="image/*"
                               class="hidden" x-ref="file" @click="$refs.file.value = ''" />
                    </div>

                    <!-- ✅ Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input id="name" name="name" type="text"
                               class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               value="{{ old('name', $tourist->name) }}" required />
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ✅ Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="text" class="mt-1 p-3 w-full bg-gray-100 rounded-lg text-gray-700" value="{{ $tourist->email }}" disabled>
                    </div>

                    <!-- ✅ Mobile -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mobile</label>
                        <input type="text" class="mt-1 p-3 w-full bg-gray-100 rounded-lg text-gray-700" value="{{ $tourist->mobile }}" disabled>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                            Save Changes
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-green-600 mt-2">Profile updated successfully.</p>
                        @endif
                    </div>
                </form>

                <!-- ✅ Remove Image Button -->
                @if ($tourist->profile_image)
                    <form method="POST" action="{{ route('profile.removeImage') }}"
                          onsubmit="return confirm('Are you sure you want to remove your profile image?')"
                          class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                            Remove Profile Image
                        </button>
                        @if (session('status') === 'profile-image-removed')
                            <p class="text-sm text-green-600 mt-2">Profile image removed successfully.</p>
                        @endif
                    </form>
                @endif
            </div>

            <!-- ✅ Password Update -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">Update Password</h2>
                <p class="text-sm text-gray-500 mb-4">Ensure your password is strong and unique.</p>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input id="current_password" name="current_password" type="password"
                               class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input id="password" name="password" type="password"
                               class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                               class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                            Update Password
                        </button>

                        @if (session('status') === 'password-updated')
                            <p class="text-sm text-green-600 mt-2">Password updated successfully.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Footer -->
<footer class="bg-gray-900 text-white text-sm py-8 mt-12">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <h4 class="font-bold mb-2">About Travel Mate</h4>
            <ul class="space-y-1 text-gray-400">
                <li>About Us</li>
                <li>Careers</li>
                <li>Trust & Safety</li>
                <li>Technology Blog</li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold mb-2">Explore</h4>
            <ul class="space-y-1 text-gray-400">
                <li>Help Center</li>
                <li>Write a Review</li>
                <li>Join as a Host</li>
                <li>Traveler’s Choice</li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold mb-2">Get the App</h4>
            <ul class="space-y-1 text-gray-400">
                <li>iOS / Android</li>
            </ul>
        </div>
    </div>
    <div class="text-center mt-6 border-t border-gray-700 pt-4 text-gray-500">
        © 2025 Trip Mate. All Rights Reserved.
    </div>
</footer>

</body>
</html>
