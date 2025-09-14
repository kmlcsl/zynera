<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verifikasi OTP - {{ config('app.name', 'AgriConnect') }}</title>

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

        .otp-input {
            transition: all 0.2s ease;
        }

        .otp-input:focus {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
        }

        .countdown-circle {
            animation: rotate 60s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
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
                    <h2 class="text-3xl font-bold mb-4">Satu Langkah Lagi!</h2>
                    <p class="text-emerald-100 text-lg leading-relaxed mb-8">
                        Verifikasi email Anda dengan memasukkan kode OTP yang telah dikirim.
                        Langkah terakhir untuk bergabung dengan komunitas AgriConnect.
                    </p>

                    <!-- Security Features -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-emerald-100">Keamanan data terjamin dengan enkripsi</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-emerald-100">Verifikasi email untuk akun yang aman</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-emerald-100">Proses registrasi yang mudah dan cepat</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-20 right-20 w-32 h-32 bg-white bg-opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute bottom-32 right-32 w-20 h-20 bg-white bg-opacity-5 rounded-full animate-bounce"></div>
        </div>

        <!-- Right Side - OTP Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-6 py-12 lg:px-8" x-data="otpVerification()">
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

                <!-- Header -->
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div
                            class="w-16 h-16 agriconnect-primary rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-3xl font-bold tracking-tight text-slate-900">Verifikasi Email</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Masukkan kode 6 digit yang telah dikirim ke
                    </p>
                    <p class="text-agriconnect-primary font-medium mt-1">{{ $email }}</p>

                    @if (isset($registrationData))
                        <div class="mt-3 text-xs text-slate-500">
                            Mendaftar sebagai: <span
                                class="font-medium">{{ ucfirst($registrationData['user_type']) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-slate-200">

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- OTP Form -->
                    <form method="POST" action="{{ route('google.otp.verify.post') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">

                        <!-- OTP Input -->
                        <div>
                            <label for="otp" class="block text-sm font-medium text-slate-700 mb-3">
                                Kode Verifikasi OTP
                            </label>

                            <!-- OTP Input Box -->
                            <div class="relative">
                                <input type="text" name="otp" id="otp" maxlength="6" required
                                    x-model="otp" x-on:input="handleOtpInput"
                                    class="otp-input w-full px-4 py-4 text-center text-2xl font-bold border-2 border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 tracking-[0.5em] transition-all duration-200"
                                    placeholder="000000" autocomplete="off" inputmode="numeric" pattern="[0-9]{6}">

                                <!-- OTP Indicator -->
                                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                                    <div class="flex space-x-2">
                                        <template x-for="i in 6" :key="i">
                                            <div class="w-2 h-2 rounded-full transition-colors duration-200"
                                                :class="otp.length >= i ? 'bg-emerald-500' : 'bg-slate-300'">
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            @error('otp')
                                <p class="mt-3 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror

                            <!-- Info Text -->
                            <p class="mt-3 text-xs text-slate-500 text-center">
                                Kode berlaku selama <span class="font-medium text-emerald-600">10 menit</span>
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white agriconnect-primary hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                :disabled="otp.length !== 6">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Verifikasi Akun
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-slate-500">Tidak menerima kode?</span>
                            </div>
                        </div>
                    </div>

                    <!-- Resend OTP -->
                    <div class="mt-6 text-center">
                        <div x-show="!isResendDisabled" x-transition>
                            <form method="POST" action="{{ route('google.otp.resend') }}" style="display: inline;">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                <button type="submit"
                                    class="inline-flex items-center text-agriconnect-primary hover:underline font-medium text-sm transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Kirim Ulang Kode OTP
                                </button>
                            </form>
                        </div>

                        <div x-show="isResendDisabled" x-transition class="text-slate-500 text-sm">
                            Kirim ulang dalam <span x-text="resendTimer" class="font-medium"></span> detik
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="mt-6 p-4 bg-slate-50 rounded-xl">
                        <h4 class="text-sm font-medium text-slate-900 mb-2">Tidak menerima email?</h4>
                        <ul class="text-xs text-slate-600 space-y-1">
                            <li>• Periksa folder spam/junk email Anda</li>
                            <li>• Pastikan email yang dimasukkan benar</li>
                            <li>• Tunggu beberapa menit, email mungkin tertunda</li>
                        </ul>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-8 text-center space-y-2">
                    <p class="text-sm text-slate-600">
                        <a href="{{ route('register') }}"
                            class="font-medium text-agriconnect-primary hover:underline">
                            ← Kembali ke Pendaftaran
                        </a>
                    </p>

                    <p class="text-xs text-slate-500">
                        <a href="{{ route('home') }}" class="hover:underline">Kembali ke beranda</a>
                        •
                        <a href="#" class="hover:underline">Bantuan</a>
                        •
                        <a href="#" class="hover:underline">Kontak</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function otpVerification() {
            return {
                otp: '',
                isResendDisabled: true,
                resendTimer: 60,

                init() {
                    this.startResendTimer();

                    // Auto-focus pada input OTP
                    this.$nextTick(() => {
                        document.getElementById('otp').focus();
                    });
                },

                handleOtpInput() {
                    // Hanya izinkan angka
                    this.otp = this.otp.replace(/[^0-9]/g, '');

                    // Auto submit ketika 6 digit terisi
                    if (this.otp.length === 6) {
                        setTimeout(() => {
                            document.querySelector('form').submit();
                        }, 300);
                    }
                },

                startResendTimer() {
                    const timer = setInterval(() => {
                        this.resendTimer--;

                        if (this.resendTimer <= 0) {
                            this.isResendDisabled = false;
                            clearInterval(timer);
                        }
                    }, 1000);
                }
            }
        }
    </script>
</body>

</html>
