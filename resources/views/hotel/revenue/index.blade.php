@extends('hotel.layouts.app')

@section('title', 'Revenue Dashboard')

@section('content')
    <!-- Compact Header like Room Management -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-dark-100 p-6 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 mb-6 transition-colors duration-300">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-dark-700">Revenue Dashboard</h1>
            <p class="text-gray-600 dark:text-dark-500">Monitor and analyze your hotel's revenue performance</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Period Filter -->
            <div class="relative">
                <select id="periodFilter" class="appearance-none bg-white dark:bg-dark-100 border border-gray-300 dark:border-dark-200 rounded-lg px-4 py-2.5 pr-10 text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:text-dark-700 transition-colors duration-300">
                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="yesterday" {{ $period == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                    <option value="this_week" {{ $period == 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="last_week" {{ $period == 'last_week' ? 'selected' : '' }}>Last Week</option>
                    <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_month" {{ $period == 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="this_year" {{ $period == 'this_year' ? 'selected' : '' }}>This Year</option>
                    <option value="last_year" {{ $period == 'last_year' ? 'selected' : '' }}>Last Year</option>
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 dark:text-dark-400 pointer-events-none"></i>
            </div>
            <a href="{{ route('hotel.revenue.report') }}" class="bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded-lg transition-colors text-sm flex items-center gap-2">
                <i class="fas fa-file-alt"></i>
                Detailed Report
            </a>
        </div>
    </div>

    <div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Bookings -->
        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-lg p-6 border dark:border-dark-200 transition-colors duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-dark-400">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-dark-700">{{ number_format($totalBookings) }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Booking Value -->
        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-lg p-6 border dark:border-dark-200 transition-colors duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-dark-400">Total Booking Value</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-dark-700">${{ number_format($totalBookingValue, 2) }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <i class="fas fa-dollar-sign text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Your Revenue -->
        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-lg p-6 border dark:border-dark-200 transition-colors duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-dark-400">Your Revenue (90%)</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">${{ number_format($hotelRevenue, 2) }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <i class="fas fa-hotel text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Platform Fee -->
        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-lg p-6 border dark:border-dark-200 transition-colors duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-dark-400">Platform Fee (10%)</p>
                    <p class="text-2xl font-bold text-gray-600 dark:text-dark-500">${{ number_format($adminCommission, 2) }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-dark-200 p-3 rounded-full">
                    <i class="fas fa-percentage text-gray-600 dark:text-dark-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Revenue Trend -->
        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-lg p-6 border dark:border-dark-200 transition-colors duration-300">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-dark-700 mb-4">Monthly Revenue Trend (Last 12 Months)</h3>
            <div class="h-64 relative">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>

        <!-- Daily Revenue -->
        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-lg p-6 border dark:border-dark-200 transition-colors duration-300">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-dark-700 mb-4">Daily Revenue (Last 7 Days)</h3>
            <div class="h-64 relative">
                <canvas id="dailyRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue by Room Type -->
        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-lg p-6 border dark:border-dark-200 transition-colors duration-300">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-dark-700 mb-4">Revenue by Room Type</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-dark-200">
                            <th class="text-left py-3 font-medium text-gray-700 dark:text-dark-500">Room Type</th>
                            <th class="text-right py-3 font-medium text-gray-700 dark:text-dark-500">Bookings</th>
                            <th class="text-right py-3 font-medium text-gray-700 dark:text-dark-500">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roomTypeRevenue as $roomType)
                        <tr class="border-b border-gray-100 dark:border-dark-200">
                            <td class="py-3">
                                <div class="font-medium text-gray-900 dark:text-dark-700">{{ $roomType->roomType->name ?? 'N/A' }}</div>
                                <div class="text-gray-500 dark:text-dark-400 text-xs">{{ $roomType->total_rooms_booked }} rooms booked</div>
                            </td>
                            <td class="text-right py-3 text-gray-700 dark:text-dark-500">{{ number_format($roomType->booking_count) }}</td>
                            <td class="text-right py-3 font-medium text-blue-600">${{ number_format($roomType->hotel_revenue, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-gray-500">No data available for selected period</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Guests -->
        <div class="bg-white dark:bg-dark-100 rounded-xl shadow-lg p-6 border dark:border-dark-200 transition-colors duration-300">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-dark-700 mb-4">Top Guests by Spending</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-dark-200">
                            <th class="text-left py-3 font-medium text-gray-700 dark:text-dark-500">Guest</th>
                            <th class="text-right py-3 font-medium text-gray-700 dark:text-dark-500">Bookings</th>
                            <th class="text-right py-3 font-medium text-gray-700 dark:text-dark-500">Total Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topGuests as $guest)
                        <tr class="border-b border-gray-100 dark:border-dark-200">
                            <td class="py-3">
                                <div class="font-medium text-gray-900 dark:text-dark-700">{{ $guest->tourist->name ?? 'N/A' }}</div>
                                <div class="text-gray-500 dark:text-dark-400 text-xs">${{ number_format($guest->avg_booking_value, 2) }} avg</div>
                            </td>
                            <td class="text-right py-3 text-gray-700 dark:text-dark-500">{{ number_format($guest->booking_count) }}</td>
                            <td class="text-right py-3 font-medium text-green-600 dark:text-green-400">${{ number_format($guest->total_spent, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-gray-500 dark:text-dark-400">No data available for selected period</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Period filter change
    document.getElementById('periodFilter').addEventListener('change', function() {
        window.location.href = '{{ route("hotel.revenue.index") }}?period=' + this.value;
    });

    // Prepare monthly data
    const monthlyData = @json($monthlyRevenue);
    const monthlyLabels = monthlyData.map(item => item.month_name || 'N/A');
    const monthlyRevenues = monthlyData.map(item => parseFloat(item.hotel_revenue || 0));

    // Prepare daily data (last 7 days)
    const dailyData = @json($dailyRevenueChart);
    const dailyLabels = dailyData.map(item => {
        const date = new Date(item.date);
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(today.getDate() - 1);
        
        if (date.toDateString() === today.toDateString()) {
            return 'Today';
        } else if (date.toDateString() === yesterday.toDateString()) {
            return 'Yesterday';
        } else {
            return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
        }
    });
    const dailyRevenues = dailyData.map(item => parseFloat(item.hotel_revenue || 0));
    
    // Debug daily data
    console.log('Daily Revenue Data Debug:', {
        rawData: dailyData,
        labels: dailyLabels,
        revenues: dailyRevenues,
        hasData: dailyRevenues.some(val => val > 0)
    });

    // Monthly Revenue Chart (Line Chart)
    const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels.length ? monthlyLabels : ['No Data'],
            datasets: [{
                label: 'Hotel Revenue',
                data: monthlyRevenues.length ? monthlyRevenues : [0],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: $' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString('en-US', {minimumFractionDigits: 0});
                        }
                    }
                }
            }
        }
    });

    // Daily Revenue Chart (Line Chart)
    const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyLabels.length ? dailyLabels : ['No Data'],
            datasets: [{
                label: 'Daily Revenue',
                data: dailyRevenues.length ? dailyRevenues : [0],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: $' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString('en-US', {minimumFractionDigits: 0});
                        }
                    }
                }
            }
        }
    });

    // Add empty state handling
    if (monthlyData.length === 0) {
        document.querySelector('#monthlyRevenueChart').parentElement.innerHTML = 
            '<div class="h-64 flex items-center justify-center text-gray-500"><div class="text-center"><i class="fas fa-chart-line text-4xl mb-2 opacity-50"></i><p>No monthly data available</p></div></div>';
    }
    
    if (dailyData.length === 0) {
        document.querySelector('#dailyRevenueChart').parentElement.innerHTML = 
            '<div class="h-64 flex items-center justify-center text-gray-500"><div class="text-center"><i class="fas fa-chart-bar text-4xl mb-2 opacity-50"></i><p>No daily data available</p></div></div>';
    }
});
</script>
@endpush
@endsection
