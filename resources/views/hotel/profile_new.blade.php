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
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Hotel Profile</h1>
            <p class="text-gray-600">Manage your hotel information and settings</p>
        </div>
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <i class="fas fa-building"></i>
            <span>{{ $hotel->name }}</span>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Hotel Information -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Hotel Information</h2>
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building text-white text-lg"></i>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hotel Name</label>
                    <div class="bg-gray-50 p-3 rounded-lg border">
                        <span class="text-gray-800">{{ $hotel->name }}</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="bg-gray-50 p-3 rounded-lg border">
                        <span class="text-gray-800">{{ $hotel->email }}</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <div class="bg-gray-50 p-3 rounded-lg border">
                        <span class="text-gray-800">{{ $hotel->location->name ?? 'Not specified' }}</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="bg-gray-50 p-3 rounded-lg border">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $hotel->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $hotel->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Username Management -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Username Settings</h2>
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-white text-lg"></i>
                </div>
            </div>
            
            <form action="{{ route('hotel.profile.username.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Username</label>
                    <div class="bg-gray-50 p-3 rounded-lg border mb-3">
                        <span class="text-gray-800">{{ $hotel->username }}</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="username">New Username</label>
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
                    <p class="text-xs text-gray-500 mt-1">Must be unique among hotels.</p>
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white px-4 py-3 rounded-lg hover:from-green-700 hover:to-teal-700 transition-all duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-save"></i>
                    <span>Update Username</span>
                </button>
            </form>
        </div>

        <!-- Password Management -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Password Settings</h2>
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-lock text-white text-lg"></i>
                </div>
            </div>
            
            <form action="{{ route('hotel.profile.password.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="current_password">Current Password</label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="password">New Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                        required
                        autocomplete="new-password"
                    >
                    <p class="text-xs text-gray-500 mt-1">Must contain uppercase and lowercase letters.</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="password_confirmation">Confirm Password</label>
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
                    <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-200 flex items-center space-x-2">
                        <i class="fas fa-key"></i>
                        <span>Update Password</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Security Information -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Security Information</h2>
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-shield-alt text-white text-lg"></i>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Account Active</p>
                            <p class="text-sm text-gray-600">Your account is in good standing</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-shield text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Case Sensitive Login</p>
                            <p class="text-sm text-gray-600">Username must match exactly</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-key text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Strong Password</p>
                            <p class="text-sm text-gray-600">Requires upper & lowercase</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
