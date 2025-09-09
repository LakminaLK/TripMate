<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - TripMate</title>
    
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
    <main class="max-w-2xl mx-auto px-6 py-12 text-center">
        
        <!-- Error Icon -->
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-times text-4xl text-red-600"></i>
        </div>
        
        <!-- Error Message -->
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Payment Failed</h1>
        <p class="text-xl text-gray-600 mb-8">
            We're sorry, but your payment could not be processed at this time.
        </p>
        
        <!-- Error Details -->
        <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8 text-left">
            <h2 class="text-lg font-semibold text-red-900 mb-3 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                What happened?
            </h2>
            <ul class="text-red-800 space-y-2 text-sm">
                <li class="flex items-start">
                    <i class="fas fa-circle text-red-600 mr-2 mt-1.5 text-xs"></i>
                    Your payment information may be incorrect
                </li>
                <li class="flex items-start">
                    <i class="fas fa-circle text-red-600 mr-2 mt-1.5 text-xs"></i>
                    Your card may have insufficient funds
                </li>
                <li class="flex items-start">
                    <i class="fas fa-circle text-red-600 mr-2 mt-1.5 text-xs"></i>
                    There may be a temporary issue with our payment system
                </li>
                <li class="flex items-start">
                    <i class="fas fa-circle text-red-600 mr-2 mt-1.5 text-xs"></i>
                    Your bank may have declined the transaction
                </li>
            </ul>
        </div>
        
        <!-- Action Buttons -->
        <div class="space-y-4">
            <button onclick="history.back()" 
                    class="w-full bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>
                Try Again
            </button>
            
            <a href="{{ route('tourist.home') }}" 
               class="block w-full bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition-colors font-semibold">
                <i class="fas fa-home mr-2"></i>
                Go to Dashboard
            </a>
        </div>
        
        <!-- Help Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-8 text-left">
            <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                <i class="fas fa-question-circle mr-2"></i>
                Need Help?
            </h3>
            <div class="text-blue-800 space-y-2 text-sm">
                <p>If you continue to experience issues, please:</p>
                <ul class="ml-4 space-y-1">
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                        Check your card details and try again
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                        Contact your bank to verify the transaction
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                        Try using a different payment method
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                        Contact our support team for assistance
                    </li>
                </ul>
            </div>
        </div>
    </main>
</body>
</html>
