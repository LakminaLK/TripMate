<!DOCTYPE html>
<html lang="en">
<x-tourist.head title="{{ $activity->name }} | TripMate" />
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col">

@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
@endphp

<!-- ✅ Professional Navbar -->
<x-tourist.header />

<!-- Activity Details Section -->
<div class="max-w-7xl mx-auto px-6 py-12 mt-[72px]">
    <div class="bg-white rounded-3xl shadow-lg ring-1 ring-black/5 overflow-hidden fade-in">
        <div class="grid lg:grid-cols-2 gap-0">
            <!-- Activity Image with Enhanced Hover Effect -->
            <div class="image-container h-[500px] slide-in-left">
                <img src="{{ $activity->image_url }}"
                     alt="{{ $activity->name }}"
                     class="w-full h-full object-cover">
                <div class="image-overlay"></div>

                @if(!is_null($activity->price))
                    <div class="absolute top-6 right-6 z-10">
                        <div class="bg-white/95 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg transform transition-transform duration-300 hover:scale-105">
                            <span class="text-blue-600 font-bold text-lg">
                                ${{ number_format($activity->price, 2) }}
                            </span>
                            <span class="text-gray-600 text-sm">/person</span>
                        </div>
                    </div>
                @endif

                <!-- Activity Quick Info -->
                <div class="absolute bottom-6 left-6 right-6 z-10 text-white space-y-4">
                    <div class="flex items-center space-x-4 text-sm">
                        <div class="flex items-center bg-black/30 backdrop-blur-sm rounded-full px-3 py-1">
                            <i class="fas fa-clock text-blue-400 mr-2"></i>
                            <span>4 hours</span>
                        </div>
                        <div class="flex items-center bg-black/30 backdrop-blur-sm rounded-full px-3 py-1">
                            <i class="fas fa-users text-blue-400 mr-2"></i>
                            <span>Small group</span>
                        </div>
                        <div class="flex items-center bg-black/30 backdrop-blur-sm rounded-full px-3 py-1">
                            <i class="fas fa-star text-yellow-400 mr-2"></i>
                            <span>4.8 (120)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="p-10 flex flex-col h-full slide-up">
                <h1 class="text-3xl font-bold mb-2">{{ $activity->name }}</h1>

                <div class="prose max-w-none mb-8">
                    <p class="text-gray-600 leading-relaxed">{{ $activity->description }}</p>
                </div>

                <div class="mt-auto">
                    <h2 class="text-xl font-semibold mb-4">Available Locations</h2>
                    @if($locations->count())
                        <div class="grid sm:grid-cols-2 gap-4">
                            @foreach($locations as $loc)
                                <a href="{{ route('tourist.location.show', $loc->id) }}"
                                   class="group block rounded-xl border border-gray-200 hover:border-blue-500 bg-white hover:bg-blue-50/50 transition-all duration-300 p-4 hover-lift fade-in"
                                   style="animation-delay: {{ $loop->index * 0.1 }}s;">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors">
                                                {{ $loc->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 mt-0.5">View available hotels</div>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-500 transform group-hover:translate-x-1 transition-all"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 px-6 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 scale-in">
                            <div class="text-gray-500 float">No locations are currently available for this activity.</div>
                            <button class="mt-3 text-blue-600 hover:text-blue-700 font-medium">Notify me when available</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<x-tourist.footer />

</body>
</html>
