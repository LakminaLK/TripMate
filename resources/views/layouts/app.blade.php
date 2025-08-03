<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- ✅ Navigation with auth check -->
        <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <div class="text-xl font-bold text-gray-800">
        Trip Mate
    </div>

    <div>
        @php
            $tourist = Auth::guard('tourist')->user();
        @endphp

        @if($tourist)
            <span class="text-sm font-medium text-gray-700 mr-4">
                Hi, {{ $tourist->name }}!
            </span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                    Logout
                </button>
            </form>
        @else
            <a href="{{ route('login') }}"
               class="text-sm font-medium text-gray-700 hover:text-blue-600 mr-4">Login</a>
            <a href="{{ route('register') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Sign Up
            </a>
        @endif
    </div>
</nav>


        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
