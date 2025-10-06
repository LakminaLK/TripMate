<!DOCTYPE html>
<html lang="en">
<head>
    <title>TripMate - Your Ultimate Travel Companion</title>
    <x-tourist.head />
</head>
<body class="bg-white text-gray-800 font-sans">

<x-tourist.header :transparent="true" />

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
        <a href="{{ route('tourist.explore') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition animate-pop">
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
    <!-- <a href="{{ route('tourist.explore') }}" class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Explore Now</a> -->
</section>

<!-- ✅ Popular Activities with Animation -->
<section class="max-w-7xl mx-auto px-6 pb-16" x-data="{ 
    shownActivities: [],
    init() {
        this.observeActivities();
    },
    observeActivities() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.shownActivities.push(entry.target.dataset.id);
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.activity-card').forEach(card => {
            observer.observe(card);
        });
    }
}">
    <!-- <div class="flex flex-col items-center text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            Discover Amazing Experiences
        </h2>
        <p class="text-gray-600 max-w-2xl mb-8">
            Explore our handpicked selection of unforgettable activities and create memories that last a lifetime.
        </p>
    </div> -->

    @php $list = ($homeActivities ?? collect())->take(6); @endphp
    @if($list->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach($list as $index => $a)
                @php
                    $raw = $a->image;
                    $path = $raw ? (strpos($raw, 'public/') === 0 ? substr($raw, 7) : $raw) : null;
                    $img = $path
                        ? (preg_match('#^https?://#', $path) || strpos($path, '/') === 0
                            ? $path
                            : asset('storage/'.ltrim($path, '/')))
                        : asset('images/placeholder.jpg');
                @endphp
                
                <a href="{{ route('tourist.activity.show', ['activity' => $a->id]) }}" 
                   class="activity-card group relative block rounded-2xl overflow-hidden shadow-lg transform transition-all duration-500 hover:shadow-2xl hover:-translate-y-2"
                   data-id="{{ $a->id }}"
                   :class="{ 'opacity-0 translate-y-8': !shownActivities.includes('{{ $a->id }}'), 'opacity-100 translate-y-0': shownActivities.includes('{{ $a->id }}') }"
                   style="transition-delay: {{ $index * 100 }}ms">
                    <!-- Image Container -->
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $img }}" 
                             alt="{{ $a->name }}"
                             class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
                        
                        <!-- Overlay with gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent opacity-70 transition-opacity group-hover:opacity-90"></div>

                        <!-- Price Badge -->
                        @if(!is_null($a->price))
                            <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full shadow-lg">
                                <span class="text-blue-600 font-semibold">${{ number_format($a->price, 2) }}</span>
                            </div>
                        @endif

                        <!-- Content overlay -->
                        <div class="absolute inset-0 p-6 flex flex-col justify-end text-white">
                            <h3 class="text-xl font-bold mb-2 transform transition-transform group-hover:-translate-y-2">
                                {{ $a->name }}
                            </h3>
                            <!-- Animated arrow -->
                            <div class="mt-4 inline-flex items-center text-blue-400 transform translate-y-8 opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">
                                <span class="font-medium mr-2">Explore Locations</span>
                                <i class="fas fa-arrow-right transform transition-transform group-hover:translate-x-2"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="{{ route('tourist.explore') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full 
                      font-medium hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <span>Explore All Activities</span>
                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center shadow-lg">
            <i class="fas fa-hiking text-6xl text-blue-200 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Activities Available</h3>
            <p class="text-gray-600">We're working on adding exciting new activities. Check back soon!</p>
        </div>
    @endif
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

<x-tourist.footer />

<!-- ✅ Custom Tailwind Animations -->
<style>
    .animate-fade-in { animation: fadeIn 1s ease-out forwards; opacity: 0; }
    .animate-slide-up { animation: slideUp 1s ease-out forwards; opacity: 0; }
    .animate-pop { animation: pop 0.3s ease-out forwards; }

    @keyframes fadeIn { to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes pop { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
</style>

</body>
</html>
