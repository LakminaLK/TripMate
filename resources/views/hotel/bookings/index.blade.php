@extends('hotel.layouts.app')

@section('title', 'Booking Management')

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
    <!-- Header with Statistics -->
    <div class="bg-white dark:bg-dark-100 p-6 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 mb-6 transition-colors duration-300">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-dark-700">Booking Management</h1>
                <p class="text-gray-600 dark:text-dark-500">Manage and track all hotel bookings</p>
            </div>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-dark-400">Total</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-dark-400">Pending</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['confirmed'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-dark-400">Confirmed</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['completed'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-dark-400">Completed</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['cancelled'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-dark-400">Cancelled</div>
                </div>
            </div>
        </div>

        <!-- Filter Pills and Search -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-dark-200">
            <!-- Filter Pills -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('hotel.bookings.index') }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $statusFilter === 'all' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-700' : 'bg-gray-100 dark:bg-dark-200 text-gray-600 dark:text-dark-400 hover:bg-gray-200 dark:hover:bg-dark-300' }}">
                    All
                </a>
                <a href="{{ route('hotel.bookings.index', ['status' => 'pending']) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $statusFilter === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700' : 'bg-gray-100 dark:bg-dark-200 text-gray-600 dark:text-dark-400 hover:bg-gray-200 dark:hover:bg-dark-300' }}">
                    Pending
                </a>
                <a href="{{ route('hotel.bookings.index', ['status' => 'confirmed']) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $statusFilter === 'confirmed' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700' : 'bg-gray-100 dark:bg-dark-200 text-gray-600 dark:text-dark-400 hover:bg-gray-200 dark:hover:bg-dark-300' }}">
                    Confirmed
                </a>
                <a href="{{ route('hotel.bookings.index', ['status' => 'completed']) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $statusFilter === 'completed' ? 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-700' : 'bg-gray-100 dark:bg-dark-200 text-gray-600 dark:text-dark-400 hover:bg-gray-200 dark:hover:bg-dark-300' }}">
                    Completed
                </a>
                <a href="{{ route('hotel.bookings.index', ['status' => 'cancelled']) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $statusFilter === 'cancelled' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700' : 'bg-gray-100 dark:bg-dark-200 text-gray-600 dark:text-dark-400 hover:bg-gray-200 dark:hover:bg-dark-300' }}">
                    Cancelled
                </a>
            </div>

            <!-- Search -->
            <form method="GET" action="{{ route('hotel.bookings.index') }}" class="flex gap-2">
                @if($statusFilter !== 'all')
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                @endif
                <div class="relative">
                    <input type="text" 
                           name="q" 
                           value="{{ $search }}" 
                           placeholder="Search bookings..."
                           class="pl-10 pr-4 py-2 border border-gray-300 dark:border-dark-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-dark-100 text-gray-900 dark:text-dark-700 transition-colors duration-300">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400 dark:text-dark-400"></i>
                </div>
                <button type="submit" class="bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded-lg transition-colors">
                    Search
                </button>
            </form>
        </div>
    </div>

        <!-- Bookings Table -->
        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 overflow-hidden transition-colors duration-300">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100 dark:bg-dark-200 text-gray-700 dark:text-gray-300 text-sm uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-left">Booking ID</th>
                            <th class="px-6 py-4 text-left">Customer</th>
                            <th class="px-6 py-4 text-left">Room Details</th>
                            <th class="px-6 py-4 text-left">Dates</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-left">Total</th>
                            <th class="px-6 py-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-300 text-sm">
                        @forelse($bookings as $booking)
                            <tr class="border-t hover:bg-gray-50 dark:hover:bg-dark-200 transition duration-150 ease-in-out">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900 dark:text-gray-200">{{ $booking->formatted_id }}</span>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->booking_reference }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900 dark:text-gray-200">{{ $booking->tourist->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900 dark:text-gray-200">
                                        @if(isset($booking->room_details) && count($booking->room_details) > 0)
                                            @foreach($booking->room_details as $index => $room)
                                                @if($index > 0)<br>@endif{{ $room['room_count'] }} {{ $room['room_type_name'] }}
                                            @endforeach
                                        @else
                                            {{ $booking->rooms_booked ?? 1 }} {{ $booking->roomType->name ?? 'N/A' }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900 dark:text-gray-200">
                                        {{ $booking->check_in ? $booking->check_in->format('M d') : 'N/A' }} - 
                                        {{ $booking->check_out ? $booking->check_out->format('M d, Y') : 'N/A' }}
                                    </span>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->nights }} night(s)</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $status = $booking->status ?? $booking->booking_status ?? 'pending';
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-300',
                                            'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-300',
                                            'completed' => 'bg-purple-100 text-purple-800 dark:bg-purple-800/20 dark:text-purple-300',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-300'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-green-600 dark:text-green-400">${{ number_format($booking->total_amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button onclick="viewBookingDetails({{ $booking->id }})" 
                                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </button>
                                        <div class="w-px h-4 bg-gray-300 dark:bg-gray-600"></div>
                                        <div class="relative" x-data="{ open: false }">
                                            @php
                                                $currentStatus = $booking->status ?? $booking->booking_status ?? 'pending';
                                            @endphp
                                            
                                            @if($currentStatus === 'cancelled')
                                                <!-- Cannot change status after cancellation -->
                                                <span class="text-gray-400 dark:text-gray-500 text-sm">
                                                    <i class="fas fa-lock mr-1"></i> Locked
                                                </span>
                                            @else
                                                <button @click="open = !open" 
                                                        class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 text-sm font-medium">
                                                    <i class="fas fa-edit mr-1"></i> Status
                                                </button>
                                                <div x-show="open" 
                                                     @click.away="open = false"
                                                     x-transition
                                                     class="absolute right-0 mt-2 w-32 bg-white dark:bg-dark-200 border border-gray-200 dark:border-dark-100 rounded-lg shadow-lg z-10">
                                                    
                                                    @if($currentStatus === 'pending')
                                                        <!-- For pending bookings: show confirm and cancel options -->
                                                        <button onclick="showStatusConfirm({{ $booking->id }}, 'confirmed', 'Booking #{{ $booking->id }}')" 
                                                                class="block w-full text-left px-3 py-2 text-sm text-green-700 dark:text-green-300 hover:bg-green-50 dark:hover:bg-green-800/20 js-status-btn">
                                                            <i class="fas fa-check mr-1"></i> Confirm
                                                        </button>
                                                        <button onclick="showStatusConfirm({{ $booking->id }}, 'cancelled', 'Booking #{{ $booking->id }}')" 
                                                                class="block w-full text-left px-3 py-2 text-sm text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-800/20 js-status-btn">
                                                            <i class="fas fa-times mr-1"></i> Cancel
                                                        </button>
                                                    @elseif($currentStatus === 'confirmed')
                                                        <!-- For confirmed bookings: show complete option only -->
                                                        <button onclick="showStatusConfirm({{ $booking->id }}, 'completed', 'Booking #{{ $booking->id }}')" 
                                                                class="block w-full text-left px-3 py-2 text-sm text-purple-700 dark:text-purple-300 hover:bg-purple-50 dark:hover:bg-purple-800/20 js-status-btn">
                                                            <i class="fas fa-check-double mr-1"></i> Complete
                                                        </button>
                                                    @elseif($currentStatus === 'completed')
                                                        <!-- For completed bookings: no status changes allowed -->
                                                        <div class="px-2 py-1 text-xs bg-green-50 border border-green-200 rounded-md whitespace-nowrap overflow-hidden">
                                                            <div class="flex items-center text-green-700 truncate">
                                                                <i class="fas fa-check-circle mr-1 text-xs flex-shrink-0"></i>
                                                                <span class="font-medium truncate">Tour completed</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-times text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <span class="text-lg font-medium">No bookings found</span>
                                        <span class="text-sm">{{ $search ? 'Try adjusting your search terms.' : 'Bookings will appear here once guests make reservations.' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>        <!-- Pagination -->
        @if($bookings->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-dark-200">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Status Change Confirmation Modal -->
    <div id="status-confirm-modal" class="fixed inset-0 z-50 hidden">
      <div class="absolute inset-0 bg-black bg-opacity-20 z-10"></div>
      <div class="absolute inset-0 flex items-center justify-center z-20">
        <div class="bg-white rounded-xl shadow-2xl ring-1 ring-black/5 overflow-hidden w-[90%] max-w-md">
          <div class="px-5 py-4 flex items-start gap-3">
            <svg id="status-icon" class="w-6 h-6 shrink-0" viewBox="0 0 24 24" fill="none">
              <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z" fill="currentColor" opacity=".12"/>
              <path d="M12 8v5M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div>
              <h3 id="status-title" class="text-base font-semibold text-gray-900">Change booking status?</h3>
              <p class="text-sm text-gray-600 mt-1">
                Are you sure you want to change status of <span id="status-booking-name" class="font-medium text-gray-900">this booking</span> to <span id="status-new-status" class="font-medium"></span>?
                <span id="status-warning">This action cannot be undone.</span>
              </p>
            </div>
          </div>
          <div class="px-5 pb-5 pt-2 flex items-center justify-end gap-2">
            <button id="status-cancel" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</button>
            <button id="status-confirm" class="px-4 py-2 rounded-lg text-white">Confirm</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Booking Details Modal -->
    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-black dark:bg-opacity-70 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-dark-100 rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-gray-200 dark:border-dark-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Booking Details</h3>
                        <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <div id="bookingDetailsContent">
                        <!-- Content will be loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function viewBookingDetails(bookingId) {
    fetch(`/hotel/bookings/${bookingId}`)
        .then(response => response.json())
        .then(data => {
            console.log('Booking data received:', data); // Debug log
            const booking = data.booking;
            const tourist = data.tourist;
            const roomType = data.roomType;
            const nights = data.nights;

            const statusClasses = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'confirmed': 'bg-green-100 text-green-800',
                'completed': 'bg-purple-100 text-purple-800',
                'cancelled': 'bg-red-100 text-red-800'
            };

            const status = booking.status || booking.booking_status || 'pending';
            const statusClass = statusClasses[status] || 'bg-gray-100 text-gray-800';

            // Parse room details
            let roomDetailsHtml = '';
            const roomDetails = data.room_details || booking.room_details;
            console.log('Room details:', roomDetails); // Debug log
            
            if (roomDetails && roomDetails.length > 0) {
                roomDetailsHtml = `
                    <div class="space-y-3">
                        <h5 class="font-medium text-gray-700 dark:text-gray-300">Room Types & Quantities:</h5>
                `;
                roomDetails.forEach((room, index) => {
                    const subtotal = room.room_count * parseFloat(room.price_per_night) * nights;
                    console.log(`Room ${index + 1}:`, room, 'Subtotal:', subtotal); // Debug log
                    roomDetailsHtml += `
                        <div class="bg-gray-50 dark:bg-dark-200 p-3 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">${room.room_type_name}</span>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        <span class="font-medium">${room.room_count} room(s)</span> × 
                                        <span class="font-medium">$${parseFloat(room.price_per_night).toFixed(2)}/night</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium text-gray-800 dark:text-gray-200">
                                        $${subtotal.toFixed(2)}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        (${nights} night${nights > 1 ? 's' : ''})
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                roomDetailsHtml += `</div>`;
            } else {
                roomDetailsHtml = `
                    <div class="space-y-3">
                        <h5 class="font-medium text-gray-700 dark:text-gray-300">Room Details:</h5>
                        <div class="bg-gray-50 dark:bg-dark-200 p-3 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">${roomType ? roomType.name : 'N/A'}</span>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        <span class="font-medium">${booking.rooms_booked || 1} room(s)</span> × 
                                        <span class="font-medium">$${parseFloat(booking.price_per_night || 0).toFixed(2)}/night</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium text-gray-800 dark:text-gray-200">
                                        $${((booking.rooms_booked || 1) * parseFloat(booking.price_per_night || 0) * nights).toFixed(2)}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        (${nights} night${nights > 1 ? 's' : ''})
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            document.getElementById('bookingDetailsContent').innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Booking Information -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600 pb-2">Booking Information</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Booking ID:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${booking.formatted_id}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Reference:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${booking.booking_reference || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                                    ${status.charAt(0).toUpperCase() + status.slice(1)}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Payment Status:</span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full ${getPaymentStatusColor(status === 'cancelled' ? 'refunded' : (booking.payment_status || 'pending'))}">
                                    ${status === 'cancelled' ? 'Refunded' : ((booking.payment_status || 'N/A').charAt(0).toUpperCase() + (booking.payment_status || 'N/A').slice(1))}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Booking Date:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${new Date(booking.created_at).toLocaleDateString()}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Information -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600 pb-2">Guest Information</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Name:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${tourist.name || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Email:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${tourist.email || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Mobile:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${tourist.mobile || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stay Dates -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600 pb-2">Stay Dates</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Check-in:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${booking.check_in ? new Date(booking.check_in).toLocaleDateString() : 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Check-out:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${booking.check_out ? new Date(booking.check_out).toLocaleDateString() : 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Duration:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${nights} night${nights > 1 ? 's' : ''}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600 pb-2">Payment Summary</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Payment Method:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">${booking.payment_method || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold border-t border-gray-200 dark:border-gray-600 pt-2">
                                <span class="text-gray-800 dark:text-gray-200">Total Amount:</span>
                                <span class="text-green-600 dark:text-green-400">$${parseFloat(booking.total_amount).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Room Details Section (Full Width) -->
                <div class="mt-6 space-y-4">
                    <h4 class="font-semibold text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600 pb-2">Room Details & Pricing</h4>
                    ${roomDetailsHtml}
                </div>

                ${booking.special_requests ? `
                    <div class="mt-6 space-y-4">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600 pb-2">Special Requests</h4>
                        <p class="text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-dark-200 p-3 rounded-lg">${booking.special_requests}</p>
                    </div>
                ` : ''}
            `;

            document.getElementById('bookingModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            if (window.pushToast) {
                window.pushToast('Failed to load booking details', 'error');
            }
        });
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

function updateBookingStatus(bookingId, status) {
    fetch(`/hotel/bookings/${bookingId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Store success message in sessionStorage to show after reload
            sessionStorage.setItem('toastMessage', JSON.stringify({
                message: data.message || 'Booking status updated successfully',
                type: 'success'
            }));
            // Reload immediately
            location.reload();
        } else {
            // Show error toast immediately (no reload for errors)
            if (window.pushToast) {
                window.pushToast(data.message || 'Failed to update booking status', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.pushToast) {
            window.pushToast('Failed to update booking status. Please try again.', 'error');
        }
    });
}

function getPaymentStatusColor(status) {
    const colors = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'paid': 'bg-green-100 text-green-800',
        'failed': 'bg-red-100 text-red-800',
        'refunded': 'bg-orange-100 text-orange-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

// Status Confirmation Modal Functions
let pendingStatusChange = null;

function showStatusConfirm(bookingId, newStatus, bookingName) {
    const modal = document.getElementById('status-confirm-modal');
    const icon = document.getElementById('status-icon');
    const bookingNameSpan = document.getElementById('status-booking-name');
    const newStatusSpan = document.getElementById('status-new-status');
    const confirmBtn = document.getElementById('status-confirm');
    const warningText = document.getElementById('status-warning');
    
    // Store the pending change
    pendingStatusChange = { bookingId, newStatus };
    
    // Update modal content based on status
    bookingNameSpan.textContent = bookingName;
    newStatusSpan.textContent = newStatus;
    
    // Set appropriate colors and messages based on status
    if (newStatus === 'confirmed') {
        icon.className = 'w-6 h-6 shrink-0 text-green-600';
        confirmBtn.className = 'px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700';
        warningText.textContent = 'This will send a confirmation email to the customer.';
    } else if (newStatus === 'cancelled') {
        icon.className = 'w-6 h-6 shrink-0 text-red-600';
        confirmBtn.className = 'px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700';
        warningText.textContent = 'This action cannot be undone and will notify the customer.';
    } else if (newStatus === 'completed') {
        icon.className = 'w-6 h-6 shrink-0 text-purple-600';
        confirmBtn.className = 'px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700';
        warningText.textContent = 'This will mark the tour as completed.';
    }
    
    modal.classList.remove('hidden');
}

function closeStatusConfirm() {
    const modal = document.getElementById('status-confirm-modal');
    modal.classList.add('hidden');
    pendingStatusChange = null;
}

// Event listeners for status modal
document.addEventListener('DOMContentLoaded', function() {
    // Check for stored toast messages from sessionStorage
    const storedToast = sessionStorage.getItem('toastMessage');
    if (storedToast) {
        try {
            const toastData = JSON.parse(storedToast);
            if (window.pushToast && toastData.message) {
                window.pushToast(toastData.message, toastData.type || 'success');
            }
            // Clear the stored message
            sessionStorage.removeItem('toastMessage');
        } catch (e) {
            console.error('Error parsing stored toast message:', e);
            sessionStorage.removeItem('toastMessage');
        }
    }

    const statusModal = document.getElementById('status-confirm-modal');
    const overlay = statusModal?.querySelector('.absolute.inset-0.bg-black');
    const cancelBtn = document.getElementById('status-cancel');
    const confirmBtn = document.getElementById('status-confirm');
    
    // Close on background click
    overlay?.addEventListener('click', (e) => {
        if (e.target === overlay) closeStatusConfirm();
    });
    
    // Cancel button
    cancelBtn?.addEventListener('click', closeStatusConfirm);
    
    // Confirm button
    confirmBtn?.addEventListener('click', () => {
        if (pendingStatusChange) {
            updateBookingStatus(pendingStatusChange.bookingId, pendingStatusChange.newStatus);
            closeStatusConfirm();
        }
    });
});
</script>
@endpush
