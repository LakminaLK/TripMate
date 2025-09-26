{{-- resources/views/tourist/location.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<x-tourist.head title="{{ $location->name }} | TripMate" />

  @if($location->latitude && $location->longitude)
  <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const map = new google.maps.Map(document.getElementById('location-preview-map'), {
        center: { 
          lat: {{ $location->latitude }}, 
          lng: {{ $location->longitude }} 
        },
        zoom: 15,
        mapTypeId: 'roadmap',
        mapTypeControl: false,
        fullscreenControl: false,
        streetViewControl: false,
        zoomControl: true,
        styles: [
          {
            featureType: 'poi',
            elementType: 'labels',
            stylers: [{ visibility: 'off' }]
          }
        ]
      });

      new google.maps.Marker({
        position: { 
          lat: {{ $location->latitude }}, 
          lng: {{ $location->longitude }} 
        },
        map: map,
        title: '{{ $location->name }}',
        animation: google.maps.Animation.DROP
      });
    });
  </script>
  @endif
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();

    // Fallbacks in case model accessors aren't added yet
    $main = $location->image_url ?? null;
    if (!$main) {
        $raw = $location->main_image ?? null;
        if ($raw) {
            $path = str_starts_with($raw, 'public/') ? substr($raw, 7) : $raw;
            $main = preg_match('#^https?://#', $path) || str_starts_with($path, '/') ? $path : asset('storage/'.ltrim($path,'/'));
        } else {
            $main = asset('images/placeholder.jpg');
        }
    }

    $gallery = $location->gallery_images ?? [];
    if (!$gallery) {
        $ai = $location->additional_images ?? null;
        if ($ai) {
            $arr = is_array($ai) ? $ai : (json_decode($ai,true) ?: preg_split('/[\r\n,]+/', (string)$ai, -1, PREG_SPLIT_NO_EMPTY));
            $gallery = collect($arr)->map(function($p){
                if (!$p) return null;
                if (str_starts_with($p, 'public/')) $p = substr($p, 7);
                return (preg_match('#^https?://#', $p) || str_starts_with($p, '/')) ? $p : asset('storage/'.ltrim($p,'/'));
            })->filter()->values()->all();
        }
    }

    $things = $location->things_list ?? [];
    if (!$things && !empty($location->things)) {
        $decoded = json_decode($location->things,true);
        $things = is_array($decoded) ? $decoded : preg_split('/\r\n|\r|\n/', (string)$location->things);
        $things = array_values(array_filter(array_map('trim',$things)));
    }
@endphp



<!-- ✅ Professional Navbar -->
<x-tourist.header />

