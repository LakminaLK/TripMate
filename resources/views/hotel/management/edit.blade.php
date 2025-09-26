@extends('hotel.layouts.app')

@section('title', 'Edit Hotel Details')

@push('scripts')
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
            <h1 class="text-3xl font-bold text-gray-800 dark:text-dark-700">Edit Hotel Details</h1>
            <p class="text-gray-600 dark:text-dark-500">Update your hotel information, upload images, and manage facilities</p>
        </div>
        <div class="flex gap-3">
            <button type="submit" form="hotel-edit-form"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors text-sm flex items-center gap-2 font-medium">
                <i class="fas fa-save"></i>
                Save Changes
            </button>
            <a href="{{ route('hotel.management.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors text-sm flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Back to Overview
            </a>
        </div>
    </div>

    <!-- Form Container with Better Structure -->
    <form id="hotel-edit-form" action="{{ route('hotel.management.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Hidden fields for removed form elements -->
        <input type="hidden" name="location_id" value="{{ old('location_id', $hotel->location_id) }}">
        <input type="hidden" name="website" value="{{ old('website', $hotel->website) }}">
        <input type="hidden" name="status" value="{{ old('status', $hotel->status ?? 'active') }}">
        <input type="hidden" name="star_rating" value="{{ old('star_rating', $hotel->star_rating) }}">
        
        
        
        <div class="space-y-6">
            
            <!-- Basic Information -->
            <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 transition-colors duration-300">
                <div class="border-b border-gray-200 dark:border-dark-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-dark-700 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-white mb-2" for="description">
                                Hotel Description
                            </label>
                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-dark-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50 dark:bg-dark-100 text-gray-900 dark:text-dark-500"
                                placeholder="Describe your hotel, its amenities, location, and what makes it special..."
                            >{{ old('description', $hotel->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Address -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-white mb-2" for="address">
                                    Street Address
                                </label>
                                <input
                                    type="text"
                                    id="address"
                                    name="address"
                                    value="{{ old('address', $hotel->address) }}"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-dark-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50 dark:bg-dark-100 text-gray-900 dark:text-dark-500"
                                    placeholder="123 Main Street"
                                >
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Mobile Number -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-white mb-2" for="phone">
                                    Mobile Number (Sri Lanka)
                                </label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 dark:border-dark-200 bg-gray-50 dark:bg-dark-100 text-gray-600 dark:text-dark-500 text-sm font-medium">
                                        +94
                                    </span>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone', str_replace('+94', '', $hotel->phone ?? '')) }}"
                                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-dark-200 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50 dark:bg-dark-100 text-gray-900 dark:text-dark-500"
                                        placeholder="771234567"
                                        maxlength="9"
                                        pattern="[0-9]{9}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)"
                                    >
                                </div>
                                <p class="text-xs text-gray-500 dark:text-dark-400 mt-1">Enter 9 digits (e.g., 771234567)</p>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Star Rating -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-white mb-2" for="star_rating">
                                <i class="fas fa-star text-yellow-500 mr-1"></i>
                                Hotel Star Rating
                            </label>
                            <select
                                id="star_rating"
                                name="star_rating"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-dark-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-dark-100 text-gray-900 dark:text-dark-500 appearance-none"
                                style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9Im5vbmUiIHZpZXdCb3g9IjAgMCAyMCAyMCI+PHBhdGggc3Ryb2tlPSIjNmI3MjgwIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIHN0cm9rZS13aWR0aD0iMS41IiBkPSJtNiA5IDQgNCA0LTQiLz48L3N2Zz4='); background-position: right 12px center; background-repeat: no-repeat; background-size: 16px;"
                            >
                                <option value="">Select Star Rating</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('star_rating', $hotel->star_rating) == $i ? 'selected' : '' }}>
                                        {{ $i }} Star{{ $i > 1 ? 's' : '' }} {{ str_repeat('⭐', $i) }}
                                    </option>
                                @endfor
                            </select>
                            <p class="text-xs text-gray-500 dark:text-dark-400 mt-1">Select the official star rating of your hotel (1-5 stars)</p>
                            @error('star_rating')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Coordinates -->
                <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 transition-colors duration-300">
                    <div class="border-b border-gray-200 dark:border-dark-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-dark-700 flex items-center">
                            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                            Map Location
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-dark-500 mt-1">Set your hotel's exact location on the map</p>
                    </div>
                    <div class="p-6">
                        <!-- Google Maps Share Link Input -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-white mb-2" for="map_url">
                                Google Maps Link
                            </label>
                            <input
                                type="url"
                                id="map_url"
                                name="map_url"
                                value="{{ old('map_url', $hotel->map_url) }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-dark-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50 dark:bg-dark-100 text-gray-900 dark:text-dark-500"
                                placeholder="Paste Google Maps share link (e.g., https://maps.app.goo.gl/...)"
                            >
                            @error('map_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">How to get your Google Maps link:</h4>
                                    <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                                        1. Go to <a href="https://www.google.com/maps" target="_blank" class="underline">Google Maps</a><br>
                                        2. Search for your hotel location<br>
                                        3. Click the "Share" button and copy the link
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images and Facilities Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Images Section -->
                <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 transition-colors duration-300">
                    <div class="border-b border-gray-200 dark:border-dark-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-dark-700 flex items-center">
                            <i class="fas fa-images text-green-600 mr-2"></i>
                            Hotel Images
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Main Image -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-white mb-2" for="main_image">
                                Main Hotel Image
                            </label>
                            @if($hotel->main_image)
                                <div class="mb-4">
                                    <div class="relative inline-block">
                                        <img src="{{ asset('storage/' . $hotel->main_image) }}" 
                                             alt="Current main image" 
                                             class="w-full h-32 object-cover rounded-lg border dark:border-dark-200">
                                        <button type="button" 
                                                onclick="deleteMainImage()"
                                                class="absolute -top-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm opacity-80 hover:opacity-100 transition-all">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-dark-400 mt-2">Current main image</p>
                                </div>
                            @endif
                            
                            <!-- Custom Upload Area for Main Image -->
                            <div class="relative">
                                <input
                                    type="file"
                                    id="main_image"
                                    name="main_image"
                                    accept="image/*"
                                    class="hidden"
                                    onchange="previewMainImage(this)"
                                >
                                <label for="main_image" class="block w-full cursor-pointer">
                                    <div class="border-2 border-dashed border-blue-300 dark:border-blue-600 rounded-lg p-6 text-center hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all duration-200 bg-blue-50/50 dark:bg-blue-900/5">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                <i class="fas fa-cloud-upload-alt text-blue-600 dark:text-blue-400 text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-blue-700 dark:text-blue-300">
                                                    Click to upload main image
                                                </p>
                                                <p class="text-xs text-blue-500 dark:text-blue-400 mt-1">
                                                    Or drag and drop
                                                </p>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-dark-400">
                                                JPG, PNG, JPEG or WebP (Max 2MB)
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Main Image Preview -->
                            <div id="main_image_preview" class="mt-3 hidden">
                                <p class="text-sm font-medium text-gray-700 dark:text-dark-500 mb-2">New Main Image Preview:</p>
                                <div class="relative">
                                    <img id="main_image_preview_img" src="" alt="Main image preview" 
                                         class="w-full h-32 object-cover rounded-lg border dark:border-dark-200">
                                    <button type="button" onclick="clearMainImagePreview()" 
                                            class="absolute -top-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm opacity-80 hover:opacity-100 transition-all">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @error('main_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Additional Images -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-white mb-2" for="additional_images">
                                Additional Images
                            </label>
                            @if($hotel->images->count() > 0)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-dark-500 mb-2">Current images ({{ $hotel->images->count() }}):</p>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach($hotel->images as $image)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     alt="{{ $image->alt_text }}" 
                                                     class="w-full h-16 object-cover rounded border dark:border-dark-200">
                                                <button type="button" 
                                                        onclick="deleteImage({{ $image->id }})"
                                                        class="absolute -top-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-80 hover:opacity-100 transition-all">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Custom Upload Area for Additional Images -->
                            <div class="relative">
                                <input
                                    type="file"
                                    id="additional_images"
                                    name="additional_images[]"
                                    accept="image/*"
                                    multiple
                                    class="hidden"
                                    onchange="previewAdditionalImages(this)"
                                >
                                <label for="additional_images" class="block w-full cursor-pointer">
                                    <div class="border-2 border-dashed border-blue-300 dark:border-blue-600 rounded-lg p-6 text-center hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all duration-200 bg-blue-50/50 dark:bg-blue-900/5">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                <i class="fas fa-images text-blue-600 dark:text-blue-400 text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-blue-700 dark:text-blue-300">
                                                    Click to upload additional images
                                                </p>
                                                <p class="text-xs text-blue-500 dark:text-blue-400 mt-1">
                                                    Select multiple files
                                                </p>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-dark-400">
                                                JPG, PNG, JPEG or WebP (2MB each)
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <!-- Additional Images Preview -->
                            <div id="additional_images_preview" class="mt-3 hidden">
                                <p class="text-sm font-medium text-gray-700 dark:text-dark-500 mb-2">New Additional Images Preview:</p>
                                <div id="additional_images_grid" class="grid grid-cols-3 gap-2"></div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-dark-400 mt-1">Select multiple images (2MB each) - JPG, PNG, JPEG or WebP</p>
                            @error('additional_images')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Facilities Section -->
                <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 transition-colors duration-300">
                    <div class="border-b border-gray-200 dark:border-dark-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-dark-700 flex items-center">
                            <i class="fas fa-concierge-bell text-purple-600 mr-2"></i>
                            Hotel Facilities
                            @if($hotel->facilities->count() > 0)
                                <span class="ml-2 px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs rounded-full">
                                    {{ $hotel->facilities->count() }}
                                </span>
                            @endif
                        </h3>
                    </div>
                    <div class="p-6">
                        @php
                            $selectedFacilities = old('facilities', $hotel->facilities->pluck('id')->toArray());
                            $facilitiesByCategory = $facilities->groupBy('category');
                        @endphp
                        
                        <div class="space-y-4">
                            @foreach($facilitiesByCategory as $category => $categoryFacilities)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-white mb-2 capitalize">
                                        {{ str_replace('_', ' ', $category) }}
                                    </h4>
                                    <div class="grid grid-cols-1 gap-1">
                                        @foreach($categoryFacilities as $facility)
                                            <label class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50 dark:hover:bg-dark-200/50 cursor-pointer transition-colors duration-200">
                                                <input
                                                    type="checkbox"
                                                    name="facilities[]"
                                                    value="{{ $facility->id }}"
                                                    {{ in_array($facility->id, $selectedFacilities) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 dark:border-dark-200 text-blue-600 focus:ring-blue-500 dark:bg-dark-100 dark:checked:bg-blue-600 dark:focus:ring-blue-400"
                                                >
                                                <i class="{{ $facility->icon ?? 'fas fa-check' }} text-gray-600 dark:text-dark-400 text-sm"></i>
                                                <span class="text-sm text-gray-700 dark:text-dark-500">{{ $facility->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('facilities')
                            <p class="text-red-500 text-sm mt-4">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- JavaScript for delete functionality -->
    <script>
    // Delete image function
    function deleteImage(imageId) {
        fetch(`/hotel/management/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error deleting image');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting image: ' + error.message);
        });
    }

    // Delete main image function
    function deleteMainImage() {
        fetch('/hotel/management/main-image', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error deleting main image');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting main image: ' + error.message);
        });
    }
    </script>
@endsection

@push('flash-scripts')
<script>
// Clear main image preview
function clearMainImagePreview() {
    const preview = document.getElementById('main_image_preview');
    const input = document.getElementById('main_image');
    
    preview.classList.add('hidden');
    input.value = '';
}

// Preview main image
function previewMainImage(input) {
    const preview = document.getElementById('main_image_preview');
    const previewImg = document.getElementById('main_image_preview_img');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Check file size (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            showToast('File size must be less than 2MB', 'error');
            input.value = '';
            return;
        }
        
        // Check file type
        if (!file.type.startsWith('image/')) {
            showToast('Please select an image file', 'error');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

// Preview additional images
function previewAdditionalImages(input) {
    const preview = document.getElementById('additional_images_preview');
    const grid = document.getElementById('additional_images_grid');
    
    // Clear existing previews
    grid.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        let validFiles = [];
        
        // Validate files
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            
            // Check file size (2MB = 2097152 bytes)
            if (file.size > 2097152) {
                showToast(`File "${file.name}" is too large. Max size is 2MB`, 'error');
                continue;
            }
            
            // Check file type
            if (!file.type.startsWith('image/')) {
                showToast(`File "${file.name}" is not an image`, 'error');
                continue;
            }
            
            validFiles.push(file);
        }
        
        if (validFiles.length === 0) {
            input.value = '';
            preview.classList.add('hidden');
            return;
        }
        
        preview.classList.remove('hidden');
        
        // Show up to 20 images for preview (or all if less than 20)
        const filesToShow = Math.min(validFiles.length, 20);
        
        for (let i = 0; i < filesToShow; i++) {
            const file = validFiles[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imgDiv = document.createElement('div');
                imgDiv.className = 'relative group';
                imgDiv.innerHTML = `
                    <img src="${e.target.result}" 
                         alt="Preview ${i + 1}" 
                         class="w-full h-16 object-cover rounded border">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded flex items-center justify-center">
                        <span class="text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            ${file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name}
                        </span>
                    </div>
                `;
                grid.appendChild(imgDiv);
            }
            
            reader.readAsDataURL(file);
        }
        
        // Show count if more than 20 files
        if (validFiles.length > 20) {
            const countDiv = document.createElement('div');
            countDiv.className = 'col-span-3 text-center py-2';
            countDiv.innerHTML = `
                <span class="text-xs text-gray-500">
                    +${validFiles.length - 20} more images selected
                </span>
            `;
            grid.appendChild(countDiv);
        }
    } else {
        preview.classList.add('hidden');
    }
}
        
        // Show count if more than 20 files
        if (input.files.length > 20) {
            const countDiv = document.createElement('div');
            countDiv.className = 'col-span-3 text-center py-2';
            countDiv.innerHTML = `
                <span class="text-xs text-gray-500">
                    +${input.files.length - 20} more images selected
                </span>
            `;
            grid.appendChild(countDiv);
        }
    } else {
        preview.classList.add('hidden');
    }
}

// Toast notification for actions
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-0 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Form submission handler
document.addEventListener('DOMContentLoaded', function() {
    // Initialize drag and drop for upload areas
    initializeDragAndDrop();
    
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Get phone input value
            const phoneInput = document.getElementById('phone');
            if (phoneInput && phoneInput.value) {
                // Ensure the phone number is exactly 9 digits
                if (phoneInput.value.length !== 9 || !/^[0-9]{9}$/.test(phoneInput.value)) {
                    e.preventDefault();
                    showToast('Please enter exactly 9 digits for mobile number', 'error');
                    return false;
                }
            }
            showToast('Saving hotel information...', 'success');
        });
    }
    
    // Format phone input on page load
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        // Remove +94 prefix if it exists in the value for display
        let currentValue = phoneInput.value;
        if (currentValue.startsWith('+94')) {
            phoneInput.value = currentValue.substring(3);
        }
        if (currentValue.startsWith('94')) {
            phoneInput.value = currentValue.substring(2);
        }
        if (currentValue.startsWith('0')) {
            phoneInput.value = currentValue.substring(1);
        }
    }
});

// Initialize drag and drop functionality
function initializeDragAndDrop() {
    const mainImageLabel = document.querySelector('label[for="main_image"]');
    const additionalImagesLabel = document.querySelector('label[for="additional_images"]');
    
    // Main image drag and drop
    if (mainImageLabel) {
        setupDragAndDrop(mainImageLabel, 'main_image', false);
    }
    
    // Additional images drag and drop
    if (additionalImagesLabel) {
        setupDragAndDrop(additionalImagesLabel, 'additional_images', true);
    }
}

// Setup drag and drop for an element
function setupDragAndDrop(element, inputId, multiple = false) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        element.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        element.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        element.addEventListener(eventName, unhighlight, false);
    });

    element.addEventListener('drop', (e) => handleDrop(e, inputId, multiple), false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        element.classList.add('border-blue-500', 'bg-blue-100', 'dark:bg-blue-900/20');
    }

    function unhighlight(e) {
        element.classList.remove('border-blue-500', 'bg-blue-100', 'dark:bg-blue-900/20');
    }
}

// Handle dropped files
function handleDrop(e, inputId, multiple) {
    const dt = e.dataTransfer;
    const files = dt.files;
    const input = document.getElementById(inputId);
    
    if (input) {
        // Create a new FileList-like object
        if (multiple) {
            input.files = files;
            previewAdditionalImages(input);
        } else {
            // For single file, only take the first one
            if (files.length > 0) {
                const fileArray = Array.from(files).slice(0, 1);
                const newFileList = createFileList(fileArray);
                input.files = newFileList;
                previewMainImage(input);
            }
        }
    }
}

// Create a FileList-like object (for single file upload)
function createFileList(files) {
    const dt = new DataTransfer();
    files.forEach(file => dt.items.add(file));
    return dt.files;
}
</script>
@endpush
