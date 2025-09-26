@extends('hotel.layouts.app')

@section('title', 'Hotel Management')

@push('                        <div class="border-b bord                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-white uppercase tracking-wide mb-2">Rating</h3>
                            <div class="flex items-center space-x-2">gray-100 dark:border-dark-200 pb-3">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-white uppercase tracking-wide mb-2">Location</h3>
                            <div class="space-y-2">sh-scripts')
<!-- Expose flash for toasts -->
<script>
  window.FLASH = {
    success: @json(session('success')),
    errors:  @json($errors->all()),
  };
</script>
@endpush

@section('content')
    <!-- Compact Header like Admin -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-dark-100 p-6 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 mb-6 transition-colors duration-300">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-dark-700">Hotel Management</h1>
            <p class="text-gray-600 dark:text-dark-500">Manage your hotel details, images, facilities, and status</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Status Badge -->
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-gray-500 dark:text-dark-400">Status:</span>
                <span class="px-3 py-1 rounded-full text-sm font-semibold 
                    {{ $hotel->status === 'Active' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300' }}">
                    {{ $hotel->status }}
                </span>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-2">
                <a href="{{ route('hotel.management.edit') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    Edit Details
                </a>
                
                <form action="{{ route('hotel.management.toggle-status') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="{{ $hotel->status === 'Active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg transition-colors text-sm flex items-center gap-2">
                        <i class="fas fa-power-off"></i>
                        {{ $hotel->status === 'Active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column - Hotel Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 overflow-hidden transition-colors duration-300">
                <!-- Hotel Image -->
                <div class="relative">
                    @if($hotel->main_image)
                        <img src="{{ asset('storage/' . $hotel->main_image) }}" 
                             alt="{{ $hotel->name }}" 
                             class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-dark-200 dark:to-dark-300 flex items-center justify-center transition-colors duration-300">
                            <div class="text-center text-gray-500 dark:text-dark-400">
                                <i class="fas fa-hotel text-4xl mb-2"></i>
                                <h2 class="text-xl font-bold text-gray-700 dark:text-dark-600">{{ $hotel->name }}</h2>
                                <p class="text-sm">No main image</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Hotel Details -->
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="border-b border-gray-100 dark:border-dark-200 pb-3">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-white uppercase tracking-wide mb-2">Contact Information</h3>
                            <div class="space-y-2">
                                <div class="flex items-center text-gray-700 dark:text-gray-200">
                                    <i class="fas fa-envelope w-4 text-gray-400 dark:text-gray-300 mr-3"></i>
                                    <span class="text-sm">{{ $hotel->email }}</span>
                                </div>
                                @if($hotel->phone)
                                <div class="flex items-center text-gray-700 dark:text-gray-200">
                                    <i class="fas fa-phone w-4 text-gray-400 dark:text-gray-300 mr-3"></i>
                                    <span class="text-sm">{{ $hotel->phone }}</span>
                                </div>
                                @endif
                                @if($hotel->website)
                                <div class="flex items-center text-gray-700 dark:text-gray-200">
                                    <i class="fas fa-globe w-4 text-gray-400 dark:text-gray-300 mr-3"></i>
                                    <a href="{{ $hotel->website }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                        Website
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="border-b border-gray-100 dark:border-dark-200 pb-3">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-white uppercase tracking-wide mb-2">Location</h3>
                            <div class="flex items-start text-gray-700 dark:text-gray-200">
                                <i class="fas fa-map-marker-alt w-4 text-gray-400 dark:text-gray-300 mr-3 mt-0.5"></i>
                                <div>
                                    <p class="text-sm font-medium">{{ $hotel->location->name ?? 'Not specified' }}</p>
                                    @if($hotel->address)
                                        <p class="text-xs text-gray-500 dark:text-gray-300 mt-1">{{ $hotel->address }}</p>
                                    @endif
                                    @if($hotel->map_url)
                                        <a href="{{ $hotel->map_url }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                            View on Google Maps
                                        </a>
                                    @elseif($hotel->latitude && $hotel->longitude)
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $hotel->latitude }},{{ $hotel->longitude }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                            View on Google Maps
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if($hotel->star_rating)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-white uppercase tracking-wide mb-2">Rating</h3>
                            <div class="flex items-center">
                                <div class="flex mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $hotel->star_rating ? 'text-yellow-400' : 'text-gray-300 dark:text-dark-300' }} text-sm"></i>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600 dark:text-gray-200">{{ $hotel->star_rating }}/5 Stars</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Details Sections -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Hotel Description -->
            <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 transition-colors duration-300">
                <div class="border-b border-gray-200 dark:border-dark-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-dark-700 flex items-center">
                            <i class="fas fa-file-alt text-blue-600 dark:text-blue-400 mr-2"></i>
                            Hotel Description
                        </h3>
                        @if(!$hotel->description)
                            <a href="{{ route('hotel.management.edit') }}" 
                               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm flex items-center">
                                <i class="fas fa-plus mr-1"></i>
                                Add Description
                            </a>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    @if($hotel->description)
                        <p class="text-gray-700 dark:text-dark-500 leading-relaxed">{{ $hotel->description }}</p>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-dark-400">
                            <i class="fas fa-file-alt text-3xl mb-3 text-gray-300 dark:text-dark-300"></i>
                            <p class="text-gray-600 dark:text-dark-500">No description added yet</p>
                            <p class="text-sm text-gray-500 dark:text-dark-400 mt-1">Add a compelling description to attract more guests</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Facilities & Amenities -->
            <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 transition-colors duration-300">
                <div class="border-b border-gray-200 dark:border-dark-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-dark-700 flex items-center">
                            <i class="fas fa-concierge-bell text-green-600 dark:text-green-400 mr-2"></i>
                            Facilities & Amenities
                            <span class="ml-2 px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 text-xs rounded-full">
                                {{ $hotel->facilities->count() }}
                            </span>
                        </h3>
                        @if($hotel->facilities->count() === 0)
                            <a href="{{ route('hotel.management.edit') }}" 
                               class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm flex items-center">
                                <i class="fas fa-plus mr-1"></i>
                                Add Facilities
                            </a>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    @if($hotel->facilities->count() > 0)
                        @php
                            $facilitiesByCategory = $hotel->facilities->groupBy('category');
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($facilitiesByCategory as $category => $facilities)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-white mb-3 capitalize border-b border-gray-100 dark:border-dark-200 pb-2">
                                        {{ str_replace('_', ' ', $category) }}
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($facilities as $facility)
                                            <div class="flex items-center text-gray-700 dark:text-dark-500">
                                                <i class="{{ $facility->icon ?? 'fas fa-check' }} text-green-600 dark:text-green-400 w-4 mr-3"></i>
                                                <span class="text-sm">{{ $facility->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-dark-400">
                            <i class="fas fa-concierge-bell text-3xl mb-3 text-gray-300 dark:text-dark-300"></i>
                            <p class="text-gray-600 dark:text-dark-500">No facilities selected yet</p>
                            <p class="text-sm text-gray-500 dark:text-dark-400 mt-1">Add facilities to showcase your hotel amenities</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Image Gallery -->
            <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 transition-colors duration-300">
                <div class="border-b border-gray-200 dark:border-dark-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-dark-700 flex items-center">
                            <i class="fas fa-images text-purple-600 dark:text-purple-400 mr-2"></i>
                            Image Gallery
                            <span class="ml-2 px-2 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300 text-xs rounded-full">
                                {{ $hotel->images->count() }}
                            </span>
                        </h3>
                        @if($hotel->images->count() === 0)
                            <a href="{{ route('hotel.management.edit') }}" 
                               class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 text-sm flex items-center">
                                <i class="fas fa-plus mr-1"></i>
                                Add Images
                            </a>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    @if($hotel->images->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($hotel->images as $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         alt="{{ $image->alt_text }}"
                                         class="w-full h-24 object-cover rounded-lg border border-gray-200 dark:border-dark-200">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-dark-400">
                            <i class="fas fa-images text-3xl mb-3 text-gray-300 dark:text-dark-300"></i>
                            <p class="text-gray-600 dark:text-dark-500">No additional images uploaded</p>
                            <p class="text-sm text-gray-500 dark:text-dark-400 mt-1">Upload images to showcase your hotel</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
