<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        #toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 50;
        }
        .toast-success {
            background-color: white;
            border-left: 4px solid #10B981;
            color: #374151;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 6px;
            padding: 1rem;
            display: flex;
            align-items: center;
            animation: slideIn 0.3s ease-out;
            margin-bottom: 0.5rem;
        }
        .toast-success .icon {
            background-color: #10B981;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }
        @keyframes fadeOut {
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 to-teal-100 font-sans min-h-screen">
    <!-- Header -->
    <div class="bg-white py-8 text-center shadow-md mb-8">
        <div class="flex items-center justify-center space-x-3">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">TripMate</h1>
        </div>
    </div>

    <div id="toast-container">
        @if(session('toast'))
        <div id="success-toast" class="toast-success">
            <div class="icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <span class="text-sm font-medium">{{ session('toast.message') }}</span>
        </div>
        @endif
    </div>

    <!-- Reset Password Form -->
    <div class="flex justify-center items-center min-h-[65vh] px-4 pb-12">
        <div class="bg-white p-10 rounded-2xl w-full max-w-md shadow-2xl border border-gray-100"
    >
        <!-- Header -->
        <div class="text-center mb-8">
            <img src="{{ asset('/images/tm1.png') }}" alt="TripMate Logo" class="h-12 mx-auto mb-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Reset Hotel Password</h2>
            <p class="text-center text-sm text-gray-500">Enter your registered hotel email and we'll send you instructions to reset your password</p>
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

        <!-- Form -->
        <form method="POST" action="{{ route('hotel.password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Hotel Email Address</label>
                <input type="email" name="email" id="email" 
                       class="mt-1 w-full px-4 py-3 border rounded-md focus:outline-none focus:ring focus:border-green-500"
                       placeholder="hotel@example.com"
                       required autofocus>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                Send Reset Link
            </button>

            <div class="text-center mt-4">
                <a href="{{ route('hotel.login') }}" class="text-sm text-green-600 hover:underline">
                    Back to Login
                </a>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('success-toast');
            if (toast) {
                setTimeout(() => {
                    toast.classList.add('fade-out');
                    setTimeout(() => {
                        const container = document.getElementById('toast-container');
                        if (container && container.contains(toast)) {
                            container.removeChild(toast);
                        }
                    }, 300);
                }, 3000);
            }
        });
    </script>
</body>
</html>
