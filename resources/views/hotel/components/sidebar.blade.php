<!-- Desktop Sidebar -->
<div class="w-64 bg-white fixed left-0 h-full p-6 space-y-2 text-sm font-medium pt-6 overflow-y-auto hidden md:block shadow-xl border-r border-gray-100" style="top: 80px;">
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
                'route' => '#',
                'routePattern' => 'hotel.reviews.*',
                'label' => 'Reviews',
                'icon' => 'fas fa-star'
            ],
            [
                'route' => '#',
                'routePattern' => 'hotel.settings.*',
                'label' => 'Settings',
                'icon' => 'fas fa-cog'
            ]
        ];
        
        $disabledItems = [
            ['label' => 'Analytics', 'icon' => 'fas fa-chart-bar'],
            ['label' => 'Reports', 'icon' => 'fas fa-file-alt']
        ];
    @endphp

    @foreach($menuItems as $item)
        @php
            $isActive = request()->routeIs($item['routePattern']);
            $isDisabled = $item['route'] === '#';
        @endphp
        
        @if($isDisabled)
            <span class="flex items-center px-4 py-3 text-gray-400 cursor-not-allowed rounded-xl">
                <i class="{{ $item['icon'] }} mr-3 text-gray-400"></i>
                {{ $item['label'] }}
            </span>
        @else
            <a href="{{ route($item['route']) }}"
               class="{{ $isActive ? 'bg-gradient-to-r from-green-600 to-teal-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gradient-to-r hover:from-green-50 hover:to-teal-50 hover:text-green-600' }} flex items-center px-4 py-3 rounded-xl transition-all duration-200 group">
                <i class="{{ $item['icon'] }} mr-3 {{ $isActive ? 'text-white' : 'text-gray-500 group-hover:text-green-600' }}"></i>
                {{ $item['label'] }}
            </a>
        @endif
    @endforeach

    <div class="pt-4 border-t border-gray-200 mt-4">
        @foreach($disabledItems as $item)
            <span class="flex items-center px-4 py-3 text-gray-400 cursor-not-allowed rounded-xl">
                <i class="{{ $item['icon'] }} mr-3 text-gray-400"></i>
                {{ $item['label'] }}
            </span>
        @endforeach
    </div>
</div>
