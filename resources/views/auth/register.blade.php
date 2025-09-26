<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" />
    <style>
        .iti { width: 100%; }
        .iti__country-list { max-height: 200px; overflow-y: auto; z-index: 9999; }
        
        /* Custom validation styles */
        .validation-check {
            transition: all 0.2s ease-in-out;
        }
        
        .validation-check svg {
            transition: transform 0.2s ease-in-out;
        }
        
        .validation-check.valid svg {
            transform: scale(1.1);
        }
        
        /* Input transition effects */
        input {
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        /* Loading spinner */
        .spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-white font-sans h-screen">

<div class="flex h-screen w-full overflow-hidden">

    <!-- Left: Fullscreen Image -->
    <div class="w-1/2 hidden lg:block">
        <img src="{{ asset('images/signup.jpg') }}" class="h-full w-full object-cover" alt="Signup Image">
    </div>

    <!-- Right: Signup Form -->
    <div class="w-full lg:w-1/2 flex justify-center items-center" x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)">
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 translate-x-10"
        x-transition:enter-end="opacity-100 translate-x-0"
        class="w-full max-w-md px-6"
    >
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Create an Account</h2>
        <p class="text-center text-sm text-gray-500 mb-6">Join us and explore breathtaking destinations</p>

            <form method="POST" action="{{ route('register') }}" autocomplete="off">
                @csrf
                
                <!-- Hidden dummy fields to confuse password managers -->
                <div style="display:none;">
                    <input type="text" name="dummy_username" autocomplete="username" tabindex="-1">
                    <input type="password" name="dummy_password" autocomplete="current-password" tabindex="-1">
                </div>

                <div class="mb-4">
                    <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           autocomplete="off" required />
                </div>

                <!-- Email Field with Real-time Validation -->
                <div class="mb-4" x-data="emailValidator()">
                    <input type="email" name="email" placeholder="Email" 
                           x-model="email"
                           @input="debounceEmailCheck()"
                           :class="emailFieldClass"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200" 
                           autocomplete="off" 
                           required />
                    <div x-show="emailError" x-text="emailError" class="text-red-500 text-sm mt-1"></div>
                    <div x-show="emailLoading" class="text-gray-500 text-sm mt-1 flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Checking email...
                    </div>
                </div>

                <!-- Mobile Field with Real-time Validation -->
                <div class="mb-4" x-data="mobileValidator()">
                    <input id="phone" type="tel"
                           @input="validateMobile()"
                           :class="mobileFieldClass"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200" 
                           autocomplete="off"
                           required>
                    <input type="hidden" name="mobile" id="full_phone" />
                    <div x-show="mobileError" x-text="mobileError" class="text-red-500 text-sm mt-1"></div>
                    <div x-show="mobileLoading" class="text-gray-500 text-sm mt-1 flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Checking mobile...
                    </div>
                </div>

                <input type="hidden" name="location" id="location" />

                <!-- Password Field with Real-time Validation -->
                <div x-data="passwordValidator()" class="mb-4">
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" placeholder="Password"
                               x-model="password"
                               @input="validatePassword()"
                               :class="passwordFieldClass"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200" 
                               autocomplete="new-password"
                               required />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-3 flex items-center">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>

                    <!-- Password Validation Checklist -->
                    <div class="mt-3 space-y-2 text-sm">
                        <div class="flex items-center space-x-2">
                            <div :class="checks.length ? 'text-green-500' : 'text-gray-400'" class="w-5 h-5 flex items-center justify-center">
                                <svg x-show="checks.length" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <svg x-show="!checks.length" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                            </div>
                            <span :class="checks.length ? 'text-green-600' : 'text-gray-600'">At least 8 characters</span>
                        </div>

                        <div class="flex items-center space-x-2">
                            <div :class="checks.uppercase ? 'text-green-500' : 'text-gray-400'" class="w-5 h-5 flex items-center justify-center">
                                <svg x-show="checks.uppercase" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <svg x-show="!checks.uppercase" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                            </div>
                            <span :class="checks.uppercase ? 'text-green-600' : 'text-gray-600'">At least 1 uppercase letter</span>
                        </div>

                        <div class="flex items-center space-x-2">
                            <div :class="checks.lowercase ? 'text-green-500' : 'text-gray-400'" class="w-5 h-5 flex items-center justify-center">
                                <svg x-show="checks.lowercase" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <svg x-show="!checks.lowercase" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                            </div>
                            <span :class="checks.lowercase ? 'text-green-600' : 'text-gray-600'">At least 1 lowercase letter</span>
                        </div>

                        <div class="flex items-center space-x-2">
                            <div :class="checks.number ? 'text-green-500' : 'text-gray-400'" class="w-5 h-5 flex items-center justify-center">
                                <svg x-show="checks.number" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <svg x-show="!checks.number" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                            </div>
                            <span :class="checks.number ? 'text-green-600' : 'text-gray-600'">At least 1 number</span>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div x-data="confirmPasswordValidator()" class="mb-6">
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" placeholder="Confirm Password"
                               x-model="confirmPassword"
                               @input="validateConfirmPassword()"
                               :class="confirmPasswordFieldClass"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200" 
                               autocomplete="new-password"
                               required />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-3 flex items-center">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <div x-show="confirmPasswordError" x-text="confirmPasswordError" class="text-red-500 text-sm mt-1"></div>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                    Sign Up
                </button>
            </form>

            <!-- Prevent autofill script -->
            <script>
                // Additional measures to prevent browser password saving
                document.addEventListener('DOMContentLoaded', function() {
                    // Disable autocomplete more aggressively
                    const form = document.querySelector('form[action*="register"]');
                    if (form) {
                        form.setAttribute('autocomplete', 'off');
                        
                        // Find all input fields and set autocomplete off
                        const inputs = form.querySelectorAll('input');
                        inputs.forEach(input => {
                            if (input.type === 'password') {
                                input.setAttribute('autocomplete', 'new-password');
                            } else if (input.name !== '_token') {
                                input.setAttribute('autocomplete', 'off');
                            }
                            
                            // Prevent browser from saving values
                            input.setAttribute('data-form-type', 'other');
                        });
                    }
                });
            </script>

            <p class="mt-5 text-center text-sm">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
            </p>
        </div>
    </div>
