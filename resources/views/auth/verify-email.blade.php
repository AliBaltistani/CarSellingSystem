<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verify Email - {{ $globalSettings['site_name'] ?? config('app.name', 'CarSellingSystem') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <meta name="description" content="{{ $globalSettings['meta_description'] ?? 'Verify your email address' }}">

    @if(isset($globalSettings['site_favicon']) && $globalSettings['site_favicon'])
        <link rel="icon" href="{{ Storage::url($globalSettings['site_favicon']) }}" type="image/x-icon"/>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%239C92AC%22 fill-opacity=%220.03%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>

            <div class="relative z-10 flex flex-col justify-center px-12 lg:px-16 xl:px-24">
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

                <div class="w-20 h-20 bg-gradient-to-br from-violet-400/20 to-purple-500/20 rounded-3xl flex items-center justify-center mb-8">
                    <svg class="w-10 h-10 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>

                <h1 class="text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                    Verify Your<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-purple-500">Email Address</span>
                </h1>

                <p class="text-slate-400 text-lg mb-8 max-w-md">
                    We've sent a verification link to your email. Please check your inbox and click the link to activate your account.
                </p>

                <div class="space-y-4">
                    <div class="flex items-center space-x-3 text-slate-300">
                        <div class="w-8 h-8 bg-violet-500/20 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-violet-400">1</span>
                        </div>
                        <span>Open the email we sent you</span>
                    </div>
                    <div class="flex items-center space-x-3 text-slate-300">
                        <div class="w-8 h-8 bg-violet-500/20 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-violet-400">2</span>
                        </div>
                        <span>Click the verification link</span>
                    </div>
                    <div class="flex items-center space-x-3 text-slate-300">
                        <div class="w-8 h-8 bg-violet-500/20 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-violet-400">3</span>
                        </div>
                        <span>Start browsing & listing cars!</span>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-br from-violet-500/10 to-purple-500/10 rounded-full blur-3xl"></div>
            <div class="absolute top-20 right-20 w-64 h-64 bg-gradient-to-br from-pink-500/10 to-rose-500/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Right Side - Content -->
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

                <!-- Email Illustration -->
                <div class="text-center mb-8">
                    <div class="w-24 h-24 bg-gradient-to-br from-violet-50 to-purple-50 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-violet-100">
                        <svg class="w-12 h-12 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-slate-900 mb-2 text-center">Check Your Email</h2>
                <p class="text-slate-500 mb-4 text-center">
                    We've sent a verification link to<br>
                    <span class="font-semibold text-slate-700">{{ Auth::user()->email }}</span>
                </p>
                <p class="text-slate-400 text-sm mb-8 text-center">
                    Click the link in the email to verify your account. If you don't see it, check your spam folder.
                </p>

                <!-- Success Message -->
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 text-sm text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        A new verification link has been sent to your email address.
                    </div>
                @endif

                <!-- Resend Button -->
                <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                    @csrf
                    <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:from-amber-600 hover:to-orange-600 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all transform hover:scale-[1.02]">
                        Resend Verification Email
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center my-6">
                    <hr class="flex-1 border-slate-200">
                    <span class="px-4 text-sm text-slate-400">or</span>
                    <hr class="flex-1 border-slate-200">
                </div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full py-3 px-4 bg-white text-slate-700 font-medium rounded-xl border border-slate-300 hover:bg-slate-50 focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 transition-all">
                        Sign Out & Use Different Account
                    </button>
                </form>

                <!-- Help Text -->
                <p class="text-center mt-8 text-sm text-slate-400">
                    Having trouble? 
                    <a href="{{ route('home') }}" class="text-amber-600 hover:text-amber-700 font-medium">Contact Support</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
