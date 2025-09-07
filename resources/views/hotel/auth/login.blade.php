<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Login - TripMate</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center px-4">
    <!-- Toast Messages -->
    <div id="toast-container" 
         x-data="{ 
            show: false, 
            message: '', 
            type: 'success',
            showToast(message, type = 'success') {
                this.show = false;
                this.message = message;
                this.type = type;
                this.show = true;
                setTimeout(() => this.show = false, 3000);
            }
         }"
         x-init="
            @if(session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif
            @if(session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif
         "
         class="fixed top-4 right-4 z-50">
        
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             :class="{
                'bg-green-500': type === 'success',
                'bg-red-500': type === 'error'
             }"
             class="p-4 rounded-lg text-white shadow-lg flex items-center space-x-3 min-w-[300px]">
            <i class="fas" :class="{
                'fa-check-circle': type === 'success',
                'fa-exclamation-circle': type === 'error'
            }"></i>
            <span x-text="message" class="flex-1"></span>
            <button @click="show = false" class="text-white hover:text-gray-200 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
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
            <h2 class="text-3xl font-bold text-gray-800">Hotel Login</h2>
            <p class="text-gray-500 text-sm">Access your hotel management portal</p>
        </div>

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

        <!-- Login Form -->
            <form method="POST" action="{{ route('hotel.login') }}" class="space-y-4" autocomplete="on">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" 
                           name="username" 
                           id="username" 
                           autocomplete="username"
                           class="mt-1 w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-green-500" 
                           value="{{ old('username') }}" 
                           required 
                           autofocus>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           autocomplete="current-password"
                           class="mt-1 w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-green-500" 
                           required>
                </div>            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>

                <a href="{{ route('hotel.password.request') }}" class="text-sm text-green-600 hover:underline">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                Sign in
            </button>
        </form>
    </div>
</body>
</html>
