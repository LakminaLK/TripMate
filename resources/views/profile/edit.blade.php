<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Update Card -->
            <div class="p-6 bg-white shadow rounded-lg">
                <header>
                    <h3 class="text-lg font-medium text-gray-900">
                        Profile Information
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Update your name. Your email and mobile number are read-only.
                    </p>
                </header>

                <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            :value="old('name', auth()->user()->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- Email (Read-only) -->
                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" type="email" class="mt-1 block w-full bg-gray-100"
                            value="{{ auth()->user()->email }}" disabled />
                    </div>

                    <!-- Mobile (Read-only) -->
                    <div>
                        <x-input-label for="mobile" value="Mobile" />
                        <x-text-input id="mobile" type="text" class="mt-1 block w-full bg-gray-100"
                            value="{{ auth()->user()->mobile }}" disabled />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Save</x-primary-button>
                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-gray-600">Saved.</p>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Password Update Card -->
            @include('profile.partials.update-password-form')
        </div>
    </div>
</x-app-layout>
