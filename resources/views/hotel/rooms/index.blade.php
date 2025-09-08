@extends('hotel.layouts.app')

@section('title', 'Room Management')

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
    <!-- Compact Header like Hotel Management -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Room Management</h1>
            <p class="text-gray-600">Manage your hotel's room inventory and pricing</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Total Rooms Badge -->
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-gray-500">Total Rooms:</span>
                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                    {{ $hotelRooms->sum('room_count') }}
                </span>
            </div>
            
            <!-- Action Button -->
            <div class="flex gap-2">
                <button type="submit" 
                        form="room-management-form"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Room Types Form -->
    <form method="POST" action="{{ route('hotel.rooms.update') }}" id="room-management-form" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl shadow-md border border-gray-200">
            <div class="border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-bed text-blue-600 mr-2"></i>
                    Room Types & Inventory
                </h3>
                <p class="text-sm text-gray-600 mt-1">Set how many rooms you have for each room type</p>
            </div>
            
            <div class="p-6">
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($roomTypes as $roomType)
                        @php
                            $hotelRoom = $hotelRooms->get($roomType->id);
                            $currentCount = $hotelRoom ? $hotelRoom->room_count : 0;
                            $currentPrice = $hotelRoom ? $hotelRoom->price_per_night : '';
                        @endphp
                        
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-green-300 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    @if($roomType->icon)
                                        <i class="{{ $roomType->icon }} text-green-600 text-xl mr-2"></i>
                                    @else
                                        <i class="fas fa-bed text-green-600 text-xl mr-2"></i>
                                    @endif
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $roomType->name }}</h4>
                                        @if($roomType->description)
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($roomType->description, 60) }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($roomType->max_occupancy)
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        Max {{ $roomType->max_occupancy }} guests
                                    </span>
                                @endif
                            </div>
                            
                            <div class="space-y-3">
                                <!-- Room Count -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Number of Rooms
                                    </label>
                                    <input type="number" 
                                           name="room_counts[{{ $roomType->id }}]" 
                                           value="{{ $currentCount }}"
                                           min="0" 
                                           max="999"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                           placeholder="0">
                                </div>
                                
                                <!-- Price Per Night -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Price per Night (Optional)
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                                        <input type="number" 
                                               name="prices[{{ $roomType->id }}]" 
                                               value="{{ $currentPrice }}"
                                               min="0" 
                                               max="999999.99"
                                               step="0.01"
                                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                               placeholder="0.00">
                                    </div>
                                </div>
                                
                                @if($roomType->base_price)
                                    <p class="text-xs text-gray-500">
                                        Suggested: ${{ number_format($roomType->base_price, 2) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($roomTypes->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-bed text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-4">No room types available</p>
                        <p class="text-sm text-gray-500">Room types will be available once they are added to the system</p>
                    </div>
                @endif
            </div>
            
            @if($roomTypes->isNotEmpty())
                <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 rounded-b-xl">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Set room count to 0 to remove a room type from your hotel
                    </p>
                </div>
            @endif
        </div>
    </form>

    <!-- Current Inventory Summary -->
    @if($hotelRooms->isNotEmpty())
        <div class="bg-white rounded-xl shadow-md border border-gray-200 mt-8">
            <div class="border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                    Current Inventory Summary
                </h3>
            </div>
            
            <div class="p-6">
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    @foreach($hotelRooms as $hotelRoom)
                        <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $hotelRoom->roomType->name }}</h4>
                                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $hotelRoom->room_count }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $hotelRoom->room_count == 1 ? 'room' : 'rooms' }}
                                    </p>
                                </div>
                                @if($hotelRoom->roomType->icon)
                                    <i class="{{ $hotelRoom->roomType->icon }} text-green-600 text-2xl"></i>
                                @endif
                            </div>
                            @if($hotelRoom->price_per_night)
                                <div class="mt-2 pt-2 border-t border-green-200">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">${{ number_format($hotelRoom->price_per_night, 2) }}</span> per night
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between text-lg font-semibold">
                        <span>Total Rooms:</span>
                        <span class="text-green-600">{{ $hotelRooms->sum('room_count') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
