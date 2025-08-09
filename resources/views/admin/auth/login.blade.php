<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - TripMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
     <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-300 font-sans">
    <!-- Header -->
    <div class="bg-white py-6 text-center shadow">
        <h1 class="text-4xl font-bold text-gray-900">TripMate</h1>
    </div>

    <!-- Login Form -->
    <div class="flex justify-center items-center min-h-[70vh]">
        <div class="bg-white p-8 rounded-lg w-full max-w-md shadow-lg">
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Admin Login</h2>

            <!-- Error Message -->
            @if ($errors->has('login'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                    {{ $errors->first('login') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any() && !$errors->has('login'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="mb-4">
                    <input type="text" name="username" placeholder="Username"
                           value="{{ old('username') }}"
                           class="w-full px-4 py-3 rounded border border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-600" required>
                </div>

                <div class="mb-4">
                    <input type="password" name="password" placeholder="Password"
                           class="w-full px-4 py-3 rounded border border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-600" required>
                </div>

                <div class="flex justify-between items-center mb-4 text-sm text-gray-700">
                    <a href="#" class="hover:underline">Forgot Password?</a>
                </div>

                <button type="submit"
                        class="w-full py-3 bg-black text-white rounded hover:bg-gray-800 font-semibold">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>
