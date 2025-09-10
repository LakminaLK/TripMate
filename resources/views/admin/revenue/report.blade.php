@extends('admin.layouts.app')

@section('title', 'Revenue Report')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <h1 class="text-2xl font-semibold text-gray-900">Revenue Report</h1>
            <span class="text-sm text-gray-600">Detailed commission and booking analysis</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.revenue.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-lg transition-colors text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="GET" action="{{ route('admin.revenue.report') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" 
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" 
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2.5 rounded-lg transition-colors text-sm font-medium">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_bookings']) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Booking Value</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($summary['total_booking_value'], 2) }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Admin Commission</p>
                    <p class="text-2xl font-bold text-amber-600">${{ number_format($summary['total_commission'], 2) }}</p>
                </div>
                <div class="bg-amber-100 p-3 rounded-full">
                    <i class="fas fa-percentage text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Hotel Revenue</p>
                    <p class="text-2xl font-bold text-purple-600">${{ number_format($summary['total_hotel_revenue'], 2) }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-hotel text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Bookings Table -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Details</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 font-medium text-gray-700">Booking ID</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-700">Hotel</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-700">Guest</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-700">Date</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-700">Total Amount</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-700">Commission (10%)</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-700">Hotel Revenue (90%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-900">{{ $booking->booking_reference }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->status }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-900">{{ $booking->hotel->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->roomType->name ?? 'N/A' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-900">{{ $booking->tourist->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->tourist->email ?? 'N/A' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-900">{{ $booking->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->created_at->format('H:i A') }}</div>
                        </td>
                        <td class="py-3 px-4 text-right font-medium text-gray-900">
                            ${{ number_format($booking->total_amount, 2) }}
                        </td>
                        <td class="py-3 px-4 text-right font-medium text-amber-600">
                            ${{ number_format($booking->admin_commission, 2) }}
                        </td>
                        <td class="py-3 px-4 text-right font-medium text-purple-600">
                            ${{ number_format($booking->hotel_revenue, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">
                            No bookings found for the selected date range.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div class="mt-6">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
