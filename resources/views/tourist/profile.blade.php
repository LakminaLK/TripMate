<!DOCTYPE html>
<html lang="en">
<x-tourist.head title="Profile | TripMate" />
</head>
<body class="bg-white text-gray-800 font-sans">

@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
    $imageUrl = $tourist->profile_image ? asset('storage/' . $tourist->profile_image) : null;
@endphp

<x-tourist.header />

<div class="py-12 bg-gray-100 min-h-screen mt-[72px]">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- ✅ Profile Info -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">Profile Information</h2>
                <p class="text-sm text-gray-500 mb-4">Update your name and profile picture.</p>

                <form method="POST" action="{{ route('tourist.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <!-- ✅ Profile Image -->
                    <div class="relative w-20 h-20 mb-4">
                        <label for="profile_image"
                            class="cursor-pointer group block w-20 h-20 rounded-full border overflow-hidden shadow-lg">
                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}?{{ now()->timestamp }}" alt="Profile Image"
                                     class="object-cover w-full h-full transition-transform duration-200 group-hover:scale-105" />
                            @else
                                <div class="bg-blue-600 text-white flex items-center justify-center w-full h-full text-2xl font-bold">
                                    {{ strtoupper(substr($tourist->name, 0, 1)) }}
                                </div>
                            @endif
                        </label>
                        <input type="file" name="profile_image" id="profile_image" accept="image/*"
                               class="hidden" x-ref="file" @click="$refs.file.value = ''" />
                    </div>

                    <!-- ✅ Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input id="name" name="name" type="text"
                               class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               value="{{ old('name', $tourist->name) }}" required />
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ✅ Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="text" class="mt-1 p-3 w-full bg-gray-100 rounded-lg text-gray-700" value="{{ $tourist->email }}" disabled>
                    </div>

                    <!-- ✅ Mobile -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mobile</label>
                        <input type="text" class="mt-1 p-3 w-full bg-gray-100 rounded-lg text-gray-700" value="{{ $tourist->mobile }}" disabled>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                            Save Changes
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-green-600 mt-2">Profile updated successfully.</p>
                        @endif
                    </div>
                </form>

                <!-- ✅ Remove Image Button -->
                @if ($tourist->profile_image)
                    <form method="POST" action="{{ route('tourist.profile.removeImage') }}"
                          onsubmit="return confirm('Are you sure you want to remove your profile image?')"
                          class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                            Remove Profile Image
                        </button>
                        @if (session('status') === 'profile-image-removed')
                            <p class="text-sm text-green-600 mt-2">Profile image removed successfully.</p>
                        @endif
                    </form>
                @endif
            </div>

            <!-- ✅ Password Update -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">Update Password</h2>
                <p class="text-sm text-gray-500 mb-4">Ensure your password is strong and unique.</p>

                <form method="POST" action="{{ route('tourist.password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input id="current_password" name="current_password" type="password"
                               class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input id="password" name="password" type="password"
                               class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                               class="mt-1 p-3 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                            Update Password
                        </button>

                        @if (session('status') === 'password-updated')
                            <p class="text-sm text-green-600 mt-2">Password updated successfully.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<x-tourist.footer />

</body>
</html>