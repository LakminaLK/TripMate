<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>[x-cloak] { display: none !important; }</style>
 <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-white text-gray-800 font-sans">

@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
@endphp

<!-- ✅ Navbar -->
<header class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-9xl mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo + Title -->
        <div>
            <img src="{{ asset('images/logoo.png') }}" alt="Trip Mate Logo" class="h-16 w-16 object-contain">
        </div>

        <nav class="flex items-center gap-6 text-sm font-medium">
            <a href="{{ route('landing') }}" class="hover:text-blue-600 transition">Home</a>
            <a href="#" class="hover:text-blue-600 transition">About</a>
            <a href="#" class="hover:text-blue-600 transition">Explore</a>
            <a href="#" class="hover:text-blue-600 transition">Emergancy Info</a>
            <a href="#" class="hover:text-blue-600 transition">Contact Us</a>

            @if ($tourist)
                <!-- ✅ Profile Dropdown FIXED -->
<div x-data="{ open: false }" class="relative" @click.away="open = false">
    <button @click="open = !open"
        class="flex items-center gap-2 bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700 transition">
        <div class="bg-white text-blue-600 font-bold h-8 w-8 rounded-full flex items-center justify-center">
            {{ strtoupper(substr($tourist->name, 0, 1)) }}
        </div>
        <span class="hidden md:inline">{{ $tourist->name }}</span>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-transition x-cloak
         class="absolute right-0 mt-2 w-44 bg-white border rounded shadow-md z-50">
        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">View Profile</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Logout</button>
        </form>
    </div>
</div>

            @else
                <a href="{{ route('login') }}" class="px-4 py-1 border rounded hover:bg-blue-50">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Sign Up</a>
            @endif
        </nav>
    </div>
</header>

<!-- ✅ Hero Section -->
<section class="relative h-[70vh] bg-cover bg-center flex items-center justify-center text-white"
         style="background-image: url('/images/2.jpeg');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative z-10 text-center px-4" x-data x-init="$el.classList.add('animate-fade-in')">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-slide-up">
            Weaving your Dreams into Unforgettable Adventure
        </h1>
        <p class="max-w-2xl mx-auto mb-6 animate-fade-in">
            From beachside resorts to the most unique stays. See the top 1% of hotels from Traveler’s Choice.
        </p>
        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition animate-pop">
            See more...
        </a>
    </div>
</section>

<!-- ✅ Welcome Section -->
<section class="py-12 px-6 max-w-7xl mx-auto text-center">
    <h2 class="text-3xl font-bold mb-4">Welcome to Trip Mate</h2>
    <p class="text-gray-600 max-w-3xl mx-auto mb-8">
        Like you, we are travelers. Exploration runs in our blood. It’s who we are, and why we do what we do. We are passionate, curious and deeply committed to sustainably exploring our incredible world. Like you, we are part of a global community, excited to embrace and discover our planet, our home and uncover the rich cultures, histories, wildlife and natural beauty that make our travels so special. At Trip Mate, we create transformative travel experiences that fulfill that deep-seated urge for connecting and learning. So, ask yourself this – where will your passion for travel take you?
    </p>
    <a href="#" class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Explore Now</a>
</section>

<!-- ✅ Enhanced Category Grid with Hover Effects -->
<!-- ✅ Categories Section with Hover Effect -->
<section class="max-w-7xl mx-auto px-6 pb-16">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ([
            'Beach Side' => 'beachside.jpg',
            'Adventure' => 'adventure.jpg',
            'Hiking' => 'hiking.jpg',
            'Rafting' => 'rafting.jpg',
            'Sky Diving' => 'skydiving.jpg',
            'Camping' => 'camping.jpg'
        ] as $label => $img)
            <div class="relative group rounded-xl overflow-hidden shadow-lg hover:-translate-y-1 transition duration-300">
                <!-- Background Image -->
                <img src="/images/explore/{{ $img }}" class="w-full h-56 object-cover">

                <!-- Overlay -->
                <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-60 transition"></div>

                <!-- Category Title (Default View) -->
                <p class="absolute inset-0 flex items-center justify-center text-white text-lg font-bold transition-opacity duration-300 group-hover:opacity-0">
                    {{ $label }}
                </p>

                <!-- Learn More Button (On Hover) -->
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <a href="#" class="bg-white text-blue-600 px-4 py-1 rounded-full font-medium hover:bg-blue-100 transition">
                        Learn More
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>




<!-- ✅ FAQs -->
<section class="bg-gray-50 py-12 px-6">
    <h3 class="text-2xl font-bold text-center mb-8">Frequently Asked Questions [FAQs]</h3>
    <div class="max-w-3xl mx-auto space-y-4">
        @foreach ([
            'How do I create a booking?',
            'What is the cancellation Policy?',
            'How can I find out more about Sri Lankan destinations?',
            'How do I contact you for support?'
        ] as $faq)
            <div x-data="{ open: false }" class="bg-white shadow rounded-md p-4">
                <button @click="open = !open" class="w-full text-left font-semibold">
                    {{ $faq }}
                </button>
                <p x-show="open" x-transition class="mt-2 text-sm text-gray-600">
                    Answer coming soon. You can update this later!
                </p>
            </div>
        @endforeach
    </div>
</section>

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

<!-- ✅ Custom Tailwind Animations -->
<style>
    .animate-fade-in {
        animation: fadeIn 1s ease-out forwards;
        opacity: 0;
    }

    .animate-slide-up {
        animation: slideUp 1s ease-out forwards;
        opacity: 0;
    }

    .animate-pop {
        animation: pop 0.3s ease-out forwards;
    }

    @keyframes fadeIn {
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pop {
        0% { transform: scale(0.9); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>

</body>
</html>