<main class="max-w-7xl mx-auto px-6 pt-24 py-10 space-y-10">

  {{-- Location Hero Section with Improved UI --}}
  <section x-data="{ 
    activeImage: '{{ $main }}', 
    showGallery: false, 
    showImageModal: false,
    modalImage: '',
    gallery: [
      '{{ $main }}',
      @if(!empty($gallery))
        @foreach($gallery as $image)
          '{{ $image }}',
        @endforeach
      @endif
    ]
  }" class="space-y-8 fade-in">
    <div class="bg-white rounded-3xl shadow-lg overflow-hidden ring-1 ring-black/5">
      <!-- Main Image Display -->
      <div class="relative h-[75vh]">
        <img :src="activeImage" alt="{{ $location->name }}" class="w-full h-full object-cover">
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>

        <!-- Interactive Map Preview -->
        @if($location->latitude && $location->longitude)
        <div class="absolute top-6 right-6 w-80 h-52 rounded-xl overflow-hidden shadow-xl border-2 border-white/20">
          <div id="location-preview-map" class="w-full h-full"></div>
        </div>
        @endif

        <!-- Image Navigation -->
        <div class="absolute inset-x-6 bottom-6 flex justify-start items-center">
          <button @click="showGallery = !showGallery" 
                  class="group bg-black/70 backdrop-blur-md text-white px-5 py-2.5 rounded-full 
                         hover:bg-black/80 transition-all duration-300 flex items-center space-x-3 shadow-lg">
            <i class="fas fa-images text-blue-400 group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">View Gallery</span>
          </button>
        </div>
      </div>

      <!-- Enhanced Thumbnail Gallery -->
      <div x-show="showGallery" x-transition:enter="transition ease-out duration-200" 
           x-transition:enter-start="opacity-0 -translate-y-2" 
           x-transition:enter-end="opacity-100 translate-y-0"
           class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3 p-6 bg-gray-50 border-t">
        <!-- Main Image Thumbnail -->
        <button @click="activeImage = '{{ $main }}'" 
                @dblclick="showImageModal = true; modalImage = '{{ $main }}'"
                class="relative rounded-xl overflow-hidden hover:ring-2 ring-blue-500 transition-all duration-300
                       shadow hover:shadow-lg transform hover:-translate-y-0.5 hover:scale-105"
                :class="{ 'ring-2 ring-offset-2': activeImage === '{{ $main }}' }">
          <img src="{{ $main }}" class="w-full h-24 object-cover" alt="Main">
          <div class="absolute inset-0 gallery-overlay opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
            <i class="fas fa-search-plus text-white text-lg"></i>
          </div>
        </button>
        
        <!-- Additional Images -->
        @if(!empty($gallery))
          @foreach($gallery as $image)
          <button @click="activeImage = '{{ $image }}'"
                  @dblclick="showImageModal = true; modalImage = '{{ $image }}'"
                  class="relative rounded-xl overflow-hidden hover:ring-2 ring-blue-500 transition-all duration-300
                         shadow hover:shadow-lg transform hover:-translate-y-0.5 hover:scale-105"
                  :class="{ 'ring-2 ring-offset-2': activeImage === '{{ $image }}' }">
            <img src="{{ $image }}" class="w-full h-24 object-cover" alt="Gallery">
            <div class="absolute inset-0 gallery-overlay opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
              <i class="fas fa-search-plus text-white text-lg"></i>
            </div>
          </button>
          @endforeach
        @endif
      </div>
      
      <!-- Image Modal -->
      <div x-show="showImageModal" 
           x-transition:enter="transition ease-out duration-300" 
           x-transition:enter-start="opacity-0" 
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-200" 
           x-transition:leave-start="opacity-100" 
           x-transition:leave-end="opacity-0"
           class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm"
           @click="showImageModal = false"
           x-cloak>
        <div class="relative max-w-7xl max-h-[90vh] w-full mx-4">
          <!-- Close Button -->
          <button @click="showImageModal = false" 
                  class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors z-10">
            <i class="fas fa-times text-2xl"></i>
          </button>
          
          <!-- Image -->
          <img :src="modalImage" 
               class="w-full h-full object-contain rounded-lg shadow-2xl" 
               alt="Full Size Image"
               @click.stop>
          
          <!-- Navigation Controls -->
          <div class="absolute inset-y-0 left-0 flex items-center">
            <button @click.stop="
              let currentIndex = gallery.indexOf(modalImage);
              let newIndex = currentIndex > 0 ? currentIndex - 1 : gallery.length - 1;
              modalImage = gallery[newIndex];
            " class="bg-black/50 hover:bg-black/70 text-white p-3 rounded-r-lg transition-colors">
              <i class="fas fa-chevron-left text-xl"></i>
            </button>
          </div>
          
          <div class="absolute inset-y-0 right-0 flex items-center">
            <button @click.stop="
              let currentIndex = gallery.indexOf(modalImage);
              let newIndex = currentIndex < gallery.length - 1 ? currentIndex + 1 : 0;
              modalImage = gallery[newIndex];
            " class="bg-black/50 hover:bg-black/70 text-white p-3 rounded-l-lg transition-colors">
              <i class="fas fa-chevron-right text-xl"></i>
            </button>
          </div>
          
          <!-- Image Counter -->
          <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/70 text-white px-4 py-2 rounded-full">
            <span x-text="gallery.indexOf(modalImage) + 1"></span> / <span x-text="gallery.length"></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Location Details -->
    <div class="bg-white rounded-3xl shadow-lg ring-1 ring-black/5 overflow-hidden slide-up">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-200 px-8 py-6">
        <h1 class="text-4xl font-bold mb-2 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent 
                   leading-tight">
          {{ $location->name }}
        </h1>
        <div class="flex items-center text-gray-600">
          <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
          <span class="font-medium">{{ $location->city ?? 'Sri Lanka' }}</span>
        </div>
      </div>

      <!-- Content Section -->
      <div class="p-8">
        <div class="grid lg:grid-cols-3 gap-8">
          <!-- Main Description -->
          <div class="lg:col-span-2 space-y-6">
            <div>
              <h2 class="text-2xl font-semibold mb-4 flex items-center text-gray-900">
                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                About This Location
              </h2>
              <div class="prose max-w-none">
                <div class="bg-gray-50 rounded-xl p-6 border-l-4 border-blue-500">
                  <p class="text-gray-700 text-lg leading-relaxed mb-4">{{ $location->description }}</p>
                  
                  @if(!empty($location->additional_description))
                    <p class="text-gray-700 text-lg leading-relaxed">{{ $location->additional_description }}</p>
                  @endif
                </div>
              </div>
            </div>

            @if(!empty($things))
              <div>
                <h2 class="text-2xl font-semibold mb-6 flex items-center text-gray-900">
                  <i class="fas fa-clipboard-list text-green-500 mr-3"></i>
                  Good to Know
                </h2>
                <div class="grid sm:grid-cols-2 gap-4">
                  @foreach($things as $item)
                    <div class="flex items-start space-x-3 p-4 rounded-xl bg-green-50 hover:bg-green-100 
                                transition-all duration-300 border border-green-200 transform hover:-translate-y-1 hover:shadow-md fade-in"
                         style="animation-delay: {{ $loop->index * 0.05 }}s;">
                      <i class="fas fa-check-circle text-green-500 mt-1"></i>
                      <span class="text-gray-700 font-medium">{{ $item }}</span>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>

          <!-- Location Info Sidebar -->
          <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-6 border border-blue-200">
              <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas fa-location-dot text-blue-500 mr-2"></i>
                Location Details
              </h3>
              
              <div class="space-y-4">
                <!-- Coordinates if available -->
                @if($location->latitude && $location->longitude)
                  <div class="flex items-center space-x-3">
                    <i class="fas fa-globe text-blue-500"></i>
                    <div>
                      <p class="text-sm font-medium text-gray-900">Coordinates</p>
                      <p class="text-xs text-gray-600">{{ number_format($location->latitude, 6) }}, {{ number_format($location->longitude, 6) }}</p>
                    </div>
                  </div>
                @endif

                <!-- Hotels count -->
                @if($hotels->count() > 0)
                  <div class="flex items-center space-x-3">
                    <i class="fas fa-hotel text-green-500"></i>
                    <div>
                      <p class="text-sm font-medium text-gray-900">Available Hotels</p>
                      <p class="text-xs text-gray-600">{{ $hotels->count() }} {{ Str::plural('hotel', $hotels->count()) }} nearby</p>
                    </div>
                  </div>
                @endif

                <!-- Action Buttons -->
                <div class="pt-4 border-t border-blue-200 space-y-3">
                  @if($location->latitude && $location->longitude)
                    <a href="https://www.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}" 
                       target="_blank"
                       class="w-full flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 
                              text-white px-4 py-3 rounded-lg transition-colors font-medium">
                      <i class="fas fa-map"></i>
                      <span>View on Google Maps</span>
                    </a>
                  @endif
                  
                  <button class="w-full flex items-center justify-center space-x-2 bg-white hover:bg-gray-50 
                                 text-blue-600 border-2 border-blue-600 px-4 py-3 rounded-lg transition-colors font-medium">
                    <i class="fas fa-heart"></i>
                    <span>Add to Favorites</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Enhanced Hotels Section --}}
  <section id="hotels" class="bg-white rounded-3xl shadow-lg p-8 ring-1 ring-black/5 slide-up">
    <div class="flex flex-col sm:flex-row items-center justify-between mb-12 gap-4">
      <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
        Available Hotels
      </h2>
      @if($hotels->count() > 0)
        <div class="flex items-center space-x-2 bg-blue-50 text-blue-800 px-5 py-2 rounded-full">
          <i class="fas fa-hotel text-blue-600"></i>
          <span class="font-medium">
            {{ $hotels->count() }} {{ Str::plural('Hotel', $hotels->count()) }} Found
          </span>
        </div>
      @endif
    </div>

    @if($hotels->count())
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($hotels as $h)
          @php
            $hImg = null;
            // Check for main_image first
            if ($h->main_image) {
              $hImg = asset('storage/' . $h->main_image);
            } else {
              // Fallback to placeholder
              $hImg = asset('images/hotel-placeholder.jpg');
            }
          @endphp

          <article class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 
                         overflow-hidden ring-1 ring-black/5 hover:ring-blue-500/20 fade-in" 
                   style="animation-delay: {{ $loop->index * 0.1 }}s;">
            <div class="aspect-[4/3] relative overflow-hidden">
              <img src="{{ $hImg }}" alt="{{ $h->name }}" 
                   class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
              
              {{-- Enhanced Hotel Stats Overlay --}}
              <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent">
                <div class="absolute inset-x-4 bottom-4 flex items-center justify-between text-white">
                  <div class="flex items-center bg-black/60 backdrop-blur-md px-4 py-2 rounded-full">
                    @if($h->star_rating && $h->star_rating > 0)
                      <div class="flex items-center space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                          @if($i <= $h->star_rating)
                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                          @else
                            <i class="fas fa-star text-gray-400 text-sm"></i>
                          @endif
                        @endfor
                        <span class="font-medium ml-2">{{ $h->star_rating }}-Star</span>
                      </div>
                    @else
                      <div class="flex items-center">
                        <i class="fa fa-hotel text-blue-400 mr-2"></i>
                        <span class="font-medium">New Hotel</span>
                      </div>
                    @endif
                  </div>
                  @if($h->price_range)
                    <span class="bg-blue-600/90 backdrop-blur-md px-4 py-2 rounded-full font-medium">
                      {{ $h->price_range }}
                    </span>
                  @endif
                </div>
              </div>
            </div>

            <div class="p-6">
              @if(Route::has('tourist.hotels.show'))
                <a href="{{ route('tourist.hotels.show', $h->id) }}">
                  <h3 class="font-bold text-xl mb-3 group-hover:text-blue-600 transition-colors hover:text-blue-600 cursor-pointer">
                    {{ $h->name }}
                  </h3>
                </a>
              @else
                <h3 class="font-bold text-xl mb-3 group-hover:text-blue-600 transition-colors">
                  {{ $h->name }}
                </h3>
              @endif
              
              @if(!empty($h->city) || !empty($h->address))
                <div class="flex items-start space-x-3 text-gray-600 mb-4">
                  <i class="fa fa-map-marker-alt text-blue-600 mt-1"></i>
                  <div class="text-sm leading-tight">
                    <span class="font-medium text-gray-900">{{ $h->city ?? '' }}</span>
                    @if($h->address)
                      <span class="block text-gray-500 mt-1">{{ $h->address }}</span>
                    @endif
                  </div>
                </div>
              @endif

              {{-- Enhanced Amenities Preview --}}
              @if(!empty($h->amenities))
                <div class="flex flex-wrap gap-2 mb-4">
                  @foreach(array_slice((is_array($h->amenities) ? $h->amenities : json_decode($h->amenities, true)) ?? [], 0, 3) as $amenity)
                    <span class="flex items-center space-x-1 bg-blue-50 text-blue-800 px-3 py-1.5 rounded-full text-sm">
                      <i class="fas fa-check text-blue-600"></i>
                      <span>{{ $amenity }}</span>
                    </span>
                  @endforeach
                </div>
              @endif

              <div class="flex items-center justify-between mt-6 pt-6 border-t">
                @if($h->price_per_night)
                  <div class="text-gray-600">
                    <span class="text-3xl font-bold text-gray-900">${{ number_format($h->price_per_night) }}</span>
                    <span class="text-sm font-medium">/night</span>
                  </div>
                @endif
                
                @if(Route::has('tourist.hotels.show'))
                  <a href="{{ route('tourist.hotels.show', $h->id) }}"
                     class="group inline-flex items-center space-x-2 bg-gradient-to-r from-blue-600 to-purple-600 
                            text-white px-6 py-2.5 rounded-full font-medium hover:shadow-lg transition duration-300">
                    <span>View Details</span>
                    <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                  </a>
                @else
                  <button class="inline-flex items-center space-x-2 bg-gradient-to-r from-gray-600 to-gray-700 
                                text-white px-6 py-2.5 rounded-full font-medium opacity-75 cursor-not-allowed"
                          disabled>
                    <span>View Details</span>
                    <i class="fas fa-arrow-right"></i>
                  </button>
                @endif
              </div>
            </div>
          </article>
        @endforeach
      </div>
    @else
      <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
          <i class="fas fa-hotel text-blue-500 text-3xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">No Hotels Available</h3>
        <p class="text-gray-500 max-w-md mx-auto">
          We're currently expanding our network. Check back soon for available hotels in this location.
        </p>
      </div>
    @endif
  </section>

</main>

{{-- Simple footer (reuse your own if you have a partial) --}}
<x-tourist.footer />

</body>
</html>
