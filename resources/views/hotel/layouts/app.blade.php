<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hotel Dashboard') | TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
    @stack('head-scripts')
</head>
<body class="bg-gradient-to-br from-green-50 to-teal-100 min-h-screen font-sans">
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
