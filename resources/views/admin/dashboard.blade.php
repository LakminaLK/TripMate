@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
            <p class="text-gray-600">Monitor platform performance and revenue analytics</p>
        </div>
    </div>

    <div class="space-y-8">
        <!-- Dashboard Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $cards = [
                    [
                        'label' => 'Total Hotels',
                        'count' => $totalHotels,
                        'icon' => 'fas fa-hotel',
                        'bg' => 'bg-blue-500',
                        'hover' => 'hover:bg-blue-600'
                    ],
                    [
                        'label' => 'Total Bookings',
                        'count' => $totalBookings,
                        'icon' => 'fas fa-calendar-check',
                        'bg' => 'bg-green-500',
                        'hover' => 'hover:bg-green-600'
                    ],
                    [
                        'label' => 'Total Activities',
                        'count' => $totalActivities,
                        'icon' => 'fas fa-map-marked-alt',
                        'bg' => 'bg-purple-500',
                        'hover' => 'hover:bg-purple-600'
                    ],
                    [
                        'label' => 'Total Locations',
                        'count' => $totalLocations,
                        'icon' => 'fas fa-map-marker-alt',
                        'bg' => 'bg-red-500',
                        'hover' => 'hover:bg-red-600'
                    ],
                    [
                        'label' => 'Total Customers',
                        'count' => $totalCustomers,
                        'icon' => 'fas fa-users',
                        'bg' => 'bg-indigo-500',
                        'hover' => 'hover:bg-indigo-600'
                    ],
                    [
                        'label' => 'Revenue (Last 30 Days)',
                        'count' => '$' . number_format($last30DaysRevenue, 2),
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
                            @if(isset($card['isRevenue']))
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

        <!-- Revenue Chart -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Overview (Commission)</h3>
                    <p class="text-gray-600 text-sm">Daily commission earnings from platform bookings</p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Period Filter for Revenue Chart -->
                    <div class="relative">
                        <select id="periodFilter" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-sm font-medium focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                            <option value="last_7_days" {{ $period == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="last_month" {{ $period == 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="last_3_months" {{ $period == 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                            <option value="last_year" {{ $period == 'last_year' ? 'selected' : '' }}>Last Year</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>
            <div class="h-80 relative">
                <canvas id="revenueChart"></canvas>
            </div>
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
    const revenueData = @json($revenueData);
    
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }),
            datasets: [{
                label: 'Daily Commission',
                data: revenueData.map(item => parseFloat(item.commission)),
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
                        color: '#374151',
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
                            return 'Commission: $' + parseFloat(context.parsed.y).toFixed(2);
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
                        color: '#6b7280',
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(229, 231, 235, 0.5)'
                    },
                    ticks: {
                        color: '#6b7280',
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
