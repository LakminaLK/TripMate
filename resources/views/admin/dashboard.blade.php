@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <h1 class="text-2xl font-semibold text-gray-900">Dashboard Overview</h1>
            
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $cards = [
                        [
                            'label' => 'Total Hotels',
                            'count' => 234,
                            'icon' => 'fas fa-hotel',
                            'bg' => 'bg-blue-500',
                            'hover' => 'hover:bg-blue-600'
                        ],
                        [
                            'label' => 'Total Bookings',
                            'count' => 833,
                            'icon' => 'fas fa-calendar-check',
                            'bg' => 'bg-green-500',
                            'hover' => 'hover:bg-green-600'
                        ],
                        [
                            'label' => 'Total Activities',
                            'count' => 833,
                            'icon' => 'fas fa-map-marked-alt',
                            'bg' => 'bg-purple-500',
                            'hover' => 'hover:bg-purple-600'
                        ],
                        [
                            'label' => 'Total Locations',
                            'count' => 133,
                            'icon' => 'fas fa-map-marker-alt',
                            'bg' => 'bg-red-500',
                            'hover' => 'hover:bg-red-600'
                        ],
                        [
                            'label' => 'Total Customers',
                            'count' => 355,
                            'icon' => 'fas fa-users',
                            'bg' => 'bg-indigo-500',
                            'hover' => 'hover:bg-indigo-600'
                        ],
                        [
                            'label' => 'Total Reviews',
                            'count' => 833,
                            'icon' => 'fas fa-star',
                            'bg' => 'bg-yellow-500',
                            'hover' => 'hover:bg-yellow-600'
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
                                <div class="text-2xl font-bold text-white mb-1">{{ number_format($card['count']) }}</div>
                                <div class="text-sm font-medium text-white text-opacity-90">{{ $card['label'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sales Chart Placeholder -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Sales Overview</h3>
                <div class="bg-white p-8 rounded-xl shadow-md border border-gray-200">
                    <div class="flex items-center justify-center h-64 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-600 mb-2">Sales Chart Coming Soon</h4>
                            <p class="text-gray-500 text-sm">Interactive analytics dashboard will be available here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
