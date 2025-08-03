<!-- resources/views/admin/layout/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - TripMate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 font-sans">

    {{-- Optional Admin Navbar --}}
    @includeIf('admin.layout.navbar')

    <div class="min-h-screen p-6">
        @yield('content')
    </div>

</body>
</html>
