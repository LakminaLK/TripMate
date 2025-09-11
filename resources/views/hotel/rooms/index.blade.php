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
                <span class="text-sm font-medium text-gray-500">Total Rooms Added:</span>
                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                    {{ $hotelRooms->sum('room_count') }}
                </span>
            </div>
            

        </div>
    </div>

    <!-- Pricing Information -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-sm border border-blue-200 mb-6">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">
                        💡 Pricing Guidelines & Commission Structure
                    </h3>
                    <div class="grid gap-3 text-sm text-gray-700">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                            <span><strong>Commission:</strong> TripMate charges a 10% commission on each confirmed booking</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                            <span><strong>Your Earnings:</strong> You receive 90% of the total booking amount</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                            <span><strong>Pricing Strategy:</strong> Set your room prices considering the 10% commission to maintain desired profit margins</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                            <span><strong>Example:</strong> If you want to earn $90 per night, set your room price at $100 per night</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                            <span><strong>Payment:</strong> Commission is automatically deducted from confirmed bookings before payment to your account</span>
                        </div>
                    </div>
                    
                </div>
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
                        
                        <div class="bg-white rounded-xl p-5 border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center flex-1">
                                    @if($roomType->icon)
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors">
                                            <i class="{{ $roomType->icon }} text-green-600 text-lg"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors">
                                            <i class="fas fa-bed text-green-600 text-lg"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 text-sm">{{ $roomType->name }}</h4>
                                        @if($roomType->description)
                                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ Str::limit($roomType->description, 65) }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($roomType->max_occupancy)
                                    <div class="flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-3 py-1.5 rounded-full shadow-sm ml-3">
                                        <i class="fas fa-users text-xs mr-1.5"></i>
                                        <span class="text-xs font-semibold whitespace-nowrap">
                                            Max {{ $roomType->max_occupancy }} {{ $roomType->max_occupancy == 1 ? 'guest' : 'guests' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="space-y-4">
                                <!-- Room Count -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-hashtag text-gray-400 mr-1"></i>
                                        Number of Rooms
                                    </label>
                                    <input type="number" 
                                           name="room_counts[{{ $roomType->id }}]" 
                                           value="{{ $currentCount }}"
                                           min="0" 
                                           max="999"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                                           placeholder="0">
                                </div>
                                
                                <!-- Price Per Night -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-dollar-sign text-gray-400 mr-1"></i>
                                        Price per Night
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-3.5 text-gray-500 text-sm">$</span>
                                        <input type="number" 
                                               name="prices[{{ $roomType->id }}]" 
                                               value="{{ $currentPrice }}"
                                               min="0" 
                                               max="999999.99"
                                               step="0.01"
                                               class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                                               placeholder="0.00">
                                    </div>
                                </div>
                                
                                @if($roomType->base_price)
                                    <div class="flex items-center text-xs text-gray-500 bg-gray-100 rounded-lg px-3 py-2 mt-2">
                                        <i class="fas fa-lightbulb text-yellow-500 mr-1.5"></i>
                                        <span>Suggested: <span class="font-semibold text-gray-700">${{ number_format($roomType->base_price, 2) }}</span></span>
                                    </div>
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
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Set room count to 0 to remove a room type from your hotel
                        </p>
                        <button type="submit" 
                                form="room-management-form"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg transition-colors text-sm flex items-center justify-center gap-2 font-medium shadow-sm hover:shadow-md">
                            <i class="fas fa-save"></i>
                            Save Changes
                        </button>
                    </div>
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
