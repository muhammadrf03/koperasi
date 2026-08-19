<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KREATINDO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen flex">
    <!-- LEFT SIDE - Logo/Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-slate-950 text-white p-12 flex-col justify-between relative overflow-hidden">
        <!-- Background Aurora (React WebGL) -->
        <div id="aurora-bg" class="absolute inset-0"></div>
        
        <!-- Content Left - Logo Kecil -->
       

        <!-- Center - Main Logo -->
        <div class="relative z-10 flex-1 flex flex-col items-center justify-center text-center">
            <div class="animate-float">
                <div class="w-56 h-56 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center mx-auto shadow-2xl p-6">
                    <!-- LOGO UTAMA BESAR -->
                    <img src="{{ asset('images/logo.png') }}" alt="KREATINDO" class="w-full h-full object-contain">
                </div>
            </div>
            <h2 class="text-4xl font-bold mt-8 mb-2"></h2>
            <p class="text-blue-200 text-lg max-w-md">
                PT. Pusat Kreatif Indonesia
            </p>
            <p class="text-blue-200 text-lg max-w-md">
                Solusi kreatif untuk masa depan yang lebih baik
            </p>
            
            <!-- Feature List -->
            <div class="grid grid-cols-2 gap-4 mt-8 max-w-md">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <i class="fas fa-lightbulb text-2xl mb-2"></i>
                    <p class="text-sm font-semibold">Inovasi Kreatif</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <i class="fas fa-users text-2xl mb-2"></i>
                    <p class="text-sm font-semibold">Tim Profesional</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <i class="fas fa-chart-line text-2xl mb-2"></i>
                    <p class="text-sm font-semibold">Pertumbuhan Bisnis</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <i class="fas fa-shield-alt text-2xl mb-2"></i>
                    <p class="text-sm font-semibold">Kualitas Terjamin</p>
                </div>
            </div>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mt-4"> </h2>
        <!-- Footer Left -->
        <div class="relative z-10">
            <p class="text-blue-200 text-sm text-center">
                &copy; 2026 PT. Kreatindo All rights reserved.
            </p>
        </div>
    </div>

    <!-- RIGHT SIDE - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg p-3">
                    <!-- LOGO UNTUK MOBILE -->
                    <img src="{{ asset('images/logo.png') }}" alt="KREATINDO" class="w-full h-full object-contain">
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mt-4">KREATINDO</h2>
                <p class="text-gray-500 text-sm">PT. Pusat Kreatif Indonesia</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="hidden lg:block text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-800">Selamat Datang</h3>
                    <p class="text-gray-500 text-sm mt-1">Silakan login ke akun Anda</p>
                </div>

                <!-- Form -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl p-3">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    @if ($errors->has('email'))
                        <!-- Alert: Email / Kata Sandi Salah -->
                        <div id="loginAlert" class="mb-4 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4">
                            <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-red-700">Login Gagal</p>
                                <p class="text-sm text-red-600">{{ $errors->first('email') }}</p>
                            </div>
                            <button type="button" class="text-red-400 hover:text-red-600 transition" onclick="document.getElementById('loginAlert').remove()" aria-label="Tutup notifikasi">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-600"></i>Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" 
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition @error('email') border-red-500 @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="masukkan@email.com"
                                   required autofocus>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-600"></i>Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" 
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition @error('password') border-red-500 @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="••••••••"
                                   required autocomplete="current-password">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword()">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                                   id="remember" 
                                   name="remember">
                            <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                        </div>
                        
                    </div>

                    <!-- Cloudflare Turnstile CAPTCHA -->
                    <div class="mb-6 flex flex-col items-center">
                        <div class="cf-turnstile"
                             data-sitekey="{{ config('services.turnstile.site_key') }}"
                             data-language="id"
                             data-theme="light"></div>
                        @error('cf-turnstile-response')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:scale-[1.02] shadow-lg hover:shadow-blue-500/30">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>

                    <!-- Register -->
                    
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

    @viteReactRefresh
    @vite(['resources/js/aurora-login-entry.ts'])
</body>
</html>