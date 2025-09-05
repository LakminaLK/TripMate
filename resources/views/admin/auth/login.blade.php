<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
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
<body class="bg-gradient-to-br from-amber-50 to-yellow-100 font-sans min-h-screen">
    <!-- Header -->
    <div class="bg-white py-8 text-center shadow-md mb-8">
        <div class="flex items-center justify-center space-x-3">
            <h1 class="text-4xl font-bold text-black">TripMate</h1>
        </div>
        <!-- <p class="text-gray-600 mt-2">Administrative Portal</p> -->
    </div>

    <!-- Login Form -->
    <div class="flex justify-center items-center min-h-[65vh] px-4 pb-12">
        <div class="bg-white p-10 rounded-2xl w-full max-w-md shadow-2xl border border-gray-100">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-r from-amber-600 to-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-shield text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Admin Login</h2>
                <p class="text-gray-600">Access your administrative dashboard</p>
            </div>

            <!-- Error Message -->
            @if ($errors->has('login'))
                <div class="bg-amber-50 border-l-4 border-amber-400 text-amber-700 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ $errors->first('login') }}</span>
                    </div>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any() && !$errors->has('login'))
                <div class="bg-amber-50 border-l-4 border-amber-400 text-amber-700 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="username" class="block text-sm font-semibold text-gray-700">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="username" id="username" placeholder="Enter your username"
                               value="{{ old('username') }}"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all duration-200 bg-gray-50 focus:bg-white text-gray-800 placeholder-gray-500" 
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
                               class="w-full pl-10 pr-12 py-3 rounded-xl border-2 border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all duration-200 bg-gray-50 focus:bg-white text-gray-800 placeholder-gray-500" 
                               required>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password')" id="toggleIcon"></i>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" 
                               {{ old('remember') ? 'checked' : '' }}
                               class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded transition-colors duration-200">
                        <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                            Remember me
                        </label>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-amber-600 to-yellow-600 text-white rounded-xl hover:from-amber-700 hover:to-yellow-700 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center space-x-2">
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
