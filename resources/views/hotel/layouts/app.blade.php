<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hotel Dashboard') | TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
    
    <!-- Alpine.js cloaking styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <!-- Immediate Dark Mode Detection (runs before any content renders) -->
    <script>
        (function() {
            // Check localStorage immediately and apply dark mode class if needed
            const isDarkMode = localStorage.getItem('tripmate_darkMode') === 'true';
            if (isDarkMode) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            '50': '#18181b',
                            '100': '#27272a',
                            '200': '#3f3f46',
                            '300': '#52525b',
                            '400': '#71717a',
                            '500': '#a1a1aa',
                            '600': '#d4d4d8',
                            '700': '#e4e4e7',
                            '800': '#f4f4f5',
                            '900': '#fafafa'
                        }
                    }
                }
            }
        }
        
        // Alpine.js store for dark mode
        document.addEventListener('alpine:init', () => {
            Alpine.store('darkMode', {
                on: false,
                _initialized: false,
                
                init() {
                    // Check if dark mode is already applied to the document
                    this.on = document.documentElement.classList.contains('dark');
                    this._initialized = true;
                    console.log('Dark mode store initialized with current state:', this.on);
                },
                
                toggle() {
                    if (!this._initialized) {
                        console.warn('Dark mode store not yet initialized');
                        return;
                    }
                    this.on = !this.on;
                    console.log('Dark mode toggled to:', this.on);
                    this.updateDOM();
                    localStorage.setItem('tripmate_darkMode', this.on.toString());
                },
                
                updateDOM() {
                    if (this.on) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            });
        });
    </script>
    @stack('head-scripts')
</head>
<body class="bg-gradient-to-br from-green-50 to-teal-100 dark:from-dark-50 dark:to-gray-900 min-h-screen font-sans transition-colors duration-300" 
      x-data 
      x-init="setTimeout(() => { $store.darkMode.init(); }, 0)">
    <div class="mt-20">
        
        @stack('flash-scripts')
        
        @include('hotel.components.navbar')
        
        @include('hotel.components.toast')
        
        <div class="flex min-h-screen">
            @include('hotel.components.sidebar')
            @include('hotel.components.mobile-sidebar')
            
            <!-- Main Content -->
            <div class="flex-1 md:ml-64 p-4 md:p-10 pt-20">
                @yield('content')
            </div>
        </div>
    </div>
    
    @include('hotel.components.scripts')
    @stack('scripts')
</body>
</html>
