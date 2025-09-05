<!-- Desktop Sidebar -->
<div class="w-64 bg-white fixed left-0 h-full p-6 space-y-2 text-sm font-medium pt-6 overflow-y-auto hidden md:block shadow-xl border-r border-gray-100" style="top: 80px;">
    @php
        $menuItems = [
            [
                'route' => 'admin.dashboard',
                'routePattern' => 'admin.dashboard',
                'label' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt'
            ],
            [
                'route' => 'admin.customers',
                'routePattern' => 'admin.customers',
                'label' => 'Customers',
                'icon' => 'fas fa-users'
            ],
            [
                'route' => 'admin.activities.index',
                'routePattern' => 'admin.activities.*',
                'label' => 'Activities',
                'icon' => 'fas fa-calendar-check'
            ],
            [
                'route' => 'admin.locations.index',
                'routePattern' => 'admin.locations.*',
                'label' => 'Locations',
                'icon' => 'fas fa-map-marker-alt'
            ],
            [
                'route' => 'admin.hotels.index',
                'routePattern' => 'admin.hotels.*',
                'label' => 'Hotels',
                'icon' => 'fas fa-hotel'
            ]
        ];
        
        $disabledItems = [
            ['label' => 'Bookings', 'icon' => 'fas fa-booking'],
            ['label' => 'Reviews', 'icon' => 'fas fa-star']
        ];
    @endphp

    @foreach($menuItems as $item)
        <a href="{{ route($item['route']) }}"
           class="{{ request()->routeIs($item['routePattern']) ? 'bg-gradient-to-r from-amber-600 to-yellow-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gradient-to-r hover:from-amber-50 hover:to-yellow-50 hover:text-amber-600' }} flex items-center px-4 py-3 rounded-xl transition-all duration-200 group">
            <i class="{{ $item['icon'] }} mr-3 {{ request()->routeIs($item['routePattern']) ? 'text-white' : 'text-gray-500 group-hover:text-amber-600' }}"></i>
            {{ $item['label'] }}
        </a>
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
