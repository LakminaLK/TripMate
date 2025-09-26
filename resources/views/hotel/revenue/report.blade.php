@extends('hotel.layouts.app')

@section('title', 'Revenue Report')

@section('content')
    <!-- Header with same style as revenue dashboard -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-dark-100 p-6 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 mb-6 transition-colors duration-300">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-dark-700">Revenue Report</h1>
            <p class="text-gray-600 dark:text-dark-500">Detailed revenue and booking analysis</p>
        </div>
        <a href="{{ route('hotel.revenue.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>Back to Dashboard
        </a>
    </div>

<div class="space-y-6">

    <!-- Date Filter -->
    <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 p-6 transition-colors duration-300">
        <form method="GET" action="{{ route('hotel.revenue.report') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-dark-500 mb-2">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" 
                       class="border border-gray-300 dark:border-dark-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 dark:bg-dark-100 text-gray-900 dark:text-dark-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-dark-500 mb-2">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" 
                       class="border border-gray-300 dark:border-dark-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 dark:bg-dark-100 text-gray-900 dark:text-dark-500">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 p-6 transition-colors duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-dark-500">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-dark-700">{{ number_format($summary['total_bookings']) }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full">
                    <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 p-6 transition-colors duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-dark-500">Total Booking Value</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-dark-700">${{ number_format($summary['total_booking_value'], 2) }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                    <i class="fas fa-dollar-sign text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 p-6 transition-colors duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-dark-500">Your Revenue (90%)</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">${{ number_format($summary['hotel_revenue'], 2) }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full">
                    <i class="fas fa-hotel text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 p-6 transition-colors duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-dark-500">Platform Fee (10%)</p>
                    <p class="text-2xl font-bold text-gray-600 dark:text-dark-500">${{ number_format($summary['admin_commission'], 2) }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700/30 p-3 rounded-full">
                    <i class="fas fa-percentage text-gray-600 dark:text-gray-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Table -->
    <div class="bg-white dark:bg-dark-100 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 overflow-hidden transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-dark-200">
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-dark-200">Booking ID</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-dark-200">Guest</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-dark-200">Room Type</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-dark-200">Date</th>
                        <th class="text-right py-4 px-6 text-sm font-semibold text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-dark-200">Total Amount</th>
                        <th class="text-right py-4 px-6 text-sm font-semibold text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-dark-200">Your Revenue (90%)</th>
                        <th class="text-right py-4 px-6 text-sm font-semibold text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-dark-200">Platform Fee (10%)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-dark-200">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-200/50 transition-colors duration-200">
                        <td class="py-4 px-6">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $booking->booking_reference }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->status }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $booking->tourist->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->tourist->email ?? 'N/A' }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $booking->roomType->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->rooms_booked }} room(s)</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $booking->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->created_at->format('H:i A') }}</div>
                        </td>
                        <td class="py-4 px-6 text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                            ${{ number_format($booking->total_amount, 2) }}
                        </td>
                        <td class="py-4 px-6 text-right text-sm font-medium text-blue-600 dark:text-blue-400">
                            ${{ number_format($booking->hotel_revenue, 2) }}
                        </td>
                        <td class="py-4 px-6 text-right text-sm font-medium text-gray-600 dark:text-gray-400">
                            ${{ number_format($booking->admin_commission, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="text-sm">No bookings found for the selected date range.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-dark-200">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
