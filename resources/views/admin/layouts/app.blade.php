<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard') | TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
    @stack('head-scripts')
</head>
<body class="bg-gradient-to-br from-amber-50 to-yellow-100 min-h-screen font-sans">
    <div class="mt-20">
        
        @stack('flash-scripts')
        
        @include('admin.components.navbar')
        
        @include('admin.components.toast')
        
        <div class="flex min-h-screen">
            @include('admin.components.sidebar')
            @include('admin.components.mobile-sidebar')
            
            <!-- Main Content -->
            <div class="flex-1 md:ml-64 p-4 md:p-10 pt-20">
                @yield('content')
            </div>
        </div>
    </div>
    
    @include('admin.components.scripts')
    @stack('scripts')
</body>
</html>
