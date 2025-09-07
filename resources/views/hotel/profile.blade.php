@extends('hotel.layouts.app')

@section('title', 'Hotel Profile')

@push('flash-scripts')
<!-- Expose flash for toasts -->
<script>
  window.FLASH = {
    success: @json(session('success')),
    errors:  @json($errors->all()),
  };
</script>
@endpush

@section('content')
    <!-- Page Header with Professional Styling -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-8 rounded-xl shadow-sm border border-green-100 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Hotel Profile</h1>
                <p class="text-gray-600 text-lg">Manage your hotel information and account settings</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-green-200">
                    <div class="text-sm text-gray-500">Hotel ID</div>
                    <div class="text-lg font-semibold text-green-600">#{{ $hotel->id }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Left Column - Hotel Information Card -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-green-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Hotel Information
                    </h2>
                </div>
                
                <!-- Card Content -->
                <div class="p-6 space-y-6">
                    <div class="text-center pb-6 border-b border-gray-200">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $hotel->name }}</h3>
                        <p class="text-gray-500 mt-1">Hotel Account</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Email Address</label>
                                <p class="text-gray-900 font-medium">{{ $hotel->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Location</label>
                                <p class="text-gray-900 font-medium">{{ $hotel->location->name ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Username</label>
                                <p class="text-gray-900 font-medium">{{ $hotel->username }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Member Since</label>
                                <p class="text-gray-900 font-medium">{{ $hotel->created_at->format('F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Settings Forms -->
        <div class="xl:col-span-2 space-y-8">
            
            <!-- Username Update Section -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Update Username
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Change your login username for this account</p>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('hotel.profile.username.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="current_username">Current Username</label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        id="current_username"
                                        value="{{ $hotel->username }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                                        readonly
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="username">New Username</label>
                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    value="{{ old('username') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                    required
                                    autocomplete="new-username"
                                    autocapitalize="off"
                                    spellcheck="false"
                                    placeholder="Enter new username"
                                >
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-800">Important Information</h4>
                                    <p class="text-sm text-blue-700 mt-1">Username must be unique and case-sensitive. You'll need to use the new username for future logins.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Update Username
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Update Section -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Change Password
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Ensure your account stays secure with a strong password</p>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('hotel.profile.password.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="current_password">Current Password</label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        id="current_password"
                                        name="current_password"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Current password"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="password">New Password</label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                        required
                                        autocomplete="new-password"
                                        placeholder="New password"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="password_confirmation">Confirm Password</label>
                                <div class="relative">
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Confirm password"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-amber-800">Password Requirements</h4>
                                    <p class="text-sm text-amber-700 mt-1">Password must contain both uppercase and lowercase letters for security.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
