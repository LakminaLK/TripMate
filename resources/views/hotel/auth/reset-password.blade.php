<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - TripMate Hotel Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center px-4">

    <!-- Centered Form -->
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)"
         x-show="show"
         x-transition:enter="transition ease-out duration-700"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="w-full max-w-md bg-white shadow-md rounded-xl p-8"
    >
        <!-- Header -->
        <div class="text-center mb-8">
            <img src="{{ asset('/images/tm1.png') }}" alt="TripMate Logo" class="h-12 mx-auto mb-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Set New Password</h2>
            <p class="text-center text-sm text-gray-500">Create a new password for your hotel account</p>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-500 text-white text-sm px-4 py-3 rounded mb-4">
                <ul class="list-disc ml-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('hotel.password.store') }}" class="space-y-4">
            @csrf
            
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <input type="hidden" name="email" value="{{ $request->email }}">

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" name="password" id="password" 
                       class="mt-1 w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-green-500"
                       required autofocus>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="mt-1 w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-green-500"
                       required>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                Reset Password
            </button>
        </form>
    </div>
</body>
</html>
