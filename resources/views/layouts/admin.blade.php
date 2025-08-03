<!-- layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | TripMate</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans text-gray-800">
    <div class="flex">
        @include('components.admin.sidebar')
        <div class="flex-1 min-h-screen">
            @include('components.admin.topbar')
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
