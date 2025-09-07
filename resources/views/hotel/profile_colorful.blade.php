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
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded border shadow mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Hotel Profile</h1>
            <p class="text-gray-600">Manage your hotel information and settings</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- LEFT: stacked cards -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Hotel Details -->
                <section class="bg-white rounded border p-6 shadow">
                    <h3 class="text-lg font-semibold mb-4">Hotel Details</h3>
                    <dl class="text-sm divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3">
                            <dt class="text-gray-600">Hotel Name</dt>
                            <dd class="font-medium">{{ $hotel->name }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <dt class="text-gray-600">Email Address</dt>
                            <dd class="font-medium">{{ $hotel->email }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <dt class="text-gray-600">Location</dt>
                            <dd class="font-medium">{{ $hotel->location->name ?? 'Not specified' }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <dt class="text-gray-600">Status</dt>
                            <dd class="font-medium">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $hotel->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $hotel->status }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <dt class="text-gray-600">Current Username</dt>
                            <dd class="font-medium">{{ $hotel->username }}</dd>
                        </div>
                    </dl>
                </section>

                <!-- Change Username -->
                <section class="bg-white rounded border p-6 shadow">
                    <h3 class="text-lg font-semibold mb-4">Change Username</h3>

                    @if ($errors->has('username'))
                        <div class="mb-3 text-sm bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded">
                            {{ $errors->first('username') }}
                        </div>
                    @endif

                    <form action="{{ route('hotel.profile.username.update') }}" method="POST" class="space-y-3" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm text-gray-700 mb-1" for="username">New Username</label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username', $hotel->username) }}"
                                class="w-full border border-gray-300 rounded px-3 py-2"
                                required
                                autocomplete="new-username"
                                autocapitalize="off"
                                spellcheck="false"
                            >
                            <p class="text-xs text-gray-500 mt-1">Must be unique among hotels.</p>
                        </div>
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded hover:bg-black transition">
                            Update Username
                        </button>
                    </form>
                </section>
            </div>

            <!-- RIGHT: Change Password -->
            <section class="bg-white rounded border p-6 shadow lg:col-span-1">
                <h3 class="text-lg font-semibold mb-4">Change Password</h3>

                <form action="{{ route('hotel.profile.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm text-gray-700 mb-1" for="current_password">Current Password</label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password"
                                   class="w-full border border-gray-300 rounded px-3 py-2 pr-10" required>
                            <button type="button" data-toggle="#current_password"
                                    class="toggle-eye absolute inset-y-0 right-0 px-3 text-gray-500 focus:outline-none">
                                <svg class="w-5 h-5 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1" for="password">New Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                   class="w-full border border-gray-300 rounded px-3 py-2 pr-10" required>
                            <button type="button" data-toggle="#password"
                                    class="toggle-eye absolute inset-y-0 right-0 px-3 text-gray-500 focus:outline-none">
                                <svg class="w-5 h-5 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Must contain uppercase and lowercase letters.</p>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1" for="password_confirmation">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full border border-gray-300 rounded px-3 py-2 pr-10" required>
                            <button type="button" data-toggle="#password_confirmation"
                                    class="toggle-eye absolute inset-y-0 right-0 px-3 text-gray-500 focus:outline-none">
                                <svg class="w-5 h-5 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded hover:bg-black transition">
                        Update Password
                    </button>
                </form>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Eye toggles
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.toggle-eye').forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = document.querySelector(btn.dataset.toggle);
                    if (target) target.type = target.type === 'password' ? 'text' : 'password';
                });
            });
        });

        // Chrome/Edge: ensure no input focused on initial paint or bfcache restore
        function defocus() {
            if (!document.body.hasAttribute('tabindex')) {
                document.body.setAttribute('tabindex', '-1');
            }
            document.body.focus({ preventScroll: true });
        }
        window.addEventListener('DOMContentLoaded', defocus);
        window.addEventListener('load', defocus);
        window.addEventListener('pageshow', (e) => { if (e.persisted) defocus(); });
    </script>
@endpush
