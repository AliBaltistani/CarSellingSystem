<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-6 mb-6 shadow-lg">
                <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
                    <div class="relative">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                                class="w-24 h-24 rounded-full object-cover border-4 border-white/20">
                        @else
                            <div class="w-24 h-24 rounded-full bg-amber-500 flex items-center justify-center text-white text-3xl font-bold border-4 border-white/20">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-center sm:text-left">
                        <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                        <p class="text-slate-400">{{ $user->email }}</p>
                        <p class="text-slate-500 text-sm mt-2">Member since {{ $user->created_at->format('F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="bg-white shadow-sm rounded-2xl border border-slate-100 mb-6">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-semibold text-slate-900">Profile Information</h3>
                    <p class="text-sm text-slate-500 mt-1">Update your personal information and contact details.</p>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white shadow-sm rounded-2xl border border-slate-100 mb-6">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-semibold text-slate-900">Update Password</h3>
                    <p class="text-sm text-slate-500 mt-1">Ensure your account is using a secure password.</p>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-white shadow-sm rounded-2xl border border-red-100 mb-6">
                <div class="p-6 border-b border-red-100">
                    <h3 class="text-lg font-semibold text-red-600">Delete Account</h3>
                    <p class="text-sm text-slate-500 mt-1">Permanently delete your account and all of its data.</p>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
