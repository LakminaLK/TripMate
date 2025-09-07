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
    <!-- Page Header -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Hotel Profile</h1>
        <p class="text-gray-600 mt-1">Manage your hotel information and settings</p>
    </div>

    <!-- Profile Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Hotel Information -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Hotel Information</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Hotel Name</label>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <span class="text-gray-800">{{ $hotel->name }}</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Email Address</label>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <span class="text-gray-800">{{ $hotel->email }}</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Location</label>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <span class="text-gray-800">{{ $hotel->location->name ?? 'Not specified' }}</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Username</label>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <span class="text-gray-800">{{ $hotel->username }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Username Update -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Change Username</h2>
            
            <form action="{{ route('hotel.profile.username.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1" for="username">New Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username', $hotel->username) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                        required
                        autocomplete="new-username"
                        autocapitalize="off"
                        spellcheck="false"
                    >
                    <p class="text-xs text-gray-500 mt-1">Username must be unique and case-sensitive</p>
                </div>
                
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors">
                    Update Username
                </button>
            </form>
        </div>

        <!-- Password Update -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h2>
            
            <form action="{{ route('hotel.profile.password.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1" for="current_password">Current Password</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                        required
                        autocomplete="current-password"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1" for="password">New Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                        required
                        autocomplete="new-password"
                    >
                    <p class="text-xs text-gray-500 mt-1">Must contain uppercase and lowercase letters</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1" for="password_confirmation">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                        required
                        autocomplete="new-password"
                    >
                </div>
                
                <div class="md:col-span-3">
                    <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
