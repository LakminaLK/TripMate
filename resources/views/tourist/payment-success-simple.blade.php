<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - TripMate</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">
                    Trip<span class="text-blue-600">Mate</span>
                </h1>
                
                <a href="{{ route('tourist.home') }}" 
                   class="text-blue-600 hover:text-blue-700 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Go to Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-12">
        
        <!-- Success Message -->
        <div class="text-center mb-12">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>
            
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Payment Successful!</h1>
            <p class="text-xl text-gray-600 mb-2">Your booking has been confirmed</p>
            <p class="text-gray-500">You will receive a confirmation email shortly.</p>
        </div>

        <!-- Action Buttons -->
        <div class="text-center space-y-4 mb-8">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('tourist.bookings.view') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-calendar-check mr-2"></i>
                    View My Bookings
                </a>
                
                <a href="{{ route('tourist.home') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-8 py-3 rounded-lg font-semibold transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-home mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Information -->
        <div class="bg-blue-50 rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                Important Information
            </h3>
            <ul class="text-blue-800 space-y-2 text-sm">
                <li class="flex items-start">
                    <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                    Please arrive at the hotel on your check-in date with a valid ID
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                    A confirmation email has been sent to your registered email address
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                    For any changes or cancellations, please contact the hotel directly
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                    Keep this confirmation for your records
                </li>
            </ul>
        </div>
    </main>
</body>
</html>