</div>

<!-- ✅ OTP Modal -->
<!-- Debug: showOtpModal = {{ session('showOtpModal') ? 'true' : 'false' }} -->
<div x-data="{ show: @json(session('showOtpModal', false)) }">>>
    <div x-show="show"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4 text-center">Enter OTP</h2>
            <p class="text-center text-sm text-gray-600 mb-4">
                We've sent a 6-digit verification code to your email
            </p>
            
            <form method="POST" action="{{ route('verify.otp') }}" 
                  x-data="{ submitting: false }"
                  @submit="submitting = true"
                  x-init="
                    // Reset submitting state if there are errors or success messages
                    @if(session('otp_error') || session('success') || $errors->any())
                        submitting = false;
                    @endif
                  ">
                @csrf
                <input type="hidden" name="email" value="{{ session('pending_email') }}">
                <div class="relative mb-3">
                    <input type="text" name="otp" placeholder="Enter 6-digit OTP" maxlength="6"
                           class="w-full border p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 text-center text-lg font-mono tracking-widest" 
                           required
                           x-data="{ value: '', isValid: false }"
                           x-model="value"
                           x-on:input="
                             value = value.replace(/[^0-9]/g, '');
                             isValid = /^\d{6}$/.test(value);
                             
                             // Hide client error when user starts typing
                             const errorDiv = $el.closest('form').querySelector('.client-error');
                             if (errorDiv && value.length > 0) {
                                 errorDiv.style.display = 'none';
                             }
                           "
                           x-bind:class="{ 
                             'border-green-500': value.length === 6 && isValid,
                             'border-red-500': value.length === 6 && !isValid,
                             'border-gray-300': value.length < 6
                           }"
                           x-init="
                             // Clear input if there's an error
                             @if(session('otp_error'))
                               setTimeout(() => { $el.focus(); }, 100);
                             @endif
                           "
                           autocomplete="off">
                    <div class="absolute inset-y-0 right-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Client-side error display -->
                <div class="client-error bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-3 text-sm" style="display: none;">
                    <div class="flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="error-text"></span>
                    </div>
                </div>
                
                @if(session('otp_error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-3 text-sm">
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ session('otp_error') }}
                        </div>
                    </div>
                @endif
                
                @if(session('success') && session('showOtpModal'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-3 text-sm">
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
                
                @if($errors->any() && session('pending_email'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-3 text-sm">
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            @foreach ($errors->all() as $error)
                                {{ $error }}@if(!$loop->last)<br>@endif
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <button type="submit"
                        class="w-full bg-green-600 text-white py-3 rounded hover:bg-green-700 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                        x-bind:disabled="submitting">
                    <span x-show="!submitting">Verify OTP</span>
                    <span x-show="submitting" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 818-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Verifying...
                    </span>
                </button>
            </form>
            
            <div class="mt-4 text-center">
                <div x-data="{ 
                    canResend: true, 
                    countdown: 0,
                    startCountdown() {
                        this.canResend = false;
                        this.countdown = 60;
                        const timer = setInterval(() => {
                            this.countdown--;
                            if (this.countdown <= 0) {
                                this.canResend = true;
                                clearInterval(timer);
                            }
                        }, 1000);
                    }
                }" x-init="
                    @if(session('otp_last_resend'))
                        let elapsed = Math.floor(Date.now() / 1000) - {{ session('otp_last_resend') }};
                        if (elapsed < 60) {
                            canResend = false;
                            countdown = 60 - elapsed;
                            const timer = setInterval(() => {
                                countdown--;
                                if (countdown <= 0) {
                                    canResend = true;
                                    clearInterval(timer);
                                }
                            }, 1000);
                        }
                    @endif
                ">
                    <p class="text-sm text-gray-500 mb-2">
                        Didn't receive the code?
                    </p>
                    
                    <form method="POST" action="{{ route('resend.otp') }}" class="inline" x-show="canResend">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('pending_email') }}">
                        <button type="submit" 
                                class="text-blue-600 hover:underline font-medium text-sm"
                                @click="startCountdown()">
                            Resend OTP
                        </button>
                    </form>
                    
                    <div x-show="!canResend" class="text-sm text-gray-500">
                        Resend available in <span x-text="countdown"></span>s
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Toast Notification -->
<div 
    x-data="{ show: {{ session('success') || session('error') || $errors->any() ? 'true' : 'false' }} }"
    x-init="setTimeout(() => show = false, 4000)"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    class="fixed top-6 right-6 z-50"
>
    @if (session('success'))
        <div class="bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-between gap-4">
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-white text-lg font-bold">&times;</button>
        </div>
    @elseif (session('error'))
        <div class="bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-between gap-4">
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="text-white text-lg font-bold">&times;</button>
        </div>
    @elseif ($errors->any())
        <div class="bg-yellow-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-start justify-between gap-4">
            <ul class="list-disc ml-4 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button @click="show = false" class="text-white text-lg font-bold mt-1">&times;</button>
        </div>
    @endif
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>

<script>
    fetch('https://ipapi.co/json/')
        .then(res => res.json())
        .then(data => {
            document.getElementById('location').value = data.country_name || 'Unknown';
        })
        .catch(() => {
            document.getElementById('location').value = 'Unknown';
        });

    const phoneInput = document.querySelector("#phone");
    const fullPhoneInput = document.querySelector("#full_phone");
    const iti = window.intlTelInput(phoneInput, {
        separateDialCode: true,
        preferredCountries: ["us", "gb", "lk", "in", "au"],
        initialCountry: "auto",
        geoIpLookup: function(callback) {
            fetch('https://ipapi.co/json')
                .then(res => res.json())
                .then(data => callback(data.country_code))
                .catch(() => callback("us"));
        },
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
    });

    // Prevent non-numeric input in phone field
    phoneInput.addEventListener('input', function(e) {
        // Allow only numbers, spaces, hyphens, and parentheses
        const cleaned = e.target.value.replace(/[^\d\s\-\(\)\+]/g, '');
        if (e.target.value !== cleaned) {
            e.target.value = cleaned;
        }
    });

    phoneInput.addEventListener('keypress', function(e) {
        // Allow: backspace, delete, tab, escape, enter, numbers, and special phone chars
        if ([46, 8, 9, 27, 13, 43].indexOf(e.keyCode) !== -1 ||
            // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    document.querySelector("form").addEventListener("submit", function () {
        fullPhoneInput.value = iti.getNumber();
    });

    // Global ITI instance for mobile validation
    window.itiInstance = iti;

    // Real-time validation functions
    function emailValidator() {
        return {
            email: '{{ old("email") }}',
            emailError: '',
            emailLoading: false,
            emailTimeout: null,

            get emailFieldClass() {
                if (!this.email) return 'border-gray-300 focus:ring-blue-500';
                if (this.emailError) return 'border-red-500 focus:ring-red-500';
                if (this.email && !this.emailError && !this.emailLoading) return 'border-green-500 focus:ring-green-500';
                return 'border-gray-300 focus:ring-blue-500';
            },

            debounceEmailCheck() {
                clearTimeout(this.emailTimeout);
                this.emailTimeout = setTimeout(() => {
                    this.checkEmail();
                }, 500);
            },

            async checkEmail() {
                if (!this.email) {
                    this.emailError = '';
                    return;
                }

                // Basic email format validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.email)) {
                    this.emailError = 'Please enter a valid email address';
                    return;
                }

                this.emailLoading = true;
                this.emailError = '';

                try {
                    const response = await fetch('/api/check-email', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ email: this.email })
                    });

                    const data = await response.json();
                    
                    if (data.exists) {
                        this.emailError = 'This email is already registered';
                    } else {
                        this.emailError = '';
                    }
                } catch (error) {
                    console.error('Email check error:', error);
                } finally {
                    this.emailLoading = false;
                }
            }
        }
    }

    function mobileValidator() {
        return {
            mobileError: '',
            mobileLoading: false,
            mobileTimeout: null,

            get mobileFieldClass() {
                const phoneInput = document.getElementById('phone');
                if (!phoneInput || !phoneInput.value) return 'border-gray-300 focus:ring-blue-500';
                if (this.mobileError) return 'border-red-500 focus:ring-red-500';
                if (phoneInput.value && !this.mobileError && !this.mobileLoading) return 'border-green-500 focus:ring-green-500';
                return 'border-gray-300 focus:ring-blue-500';
            },

            validateMobile() {
                clearTimeout(this.mobileTimeout);
                this.mobileTimeout = setTimeout(() => {
                    this.checkMobile();
                }, 800);
            },

            async checkMobile() {
                const phoneInput = document.getElementById('phone');
                if (!phoneInput.value) {
                    this.mobileError = '';
                    return;
                }

                // Validate only numbers
                const numbersOnly = phoneInput.value.replace(/[^0-9]/g, '');
                if (phoneInput.value !== numbersOnly) {
                    phoneInput.value = numbersOnly;
                }

                if (!window.itiInstance.isValidNumber()) {
                    this.mobileError = 'Please enter a valid mobile number';
                    return;
                }

                this.mobileLoading = true;
                this.mobileError = '';

                try {
                    const fullNumber = window.itiInstance.getNumber();
                    const response = await fetch('/api/check-mobile', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ mobile: fullNumber })
                    });

                    const data = await response.json();
                    
                    if (data.exists) {
                        this.mobileError = 'This mobile number is already registered';
                    } else {
                        this.mobileError = '';
                    }
                } catch (error) {
                    console.error('Mobile check error:', error);
                } finally {
                    this.mobileLoading = false;
                }
            }
        }
    }

    function passwordValidator() {
        return {
            password: '',
            checks: {
                length: false,
                uppercase: false,
                lowercase: false,
                number: false
            },

            get passwordFieldClass() {
                if (!this.password) return 'border-gray-300 focus:ring-blue-500';
                const allValid = this.checks.length && this.checks.uppercase && this.checks.lowercase && this.checks.number;
                if (allValid) return 'border-green-500 focus:ring-green-500';
                return 'border-red-500 focus:ring-red-500';
            },

            validatePassword() {
                this.checks.length = this.password.length >= 8;
                this.checks.uppercase = /[A-Z]/.test(this.password);
                this.checks.lowercase = /[a-z]/.test(this.password);
                this.checks.number = /[0-9]/.test(this.password);
                
                // Update global password for confirm password validation
                window.currentPassword = this.password;
                
                // Trigger confirm password validation if it exists
                if (window.confirmPasswordValidator) {
                    window.confirmPasswordValidator.validateConfirmPassword();
                }
            }
        }
    }

    function confirmPasswordValidator() {
        return {
            confirmPassword: '',
            confirmPasswordError: '',

            init() {
                // Store reference globally for password validator
                window.confirmPasswordValidator = this;
            },

            get confirmPasswordFieldClass() {
                if (!this.confirmPassword) return 'border-gray-300 focus:ring-blue-500';
                if (this.confirmPasswordError) return 'border-red-500 focus:ring-red-500';
                if (this.confirmPassword && !this.confirmPasswordError) return 'border-green-500 focus:ring-green-500';
                return 'border-gray-300 focus:ring-blue-500';
            },

            validateConfirmPassword() {
                if (!this.confirmPassword) {
                    this.confirmPasswordError = '';
                    return;
                }

                const mainPassword = window.currentPassword || '';
                if (this.confirmPassword !== mainPassword) {
                    this.confirmPasswordError = 'Passwords do not match';
                } else {
                    this.confirmPasswordError = '';
                }
            }
        }
    }
</script>

</body>
</html>
