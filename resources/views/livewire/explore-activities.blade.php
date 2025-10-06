<div>
    <!-- Hero Section with Search -->
    <section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 text-white overflow-hidden mt-[72px]">
        <div class="absolute inset-0 bg-black/10"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-16 sm:py-20 text-center">
            <div class="space-y-8">
                <div class="space-y-4">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight">
                        Discover Amazing
                        <span class="block text-yellow-300">Adventures</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-white/90 max-w-3xl mx-auto leading-relaxed">
                        Explore extraordinary experiences across beautiful destinations.
                    </p>
                </div>

                <!-- Livewire Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white/95 rounded-xl p-3 shadow-lg">
                        <div class="flex items-center">
                            <div class="flex-1 relative">
                                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" 
                                       wire:model.live.debounce.300ms="search"
                                       placeholder="Search activities, destinations..."
                                       class="w-full pl-12 pr-12 py-3 bg-transparent text-gray-700 placeholder-gray-400 focus:outline-none border-0">
                                
                                <!-- Loading indicator -->
                                <div wire:loading wire:target="search" class="absolute right-12 top-1/2 transform -translate-y-1/2">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                                </div>
                                
                                <!-- Clear Search Button -->
                                @if($search)
                                    <button wire:click="clearSearch" type="button"
                                           class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search Stats -->
                    <div wire:loading.remove wire:target="search">
                        @if($activities->total() > 0)
                            <div class="mt-3 text-white/80 text-sm">
                                <i class="fas fa-info-circle mr-2"></i>
                                Found {{ $activities->total() }} activities
                                @if($search)
                                    for "{{ $search }}"
                                @endif
                            </div>
                        @elseif($search)
                            <div class="mt-3 text-white/80 text-sm">
                                <i class="fas fa-search mr-2"></i>
                                No results for "{{ $search }}"
                            </div>
                        @endif
                    </div>
                    
                    <!-- Loading text -->
                    <div wire:loading wire:target="search" class="mt-3 text-white/80 text-sm">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Searching...
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Activities Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div wire:loading.class="opacity-50" wire:target="search">
            @if($activities->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($activities as $activity)
                        @php
                            $raw  = $activity->image;
                            $path = $raw ? (strpos($raw, 'public/') === 0 ? substr($raw, 7) : $raw) : null;
                            $img  = $path
                                ? (preg_match('#^https?://#', $path) || strpos($path, '/') === 0
                                    ? $path
                                    : asset('storage/'.ltrim($path, '/')))
                                : asset('images/placeholder.jpg');
                        @endphp

                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <a href="{{ route('tourist.activity.show', $activity->id) }}" class="block">
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $img }}" 
                                        class="w-full h-full object-cover" 
                                        alt="{{ $activity->name }}">
                                    @if(!is_null($activity->price))
                                        <div class="absolute top-3 right-3 bg-white/90 rounded-full px-3 py-1 text-sm font-semibold text-blue-600">
                                            ${{ number_format($activity->price, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $activity->name }}</h3>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $activity->description }}</p>
                                    <div class="mt-3 flex items-center justify-end">
                                        <span class="text-blue-600 font-semibold text-sm">
                                            Explore Now
                                            <i class="fas fa-arrow-right ml-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($activities->hasPages())
                    <div class="mt-8">
                        {{ $activities->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-search text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Activities Found</h3>
                        <p class="text-gray-600 mb-6">
                            @if($search)
                                We couldn't find any activities matching "{{ $search }}". Try different keywords.
                            @else
                                No activities are currently available.
                            @endif
                        </p>
                        @if($search)
                            <button wire:click="clearSearch"
                                   class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Clear Search
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>
