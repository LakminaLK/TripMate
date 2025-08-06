<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - TripMate</title>
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
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Forgot Password?</h2>
        <p class="text-center text-sm text-gray-500 mb-6">We'll send a password reset link to your email</p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="bg-green-500 text-white text-sm px-4 py-3 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

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
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input type="email" name="email" id="email" placeholder="you@example.com"
                   class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-blue-500"
                   required autofocus>

            <button type="submit"
                    class="w-full py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Send Reset Link
            </button>
        </form>

        <!-- Back to Login -->
        <p class="mt-4 text-sm text-center text-gray-600">
            Remember your password?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Back to login</a>
        </p>
    </div>

</body>
</html>
