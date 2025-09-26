<!DOCTYPE html>
<html lang="en">
<head>
    <title>Explore | TripMate</title>
    <x-tourist.head />
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col">

@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
@endphp

<x-tourist.header />

<!-- Hero + filters -->
<section class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white relative overflow-hidden mt-[72px]">
    <!-- Decorative Elements -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-black/20 mix-blend-multiply"></div>
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-500/30 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-500/30 rounded-full filter blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-20 relative z-10">
        <div class="fade-in max-w-3xl">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                Discover Amazing
                <span class="bg-gradient-to-r from-blue-200 to-indigo-200 text-transparent bg-clip-text">Adventures</span>
            </h1>
            <p class="text-xl text-white/90 leading-relaxed">
                Explore extraordinary experiences across beautiful destinations. Your next unforgettable adventure awaits.
            </p>
        </div>

        <form method="GET" class="mt-12 relative max-w-3xl mx-auto">
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-3 shadow-xl slide-up">
                <div class="flex items-center">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-white/70"></i>
                        </div>
                        <input type="text" 
                               name="q" 
                               value="{{ $q ?? '' }}" 
                               placeholder="Search for amazing activities..." 
                               class="w-full bg-white/20 border-0 pl-11 pr-4 py-4 rounded-xl text-white placeholder-white/70 focus:ring-2 focus:ring-white/60 focus:bg-white/30 transition-all text-lg" />
                    </div>
                    <button class="ml-3 bg-white rounded-xl text-blue-600 font-semibold px-8 py-4 hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center">
                        <span>Search</span>
                        <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                    </button>
                </div>
            </div>
        </form>

  </div>
</section>

<!-- Grid -->
<main class="max-w-7xl mx-auto px-6 -mt-8 pb-16">
    @if($activities->count())
        <div x-data="{ 
            init() {
                let observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('opacity-100', 'translate-y-0');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });

                document.querySelectorAll('.activity-card').forEach((card, index) => {
                    observer.observe(card);
                    card.style.transitionDelay = `${index * 100}ms`;
                    card.classList.add('opacity-0', 'translate-y-8', 'transition-all', 'duration-700');
                });
            }
        }" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($activities as $a)
                @php
                    $raw  = $a->image;
                    $path = $raw ? (strpos($raw, 'public/') === 0 ? substr($raw, 7) : $raw) : null;
                    $img  = $path
                        ? (preg_match('#^https?://#', $path) || strpos($path, '/') === 0
                            ? $path
                            : asset('storage/'.ltrim($path, '/')))
                        : asset('images/placeholder.jpg');
                @endphp

                <article class="activity-card bg-white rounded-2xl shadow-lg hover:shadow-xl ring-1 ring-black/5 overflow-hidden group hover-lift fade-in" 
                         style="animation-delay: {{ $loop->index * 0.1 }}s;">
                    <a href="{{ route('tourist.activity.show', $a->id) }}" class="block">
                        <div class="relative h-56 overflow-hidden">
                            <img src="{{ $img }}" 
                                class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700" 
                                alt="{{ $a->name }}">
                            @if(!is_null($a->price))
                                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur shadow-lg rounded-full px-4 py-2 text-sm font-semibold transform group-hover:scale-105 transition-all duration-300">
                                    <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">${{ number_format($a->price, 2) }}</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <h3 class="font-bold text-xl group-hover:text-blue-600 transition-colors line-clamp-1">{{ $a->name }}</h3>
                            </div>
                            <p class="text-sm text-gray-600 mt-3 line-clamp-2">{{ $a->description }}</p>
                            <div class="mt-4 flex items-center justify-end">
                                <button class="inline-flex items-center space-x-2 text-blue-600 font-semibold group/btn">
                                    <span>Explore Now</span>
                                    <i class="fas fa-arrow-right transform group-hover/btn:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $activities->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center shadow-xl ring-1 ring-black/5 scale-in max-w-2xl mx-auto">
            <div class="relative w-24 h-24 mx-auto mb-8">
                <div class="absolute inset-0 bg-blue-100 rounded-full animate-ping opacity-20"></div>
                <div class="relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-full p-6 float">
                    <svg class="w-full h-full text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Activities Found</h3>
            <p class="text-gray-600 mb-8">We couldn't find any activities matching your current filters. Try adjusting your search criteria.</p>
            <a href="{{ route('tourist.explore') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                <i class="fas fa-sync-alt mr-2"></i>
                Clear All Filters
            </a>
        </div>
    @endif
</main>

<!-- ✅ Professional Footer -->
<x-tourist.footer />

</body>
</html>
