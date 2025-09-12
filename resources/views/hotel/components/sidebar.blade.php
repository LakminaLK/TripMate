<!-- Desktop Sidebar -->
<div class="w-64 bg-white dark:bg-dark-100 fixed left-0 h-full p-6 space-y-2 text-sm font-medium pt-6 overflow-y-auto hidden md:block shadow-xl border-r border-gray-100 dark:border-dark-200 transition-colors duration-300" style="top: 80px;">
    @php
        $menuItems = [
            [
                'route' => 'hotel.dashboard',
                'routePattern' => 'hotel.dashboard',
                'label' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt'
            ],
            [
                'route' => 'hotel.management.index',
                'routePattern' => 'hotel.management.*',
                'label' => 'Hotel Management',
                'icon' => 'fas fa-hotel'
            ],
            
            [
                'route' => 'hotel.rooms.index',
                'routePattern' => 'hotel.rooms.*',
                'label' => 'Rooms',
                'icon' => 'fas fa-bed'
            ],
            [
                'route' => 'hotel.bookings.index',
                'routePattern' => 'hotel.bookings.*',
                'label' => 'Bookings',
                'icon' => 'fas fa-calendar-check'
            ],
            [
                'route' => 'hotel.revenue.index',
                'routePattern' => 'hotel.revenue.*',
                'label' => 'Revenue',
                'icon' => 'fas fa-chart-line'
            ],
            [
                'route' => 'hotel.reviews.index',
                'routePattern' => 'hotel.reviews.*',
                'label' => 'Reviews',
                'icon' => 'fas fa-star'
            ]
        ];
    @endphp

    @foreach($menuItems as $item)
        @php
            $isActive = request()->routeIs($item['routePattern']);
            $isDisabled = $item['route'] === '#';
        @endphp
        
        @if($isDisabled)
            <span class="flex items-center px-4 py-3 text-gray-400 dark:text-dark-400 cursor-not-allowed rounded-xl">
                <i class="{{ $item['icon'] }} mr-3 text-gray-400 dark:text-dark-400"></i>
                {{ $item['label'] }}
            </span>
        @else
            <a href="{{ route($item['route']) }}"
               class="{{ $isActive ? 'bg-gradient-to-r from-green-600 to-teal-600 text-white shadow-lg' : 'text-gray-700 dark:text-dark-500 hover:bg-gradient-to-r hover:from-green-50 hover:to-teal-50 dark:hover:from-green-900 dark:hover:to-teal-900 hover:text-green-600 dark:hover:text-green-400' }} flex items-center px-4 py-3 rounded-xl transition-all duration-200 group">
                <i class="{{ $item['icon'] }} mr-3 {{ $isActive ? 'text-white' : 'text-gray-500 dark:text-dark-400 group-hover:text-green-600 dark:group-hover:text-green-400' }}"></i>
                {{ $item['label'] }}
            </a>
        @endif
    @endforeach
</div>
