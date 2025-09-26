<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="h-screen flex">

    <!-- Left side image -->
    <div class="w-1/2 hidden lg:block">
        <img src="{{ asset('images/login.jpg') }}" alt="Travel Image" class="w-full h-full object-cover">
    </div>

    <!-- Right login form -->
    <div class="w-full lg:w-1/2 flex justify-center items-center bg-gray-50 px-6" x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)">
        <div 
            x-show="show"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-x-10"
            x-transition:enter-end="opacity-100 translate-x-0"
            class="w-full max-w-md"
        >
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Welcome Back!</h2>
            <p class="text-center text-sm text-gray-500 mb-6">Join us in exploring breathtaking destinations</p>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                
                <!-- Hidden field to store redirect URL -->
                @if(request('redirect'))
                    <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                @endif

                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-blue-500" required>

                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" placeholder="Password"
                        class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-blue-500" required>
                    <button type="button" @click="show = !show" class="absolute right-3 inset-y-0 flex items-center">
                        <!-- Eye icons -->
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
                        class="w-full py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Login
                </button>
            </form>

            <p class="mt-4 text-sm text-center text-gray-600">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Sign up</a>
            </p>
            <p class="mt-4 text-sm text-center text-gray-600">
                Forget your password?
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Forgot Password</a>
            </p>
        </div>
    </div>

    <!-- Toast Notifications - Only for errors, no individual field errors -->
    @if (session('success') || session('error') || $errors->any())
    <div 
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-500"
        x-transition:leave="transition ease-in duration-300"
        class="fixed top-6 right-6 z-50"
    >
        @if (session('success'))
            <div class="bg-green-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between gap-4 min-w-80">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-white text-xl font-bold hover:bg-green-700 rounded px-2">&times;</button>
            </div>
        @elseif (session('error'))
            <div class="bg-red-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between gap-4 min-w-80">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-white text-xl font-bold hover:bg-red-700 rounded px-2">&times;</button>
            </div>
        @elseif ($errors->any())
            <div class="bg-red-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-start justify-between gap-4 min-w-80">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        @if($errors->count() == 1)
                            <span>{{ $errors->first() }}</span>
                        @else
                            <div>
                                <div class="font-semibold mb-1">Please fix the following:</div>
                                <ul class="text-sm space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                <button @click="show = false" class="text-white text-xl font-bold hover:bg-red-700 rounded px-2">&times;</button>
            </div>
        @endif
    </div>
    @endif

</body>
</html>