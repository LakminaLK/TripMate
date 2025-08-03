<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome Back</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 font-sans">

<!-- 🌟 Login Card -->
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)" class="min-h-screen flex items-center justify-center px-4">
    <div 
        x-show="loaded"
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 translate-y-5 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        class="bg-white shadow-xl rounded-2xl w-full max-w-sm p-8"
    >
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Welcome Back!</h2>
        <p class="text-center text-sm text-gray-500 mb-6">Join us in exploring breathtaking destinations</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <input type="email" name="email" placeholder="Email"
                   class="w-full mb-4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" required />

            <div x-data="{ show: false }" class="relative mb-4">
                <input :type="show ? 'text' : 'password'" name="password" placeholder="Password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-3 flex items-center">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.943-9.543-7a10.055 10.055 0 011.352-2.568M6.827 6.827A9.961 9.961 0 0112 5c4.478 0 8.269 2.943 9.543 7a9.955 9.955 0 01-4.194 5.568M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3l18 18" />
                    </svg>
                </button>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                Login
            </button>
        </form>

        <p class="mt-5 text-center text-sm">
            Don’t have an account?
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Sign up</a>
        </p>
    </div>
</div>

<!-- 🌟 Toast Notification -->
<div 
    x-data="{ show: {{ session('success') || session('error') || $errors->any() ? 'true' : 'false' }} }"
    x-init="setTimeout(() => show = false, 4000)"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    class="fixed top-6 right-6 z-50"
>
    @if (session('success'))
        <div class="bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-between gap-4">
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-white text-lg font-bold">&times;</button>
        </div>
    @elseif (session('error'))
        <div class="bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-between gap-4">
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="text-white text-lg font-bold">&times;</button>
        </div>
    @elseif ($errors->any())
        <div class="bg-yellow-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-start justify-between gap-4">
            <ul class="list-disc ml-4 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button @click="show = false" class="text-white text-lg font-bold mt-1">&times;</button>
        </div>
    @endif
</div>

</body>
</html>
