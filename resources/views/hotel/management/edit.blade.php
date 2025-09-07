@extends('hotel.layouts.app')

@section('title', 'Edit Hotel Details')

@push('flash-scripts')
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
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Hotel Details</h1>
            <p class="text-gray-600">Update your hotel information, upload images, and manage facilities</p>
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
            <div class="bg-white rounded-xl shadow-md border border-gray-200">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="description">
                                Hotel Description
                            </label>
                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Describe your hotel, its amenities, location, and what makes it special..."
                            >{{ old('description', $hotel->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Address -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="address">
                                    Street Address
                                </label>
                                <input
                                    type="text"
                                    id="address"
                                    name="address"
                                    value="{{ old('address', $hotel->address) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="123 Main Street"
                                >
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Mobile Number -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="phone">
                                    Mobile Number (Sri Lanka)
                                </label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-600 text-sm font-medium">
                                        +94
                                    </span>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone', str_replace('+94', '', $hotel->phone ?? '')) }}"
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="771234567"
                                        maxlength="9"
                                        pattern="[0-9]{9}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)"
                                    >
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Enter 9 digits (e.g., 771234567)</p>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Coordinates -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                            Map Location
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Set your hotel's exact location on the map</p>
                    </div>
                    <div class="p-6">
                        <!-- Google Maps Share Link Input -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="map_url">
                                Google Maps Link
                            </label>
                            <input
                                type="url"
                                id="map_url"
                                name="map_url"
                                value="{{ old('map_url', $hotel->map_url) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Paste Google Maps share link (e.g., https://maps.app.goo.gl/...)"
                            >
                            @error('map_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-800">How to get your Google Maps link:</h4>
                                    <p class="text-sm text-blue-700 mt-1">
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
                <div class="bg-white rounded-xl shadow-md border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-images text-green-600 mr-2"></i>
                            Hotel Images
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Main Image -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="main_image">
                                Main Hotel Image
                            </label>
                            @if($hotel->main_image)
                                <div class="mb-4">
                                    <div class="relative inline-block">
                                        <img src="{{ asset('storage/' . $hotel->main_image) }}" 
                                             alt="Current main image" 
                                             class="w-full h-32 object-cover rounded-lg border">
                                        <button type="button" 
                                                onclick="deleteMainImage()"
                                                class="absolute -top-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm opacity-80 hover:opacity-100 transition-all">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2">Current main image</p>
                                </div>
                            @endif
                            <input
                                type="file"
                                id="main_image"
                                name="main_image"
                                accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                onchange="previewMainImage(this)"
                            >
                            <!-- Main Image Preview -->
                            <div id="main_image_preview" class="mt-3 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">New Main Image Preview:</p>
                                <img id="main_image_preview_img" src="" alt="Main image preview" 
                                     class="w-full h-32 object-cover rounded-lg border">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG or JPEG (Max 2MB)</p>
                            @error('main_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Additional Images -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="additional_images">
                                Additional Images
                            </label>
                            @if($hotel->images->count() > 0)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">Current images ({{ $hotel->images->count() }}):</p>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach($hotel->images->take(6) as $image)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     alt="{{ $image->alt_text }}" 
                                                     class="w-full h-16 object-cover rounded border">
                                                <button type="button" 
                                                        onclick="deleteImage({{ $image->id }})"
                                                        class="absolute -top-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-80 hover:opacity-100 transition-all">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($hotel->images->count() > 6)
                                        <p class="text-xs text-gray-500 mt-2">+{{ $hotel->images->count() - 6 }} more images</p>
                                    @endif
                                </div>
                            @endif
                            <input
                                type="file"
                                id="additional_images"
                                name="additional_images[]"
                                accept="image/*"
                                multiple
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                onchange="previewAdditionalImages(this)"
                            >
                            <!-- Additional Images Preview -->
                            <div id="additional_images_preview" class="mt-3 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">New Additional Images Preview:</p>
                                <div id="additional_images_grid" class="grid grid-cols-3 gap-2"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Select multiple images (Max 10 files, 2MB each)</p>
                            @error('additional_images')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Facilities Section -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-concierge-bell text-purple-600 mr-2"></i>
                            Hotel Facilities
                            @if($hotel->facilities->count() > 0)
                                <span class="ml-2 px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">
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
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2 capitalize">
                                        {{ str_replace('_', ' ', $category) }}
                                    </h4>
                                    <div class="grid grid-cols-1 gap-1">
                                        @foreach($categoryFacilities as $facility)
                                            <label class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    name="facilities[]"
                                                    value="{{ $facility->id }}"
                                                    {{ in_array($facility->id, $selectedFacilities) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                >
                                                <i class="{{ $facility->icon ?? 'fas fa-check' }} text-gray-600 text-sm"></i>
                                                <span class="text-sm text-gray-700">{{ $facility->name }}</span>
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
@endsection

@push('scripts')
<script>
// Preview main image
function previewMainImage(input) {
    const preview = document.getElementById('main_image_preview');
    const previewImg = document.getElementById('main_image_preview_img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
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
        preview.classList.remove('hidden');
        
        // Limit to 10 images for preview
        const filesToShow = Math.min(input.files.length, 10);
        
        for (let i = 0; i < filesToShow; i++) {
            const file = input.files[i];
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
                            Image ${i + 1}
                        </span>
                    </div>
                `;
                grid.appendChild(imgDiv);
            }
            
            reader.readAsDataURL(file);
        }
        
        // Show count if more than 10 files
        if (input.files.length > 10) {
            const countDiv = document.createElement('div');
            countDiv.className = 'col-span-3 text-center py-2';
            countDiv.innerHTML = `
                <span class="text-xs text-gray-500">
                    +${input.files.length - 10} more images selected
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

// Delete image function
function deleteImage(imageId) {
    if (confirm('Are you sure you want to delete this image?')) {
        fetch(`/hotel/management/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting image');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting image');
        });
    }
}

// Delete main image function
function deleteMainImage() {
    if (confirm('Are you sure you want to delete the main image?')) {
        fetch('/hotel/management/main-image', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting main image');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting main image');
        });
    }
}
</script>
@endpush
