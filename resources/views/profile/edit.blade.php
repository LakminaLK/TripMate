<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .custom-shadow { box-shadow: 0 0 50px -12px rgb(0 0 0 / 0.25); }
        
        /* Professional animations */
        .fade-in { animation: fadeIn 0.8s ease-out forwards; opacity: 0; }
        .slide-up { animation: slideUp 0.8s ease-out forwards; opacity: 0; transform: translateY(30px); }
        .slide-in-left { animation: slideInLeft 0.8s ease-out forwards; opacity: 0; transform: translateX(-50px); }
        .scale-in { animation: scaleIn 0.6s ease-out forwards; opacity: 0; transform: scale(0.9); }
        .float { animation: float 3s ease-in-out infinite; }
        
        @keyframes fadeIn { to { opacity: 1; } }
        @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }
        @keyframes slideInLeft { to { opacity: 1; transform: translateX(0); } }
        @keyframes scaleIn { to { opacity: 1; transform: scale(1); } }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
    </style>
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-white text-gray-800 font-sans">

@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
    $imageUrl = $tourist->profile_image ? asset('storage/' . $tourist->profile_image) : null;
@endphp

<!-- ✅ Professional Navbar -->
<!-- ✅ Professional Navbar -->
<header x-data="{ isOpen: false }" 
        class="fixed top-0 w-full z-50 bg-white/95 backdrop-blur-md shadow-lg transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <!-- Logo & Brand -->
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 group">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl blur opacity-75 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative bg-white p-2 rounded-xl">
                        <img src="{{ asset('images/logoo.png') }}" alt="TripMate" class="h-8 w-8">
                    </div>
                </div>
                <div>
                    <h1 :class="scrolled ? 'text-gray-900' : 'text-white'" 
                        class="text-xl font-bold transition-colors">
                        Trip<span class="text-blue-600">Mate</span>
                    </h1>
                    <p :class="scrolled ? 'text-gray-500' : 'text-white/70'" 
                       class="text-xs transition-colors">Your Travel Companion</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('landing') }}" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    Home
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#about" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    About
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="{{ route('tourist.explore') }}" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    Explore
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#emergency" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    Emergency
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#contact" 
                   :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                   class="font-medium transition-colors relative group">
                    Contact us
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"></span>
                </a>
            </nav>

            <!-- Auth Section -->
            <div class="flex items-center space-x-4">
                @if (isset($tourist))
                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative" @click.away="open = false">
                        <button @click="open = !open"
                                :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                                class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-white/10 transition-all duration-300">
                            <i class="fas fa-user-circle text-2xl"></i>
                        </button>

                        <div x-show="open" x-transition
                             class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                            <div class="p-4 bg-gradient-to-r from-blue-50 to-purple-50">
                                <div class="flex items-center space-x-3">
                                    <div class="h-12 w-12 rounded-full overflow-hidden flex items-center justify-center bg-white shadow-inner">
                                        @if($tourist->profile_photo_path)
                                            <img src="{{ asset('storage/' . $tourist->profile_photo_path) }}" 
                                                 alt="{{ $tourist->name }}" 
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold h-full w-full flex items-center justify-center text-xl">
                                                {{ strtoupper(substr($tourist->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $tourist->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $tourist->email ?? 'Travel Enthusiast' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="py-2">
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-user-circle mr-3 text-blue-600"></i>
                                    View Profile
                                </a>
                                <a href="#bookings" 
                                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-calendar-alt mr-3 text-blue-600"></i>
                                    My Bookings
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center px-4 py-3 text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       :class="scrolled ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-300'"
                       class="font-medium transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="bg-white/10 backdrop-blur text-white px-6 py-2 rounded-full font-medium hover:bg-white/20 transition-all duration-300">
                        Sign Up
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>

<div class="py-12 bg-gray-100 min-h-screen mt-[72px]">
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
