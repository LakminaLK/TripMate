<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center px-4">

    <!-- Centered Reset Password Form -->
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)"
         x-show="show"
         x-transition:enter="transition ease-out duration-700"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="w-full max-w-md bg-white shadow-md rounded-xl p-8"
    >
        <!-- Header -->
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Reset Password</h2>
        <p class="text-center text-sm text-gray-500 mb-6">Enter your new password below</p>

        <!-- Errors -->
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
        <!-- Form -->
<form method="POST" action="{{ route('password.store') }}" class="space-y-4">
    @csrf
    <!-- ❌ REMOVE @method('PUT') -->

    <!-- Hidden Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- Email Field -->
    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
    <input type="email" name="email" id="email"
           value="{{ old('email', $request->email) }}"
           class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-blue-500"
           placeholder="you@example.com" required>

    <!-- New Password -->
    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
    <input type="password" name="password" id="password"
           class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-blue-500"
           placeholder="New password" required>

    <!-- Confirm Password -->
    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
    <input type="password" name="password_confirmation" id="password_confirmation"
           class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-blue-500"
           placeholder="Confirm password" required>

    <!-- Submit Button -->
    <button type="submit"
            class="w-full py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
        Update Password
    </button>
</form>


        <!-- Back to login -->
        <p class="mt-4 text-sm text-center text-gray-600">
            Remembered your password?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Back to login</a>
        </p>
    </div>

</body>
</html>
