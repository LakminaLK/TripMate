<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Login - TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .input-group {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            transition: color 0.2s ease;
        }
        .password-toggle:hover {
            color: #374151;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 to-teal-100 font-sans min-h-screen">
    <!-- Header -->
    <div class="bg-white py-8 text-center shadow-md mb-8">
        <div class="flex items-center justify-center space-x-3">
            <!-- <div class="w-10 h-10 bg-gradient-to-r from-green-600 to-teal-600 rounded-xl flex items-center justify-center">
                <span class="text-white font-bold text-lg">T</span>
            </div> -->
            <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">TripMate</h1>
        </div>
        <!-- <p class="text-gray-600 mt-2">Your Ultimate Travel Companion</p> -->
    </div>

    <!-- Login Form -->
    <div class="flex justify-center items-center min-h-[65vh] px-4 pb-12">
        <div class="bg-white p-10 rounded-2xl w-full max-w-md shadow-2xl border border-gray-100">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-r from-green-600 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-building text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Hotel Login</h2>
                <p class="text-gray-600">Access your hotel management portal</p>
            </div>

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('hotel.login') }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="username" class="block text-sm font-semibold text-gray-700">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="username" id="username" placeholder="Enter your username"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all duration-200 bg-gray-50 focus:bg-white text-gray-800 placeholder-gray-500" 
                               required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                    <div class="input-group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password" placeholder="Enter your password"
                               class="w-full pl-10 pr-12 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all duration-200 bg-gray-50 focus:bg-white text-gray-800 placeholder-gray-500" 
                               required>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password')" id="toggleIcon"></i>
                    </div>
                </div>

                <div class="flex justify-between items-center text-sm">
                    <a href="{{ route('hotel.password.request') }}" class="text-green-600 hover:text-green-700 font-medium hover:underline transition-colors duration-200 flex items-center space-x-1">
                        <i class="fas fa-key text-xs"></i>
                        <span>Forgot Password?</span>
                    </a>
                </div>

                <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-xl hover:from-green-700 hover:to-teal-700 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Sign In</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('toggleIcon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
