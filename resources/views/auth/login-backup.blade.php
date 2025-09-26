<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TripMate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center">
    <div class="flex w-full max-w-6xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Left Side - Image -->
        <div class="hidden lg:block lg:w-1/2 relative">
            <img src="{{ asset('images/login.jpg') }}" alt="Travel Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-center items-center text-white">
                <h2 class="text-4xl font-bold mb-4">Welcome Back!</h2>
                <p class="text-xl text-center px-8">Join us in exploring breathtaking destinations</p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
            <div class="max-w-md mx-auto w-full">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Sign In</h1>
                <p class="text-gray-600 mb-8">Access your travel dashboard</p>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Hidden field to store redirect URL -->
                    @if(request('redirect'))
                        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                    @endif

                    <div>
                        <input type="email" name="email" placeholder="Email"
                            class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-blue-500 @error('email') border-red-500 @enderror" 
                            value="{{ old('email') }}" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div x-data="{ showPassword: false }" class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" placeholder="Password"
                                class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-blue-500 @error('password') border-red-500 @enderror" 
                                required>
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                <span x-show="!showPassword">👁️</span>
                                <span x-show="showPassword">🙈</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 transition duration-300">
                        Sign In
                    </button>
                </form>

                <p class="mt-4 text-sm text-center text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Sign up</a>
                </p>
                <p class="mt-2 text-sm text-center text-gray-600">
                    Forget your password?
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Forgot Password</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    @if (session('success') || session('error') || $errors->any())
    <div 
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 4000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-500"
        x-transition:leave="transition ease-in duration-300"
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
            <div class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-start justify-between gap-4">
                <ul class="text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button @click="show = false" class="text-white text-lg font-bold mt-1">&times;</button>
            </div>
        @endif
    </div>
    @endif
</body>
</html>