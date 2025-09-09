@extends('admin.layouts.app')

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
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <h1 class="text-2xl font-semibold text-gray-900">Booking Management</h1>
            
            <!-- Filter pills -->
            <div class="flex items-center gap-2">
                @php $status = strtolower(request('status','all')); @endphp
                <a href="{{ route('admin.bookings.index', array_merge(request()->except('page'), ['status'=>'all'])) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $status==='all'?'bg-gray-900 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">All</a>
                <a href="{{ route('admin.bookings.index', array_merge(request()->except('page'), ['status'=>'pending'])) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $status==='pending'?'bg-gray-900 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Pending</a>
                <a href="{{ route('admin.bookings.index', array_merge(request()->except('page'), ['status'=>'confirmed'])) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $status==='confirmed'?'bg-gray-900 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Confirmed</a>
                <a href="{{ route('admin.bookings.index', array_merge(request()->except('page'), ['status'=>'completed'])) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $status==='completed'?'bg-gray-900 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Completed</a>
                <a href="{{ route('admin.bookings.index', array_merge(request()->except('page'), ['status'=>'cancelled'])) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $status==='cancelled'?'bg-gray-900 text-white':'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Cancelled</a>
            </div>
        </div>
        
        <!-- Search -->
        <div class="flex items-center gap-3">
            <form method="GET" class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search bookings..."
                       class="pl-9 pr-3 py-2 w-full sm:w-64 rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400"
                />
                <svg class="w-5 h-5 text-gray-400 absolute left-2.5 top-2.5" viewBox="0 0 24 24" fill="none">
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </form>
        </div>
    </div>

    @include('admin.components.success-message')

    <!-- Booking Table Container -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Booking ID</th>
                        <th class="px-6 py-4 text-left">Customer ID</th>
                        <th class="px-6 py-4 text-left">Hotel ID</th>
                        <th class="px-6 py-4 text-left">Booking Date</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Total Amount</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 text-sm">
                    @forelse($bookings as $booking)
                        <tr class="border-t hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">{{ $booking->booking_id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">{{ $booking->customer_id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">{{ $booking->hotel_display_id }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $booking->booking_date }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'completed' => 'bg-blue-100 text-blue-800'
                                    ];
                                    $statusColor = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-green-600">
                                ${{ $booking->total_amount }}
                            </td>
                            <td class="px-6 py-4">
                                <button onclick="showBookingDetails({{ json_encode($booking) }})" 
                                        class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye mr-1"></i>
                                    More
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                                    <span class="text-lg font-medium">No bookings found</span>
                                    <span class="text-sm">Try adjusting your search criteria</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($bookings->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $bookings->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Booking Details Modal -->
    <div id="bookingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Booking Details</h3>
                    <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Content -->
                <div id="modalContent" class="space-y-4">
                    <!-- Content will be populated by JavaScript -->
                </div>
                
                <!-- Modal Footer -->
                <div class="flex justify-end mt-6">
                    <button onclick="closeBookingModal()" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function showBookingDetails(booking) {
    const modal = document.getElementById('bookingModal');
    const modalContent = document.getElementById('modalContent');
    
    // Create the detailed content
    modalContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Basic Information</h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Booking ID:</span>
                        <span class="font-medium">${booking.booking_id}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Reference:</span>
                        <span class="font-medium">${booking.booking_reference || 'N/A'}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Booking Date:</span>
                        <span class="font-medium">${booking.booking_date}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(booking.status)}">
                            ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Status:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${getPaymentColor(booking.payment_status)}">
                            ${booking.payment_status.charAt(0).toUpperCase() + booking.payment_status.slice(1)}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Guest Information -->
            <div class="space-y-4">
                <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Customer Information</h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Customer ID:</span>
                        <span class="font-medium">${booking.customer_id}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Customer Name:</span>
                        <span class="font-medium">${booking.tourist_name}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium text-sm">${booking.tourist_email}</span>
                    </div>
                </div>
            </div>
            
            <!-- Hotel Information -->
            <div class="space-y-4">
                <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Hotel Information</h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Hotel ID:</span>
                        <span class="font-medium">${booking.hotel_display_id}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Hotel Name:</span>
                        <span class="font-medium">${booking.hotel_name}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Check-in:</span>
                        <span class="font-medium">${booking.check_in}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Check-out:</span>
                        <span class="font-medium">${booking.check_out}</span>
                    </div>
                </div>
            </div>
            
            <!-- Booking Details -->
            <div class="space-y-4">
                <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Booking Details</h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Rooms Booked:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${booking.rooms_booked}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 text-lg">Total Amount:</span>
                        <span class="font-bold text-lg text-green-600">$${booking.total_amount}</span>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Show the modal
    modal.classList.remove('hidden');
}

function closeBookingModal() {
    const modal = document.getElementById('bookingModal');
    modal.classList.add('hidden');
}

function getStatusColor(status) {
    const colors = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800',
        'completed': 'bg-blue-100 text-blue-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

function getPaymentColor(status) {
    const colors = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'paid': 'bg-green-100 text-green-800',
        'failed': 'bg-red-100 text-red-800',
        'refunded': 'bg-gray-100 text-gray-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

// Close modal when clicking outside
document.getElementById('bookingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBookingModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBookingModal();
    }
});
</script>
@endpush
