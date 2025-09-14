<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reset Password - {{ config('app.name', 'AgriConnect') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .agriconnect-primary {
            background: linear-gradient(135deg, #10b981, #0d9488);
        }

        .agriconnect-secondary {
            background: linear-gradient(135deg, #059669, #0f766e);
        }

        .text-agriconnect-primary {
            color: #10b981;
        }

        .bg-agriconnect-pattern {
            background-image:
                radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(5, 150, 105, 0.1) 0%, transparent 50%);
        }

        .password-strength {
            transition: all 0.2s ease;
        }
    </style>
</head>

<body class="font-poppins antialiased bg-slate-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Hero -->
        <div class="hidden lg:flex lg:w-1/2 agriconnect-primary relative overflow-hidden">
            <div class="absolute inset-0 bg-agriconnect-pattern"></div>
            <div class="relative z-10 flex flex-col justify-center p-12 text-white">
                <!-- Logo -->
                <div class="flex items-center mb-8">
                    <img src="{{ asset('images/logo.jpg') }}" alt="AgriConnect"
                        class="w-12 h-12 object-contain mr-3 rounded-2xl bg-white/20 backdrop-blur-sm p-1">
                    <div>
                        <h1 class="text-2xl font-bold">AgriConnect</h1>
                        <p class="text-sm text-emerald-100 -mt-1">Fresh & Organic</p>
                    </div>
                </div>

                <div class="max-w-md">
                    <h2 class="text-3xl font-bold mb-4">Password Baru</h2>
                    <p class="text-emerald-100 text-lg leading-relaxed mb-8">
                        Buat password baru yang kuat untuk melindungi akun Anda di AgriConnect.
                        Pastikan password mudah diingat namun sulit ditebak orang lain.
                    </p>

                    <!-- Security Tips -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-emerald-100">Minimal 8 karakter untuk keamanan</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-emerald-100">Kombinasi huruf, angka & simbol</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-emerald-100">Berbeda dari password sebelumnya</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-emerald-100">Keamanan data terjamin 100%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-20 right-20 w-32 h-32 bg-white bg-opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute bottom-32 right-32 w-20 h-20 bg-white bg-opacity-5 rounded-full animate-bounce"></div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-6 py-12 lg:px-8" x-data="passwordReset()">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <!-- Mobile Logo -->
                <div class="flex justify-center lg:hidden mb-8">
                    <div class="flex items-center">
                        <div class="w-10 h-10 agriconnect-primary rounded-2xl flex items-center justify-center mr-3">
                            <span class="text-xl">🌿</span>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-agriconnect-primary">AgriConnect</h1>
                            <p class="text-xs text-slate-500 -mt-1">Fresh & Organic</p>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900">Reset Password</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Buat password baru untuk akun Anda
                    </p>
                </div>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-slate-200">

                    <!-- Info Message -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            <p class="text-blue-800 text-sm">
                                Pastikan password baru Anda kuat dan mudah diingat. Setelah berhasil, Anda akan otomatis
                                masuk ke akun.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address (Read Only) -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                        </path>
                                    </svg>
                                </div>
                                <input id="email" name="email" type="email" required readonly
                                    value="{{ old('email', $request->email) }}"
                                    class="w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl bg-slate-50 text-slate-600 cursor-not-allowed transition-colors">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                                Password Baru
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="password" name="password" required
                                    :type="showPassword ? 'text' : 'password'" x-model="password"
                                    x-on:input="checkPasswordStrength"
                                    class="w-full pl-10 pr-12 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="Minimal 8 karakter">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="text-slate-400 hover:text-slate-600 transition-colors">
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <svg x-show="showPassword" class="h-5 w-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Password Strength Indicator -->
                            <div x-show="password.length > 0" class="mt-2">
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 bg-slate-200 rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all duration-300"
                                            :class="strengthColor" :style="'width: ' + strengthPercent + '%'"></div>
                                    </div>
                                    <span class="text-xs font-medium" :class="strengthTextColor"
                                        x-text="strengthText"></span>
                                </div>
                            </div>

                            @error('password')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">
                                Konfirmasi Password Baru
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" required
                                    :type="showConfirmPassword ? 'text' : 'password'" x-model="confirmPassword"
                                    class="w-full pl-10 pr-12 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="Ulangi password baru">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                        class="text-slate-400 hover:text-slate-600 transition-colors">
                                        <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <svg x-show="showConfirmPassword" class="h-5 w-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Password Match Indicator -->
                            <div x-show="confirmPassword.length > 0" class="mt-2">
                                <div class="flex items-center text-sm">
                                    <template x-if="password === confirmPassword && password.length > 0">
                                        <div class="flex items-center text-emerald-600">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Password cocok
                                        </div>
                                    </template>
                                    <template x-if="password !== confirmPassword && confirmPassword.length > 0">
                                        <div class="flex items-center text-red-600">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Password tidak cocok
                                        </div>
                                    </template>
                                </div>
                            </div>

                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Requirements -->
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                            <h4 class="text-sm font-medium text-slate-700 mb-3">Syarat Password:</h4>
                            <div class="grid grid-cols-1 gap-2 text-xs">
                                <div class="flex items-center"
                                    :class="password.length >= 8 ? 'text-emerald-600' : 'text-slate-500'">
                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Minimal 8 karakter
                                </div>
                                <div class="flex items-center"
                                    :class="/[A-Z]/.test(password) && /[a-z]/.test(password) ? 'text-emerald-600' :
                                        'text-slate-500'">
                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Kombinasi huruf besar dan kecil
                                </div>
                                <div class="flex items-center"
                                    :class="/\d/.test(password) ? 'text-emerald-600' : 'text-slate-500'">
                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Mengandung angka
                                </div>
                                <div class="flex items-center"
                                    :class="/[^A-Za-z0-9]/.test(password) ? 'text-emerald-600' : 'text-slate-500'">
                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Mengandung simbol (!@#$%^&*)
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white agriconnect-primary hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:-translate-y-0.5"
                                :disabled="!isFormValid"
                                :class="isFormValid ? '' : 'opacity-50 cursor-not-allowed hover:transform-none'">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Links -->
                <p class="mt-8 text-center text-sm text-slate-600">
                    Ingat password Anda?
                    <a href="{{ route('login') }}"
                        class="font-medium text-agriconnect-primary hover:underline transition-colors">
                        Masuk di sini
                    </a>
                </p>
                <p class="mt-2 text-center text-sm text-slate-600">
                    Butuh bantuan lain?
                    <a href="{{ route('password.request') }}"
                        class="font-medium text-agriconnect-primary hover:underline transition-colors">
                        Kirim ulang link reset
                    </a>
                </p>
                <p class="mt-2 text-center text-sm text-slate-600">
                    <a href="{{ route('home') }}"
                        class="font-medium text-slate-500 hover:text-agriconnect-primary hover:underline transition-colors">
                        ← Kembali ke Beranda
                    </a>
                </p>

                <p class="mt-4 text-center text-xs text-slate-500">
                    <a href="{{ route('home') }}" class="hover:underline">Kembali ke beranda</a>
                    •
                    <a href="#" class="hover:underline">Kebijakan Privasi</a>
                    •
                    <a href="#" class="hover:underline">Syarat & Ketentuan</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function passwordReset() {
            return {
                password: '',
                confirmPassword: '',
                showPassword: false,
                showConfirmPassword: false,
                strengthLevel: 0,

                get isFormValid() {
                    return this.password.length >= 8 &&
                        this.password === this.confirmPassword &&
                        this.strengthLevel >= 3;
                },

                get strengthPercent() {
                    return (this.strengthLevel / 4) * 100;
                },

                get strengthColor() {
                    if (this.strengthLevel <= 1) return 'bg-red-500';
                    if (this.strengthLevel === 2) return 'bg-yellow-500';
                    if (this.strengthLevel === 3) return 'bg-blue-500';
                    return 'bg-emerald-500';
                },

                get strengthTextColor() {
                    if (this.strengthLevel <= 1) return 'text-red-600';
                    if (this.strengthLevel === 2) return 'text-yellow-600';
                    if (this.strengthLevel === 3) return 'text-blue-600';
                    return 'text-emerald-600';
                },

                get strengthText() {
                    if (this.strengthLevel <= 1) return 'Lemah';
                    if (this.strengthLevel === 2) return 'Sedang';
                    if (this.strengthLevel === 3) return 'Kuat';
                    return 'Sangat Kuat';
                },

                checkPasswordStrength() {
                    let score = 0;

                    // Length check
                    if (this.password.length >= 8) score++;

                    // Uppercase and lowercase
                    if (/[A-Z]/.test(this.password) && /[a-z]/.test(this.password)) score++;

                    // Numbers
                    if (/\d/.test(this.password)) score++;

                    // Special characters
                    if (/[^A-Za-z0-9]/.test(this.password)) score++;

                    this.strengthLevel = score;
                }
            };
        }
    </script>
</body>

</html>
