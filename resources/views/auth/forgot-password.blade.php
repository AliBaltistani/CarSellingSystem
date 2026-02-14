<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Forgot Password - {{ $globalSettings['site_name'] ?? config('app.name', 'CarSellingSystem') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <meta name="description" content="{{ $globalSettings['meta_description'] ?? 'Reset your password' }}">

    @if(isset($globalSettings['site_favicon']) && $globalSettings['site_favicon'])
        <link rel="icon" href="{{ Storage::url($globalSettings['site_favicon']) }}" type="image/x-icon"/>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%239C92AC%22 fill-opacity=%220.03%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center px-12 lg:px-16 xl:px-24">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="inline-flex items-center space-x-3 mb-12 group">
                    @if(isset($globalSettings['site_logo']) && $globalSettings['site_logo'])
                        <img src="{{ Storage::url($globalSettings['site_logo']) }}" alt="{{ $globalSettings['site_name'] ?? 'Logo' }}" class="h-14 w-auto">
                    @else
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/25 transform group-hover:scale-105 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <span class="block text-2xl font-bold text-white">{{ $globalSettings['site_name'] ?? config('app.name', 'CarSellingSystem') }}</span>
                        <span class="block text-sm text-slate-400">{{ $globalSettings['site_description'] ?? "UAE's Premium Car Marketplace" }}</span>
                    </div>
                </a>

                <div class="w-20 h-20 bg-gradient-to-br from-amber-400/20 to-orange-500/20 rounded-3xl flex items-center justify-center mb-8">
                    <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <h1 class="text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                    Forgot Your<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">Password?</span>
                </h1>

                <p class="text-slate-400 text-lg mb-8 max-w-md">
                    No worries! We'll send you a secure link to reset your password and get you back on track.
                </p>

                <!-- Steps -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 text-slate-300">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-emerald-400">1</span>
                        </div>
                        <span>Enter your email address</span>
                    </div>
                    <div class="flex items-center space-x-3 text-slate-300">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-emerald-400">2</span>
                        </div>
                        <span>Check your inbox for the reset link</span>
                    </div>
                    <div class="flex items-center space-x-3 text-slate-300">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-emerald-400">3</span>
                        </div>
                        <span>Create a new secure password</span>
                    </div>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-br from-amber-500/10 to-orange-500/10 rounded-full blur-3xl"></div>
            <div class="absolute top-20 right-20 w-64 h-64 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-6 sm:px-12 lg:px-16 xl:px-24 bg-white">
            <div class="max-w-md w-full mx-auto">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center space-x-3">
                        @if(isset($globalSettings['site_logo']) && $globalSettings['site_logo'])
                            <img src="{{ Storage::url($globalSettings['site_logo']) }}" alt="{{ $globalSettings['site_name'] ?? 'Logo' }}" class="h-12 w-auto">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                        @endif
                        <span class="text-2xl font-bold text-slate-900">{{ $globalSettings['site_name'] ?? config('app.name', 'CarSellingSystem') }}</span>
                    </a>
                </div>

                <!-- Icon -->
                <div class="w-16 h-16 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl flex items-center justify-center mb-6 border border-amber-100">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <h2 class="text-3xl font-bold text-slate-900 mb-2">Forgot Password</h2>
                <p class="text-slate-500 mb-8">Enter your email and we'll send you a reset link.</p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-6 text-sm text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all placeholder-slate-400"
                                placeholder="you@example.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:from-amber-600 hover:to-orange-600 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all transform hover:scale-[1.02]">
                        Send Password Reset Link
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center my-8">
                    <hr class="flex-1 border-slate-200">
                    <span class="px-4 text-sm text-slate-400">or</span>
                    <hr class="flex-1 border-slate-200">
                </div>

                <!-- Back to Login -->
                <p class="text-center text-slate-600">
                    Remember your password?
                    <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-semibold ml-1">
                        Sign In
                    </a>
                </p>

                <!-- Back to Home -->
                <p class="text-center mt-6">
                    <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-amber-600 transition-colors">
                        ← Back to Homepage
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
