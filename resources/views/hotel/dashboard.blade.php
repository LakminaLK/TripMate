@extends('hotel.layouts.app')

@section('title', 'Hotel Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-dark-100 p-6 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 mb-6 transition-colors duration-300">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-dark-700">Dashboard Overview</h1>
            <p class="text-gray-600 dark:text-dark-500">Welcome back, {{ auth('hotel')->user()->name ?? 'Hotel' }}</p>
        </div>
        <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-dark-400">
            <i class="fas fa-calendar"></i>
            <span>{{ now()->format('l, F j, Y') }}</span>
        </div>
    </div>

    <!-- Dashboard Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @php
            $cards = [
                [
                    'label' => 'Total Rooms',
                    'count' => $totalRooms ?? 0,
                    'icon' => 'fas fa-bed',
                    'bg' => 'bg-green-500',
                    'hover' => 'hover:bg-green-600'
                ],
                [
                    'label' => 'Total Bookings',
                    'count' => $totalBookings ?? 0,
                    'icon' => 'fas fa-calendar-check',
                    'bg' => 'bg-blue-500',
                    'hover' => 'hover:bg-blue-600'
                ],
                [
                    'label' => 'Bookings Today',
                    'count' => $bookingsToday ?? 0,
                    'icon' => 'fas fa-calendar-plus',
                    'bg' => 'bg-cyan-500',
                    'hover' => 'hover:bg-cyan-600'
                ],
                [
                    'label' => 'Total Reviews',
                    'count' => $totalReviews ?? 0,
                    'icon' => 'fas fa-star',
                    'bg' => 'bg-purple-500',
                    'hover' => 'hover:bg-purple-600'
                ],
                [
                    'label' => 'Average Rating',
                    'count' => number_format($averageRating ?? 0, 1) . '/5',
                    'icon' => 'fas fa-star-half-alt',
                    'bg' => 'bg-yellow-500',
                    'hover' => 'hover:bg-yellow-600',
                    'isRating' => true
                ],
                [
                    'label' => 'Revenue (Last 30 Days)',
                    'count' => '$' . number_format($last30DaysRevenue ?? 0, 2),
                    'icon' => 'fas fa-dollar-sign',
                    'bg' => 'bg-orange-500',
                    'hover' => 'hover:bg-orange-600',
                    'isRevenue' => true
                ]
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="{{ $card['bg'] }} p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 {{ $card['hover'] }}">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white bg-opacity-25 rounded-lg flex items-center justify-center mr-4">
                        <i class="{{ $card['icon'] }} text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        @if(isset($card['isRevenue']) || isset($card['isRating']))
                            <div class="text-2xl font-bold text-white mb-1">{{ $card['count'] }}</div>
                        @else
                            <div class="text-2xl font-bold text-white mb-1">{{ number_format($card['count']) }}</div>
                        @endif
                        <div class="text-sm font-medium text-white text-opacity-90">{{ $card['label'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Additional Info - Revenue Chart -->
    <div class="bg-white dark:bg-dark-100 p-6 rounded-xl shadow-md border border-gray-200 dark:border-dark-200 transition-colors duration-300">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-dark-700">Revenue Overview</h3>
                <p class="text-gray-600 dark:text-dark-500 text-sm">Daily revenue earnings from hotel bookings (Hotel Share: 90%)</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Period Filter for Revenue Chart -->
                <div class="relative">
                    <select id="periodFilter" class="appearance-none bg-white dark:bg-dark-100 border border-gray-300 dark:border-dark-200 rounded-lg px-4 py-2.5 pr-10 text-sm font-medium focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 dark:text-dark-700">
                        <option value="last_7_days" {{ ($period ?? 'last_month') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="last_month" {{ ($period ?? 'last_month') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        <option value="last_3_months" {{ ($period ?? 'last_month') == 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                        <option value="last_year" {{ ($period ?? 'last_month') == 'last_year' ? 'selected' : '' }}>Last Year</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 dark:text-dark-400 pointer-events-none"></i>
                </div>
            </div>
        </div>
        <div class="h-80 relative">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Period filter change
    document.getElementById('periodFilter').addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('period', this.value);
        window.location.href = url.toString();
    });

    // Revenue Chart
    const revenueData = @json($revenueData ?? []);
    
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }),
            datasets: [{
                label: 'Daily Revenue',
                data: revenueData.map(item => parseFloat(item.revenue)),
                borderColor: '#f97316',
                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#f97316',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151',
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#f97316',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: $' + parseFloat(context.parsed.y).toFixed(2);
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
                        color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280',
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? 'rgba(75, 85, 99, 0.3)' : 'rgba(229, 231, 235, 0.5)'
                    },
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280',
                        font: {
                            size: 11
                        },
                        callback: function(value) {
                            return '$' + value.toFixed(0);
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
});
</script>
@endpush
@endsection
