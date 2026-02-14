<x-layouts.admin title="Settings">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Site Settings</h1>
        <p class="text-slate-600">Manage application configuration</p>
    </div>

    @if(session('success'))
        <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium text-green-800">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium text-red-800">{{ session('error') }}</span>
        </div>
    @endif

    <div x-data="{ activeTab: 'general' }" class="flex flex-col lg:flex-row gap-6">

        {{-- Sidebar Tabs --}}
        <div class="lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden sticky top-6">
                <nav class="flex flex-row lg:flex-col overflow-x-auto lg:overflow-x-visible">
                    <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-amber-50 text-amber-700 border-amber-500' : 'text-slate-600 hover:bg-slate-50 border-transparent'"
                            class="flex items-center gap-3 px-5 py-3.5 text-sm font-medium border-b-2 lg:border-b-0 lg:border-l-3 transition-all text-left whitespace-nowrap w-full">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        General
                    </button>
                    <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'bg-amber-50 text-amber-700 border-amber-500' : 'text-slate-600 hover:bg-slate-50 border-transparent'"
                            class="flex items-center gap-3 px-5 py-3.5 text-sm font-medium border-b-2 lg:border-b-0 lg:border-l-3 transition-all text-left whitespace-nowrap w-full">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        Contact Info
                    </button>
                    <button @click="activeTab = 'social'" :class="activeTab === 'social' ? 'bg-amber-50 text-amber-700 border-amber-500' : 'text-slate-600 hover:bg-slate-50 border-transparent'"
                            class="flex items-center gap-3 px-5 py-3.5 text-sm font-medium border-b-2 lg:border-b-0 lg:border-l-3 transition-all text-left whitespace-nowrap w-full">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        Social Media
                    </button>
                    <button @click="activeTab = 'email'" :class="activeTab === 'email' ? 'bg-amber-50 text-amber-700 border-amber-500' : 'text-slate-600 hover:bg-slate-50 border-transparent'"
                            class="flex items-center gap-3 px-5 py-3.5 text-sm font-medium border-b-2 lg:border-b-0 lg:border-l-3 transition-all text-left whitespace-nowrap w-full">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        Email / SMTP
                    </button>
                    <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-amber-50 text-amber-700 border-amber-500' : 'text-slate-600 hover:bg-slate-50 border-transparent'"
                            class="flex items-center gap-3 px-5 py-3.5 text-sm font-medium border-b-2 lg:border-b-0 lg:border-l-3 transition-all text-left whitespace-nowrap w-full">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        SEO
                    </button>
                </nav>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="flex-1 min-w-0">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ═══════════════════════════════════════════════════ --}}
                {{-- GENERAL TAB --}}
                {{-- ═══════════════════════════════════════════════════ --}}
                <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">General Settings</h2>
                                <p class="text-sm text-slate-500">Basic site information and branding</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Site Name</label>
                                <input type="text" name="settings[site_name]" value="{{ $settings->get('general', collect())->firstWhere('key', 'site_name')->value ?? '' }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Site Tagline</label>
                                <input type="text" name="settings[site_tagline]" value="{{ $settings->get('general', collect())->firstWhere('key', 'site_tagline')->value ?? '' }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Site Logo</label>
                                <div class="flex items-center gap-4">
                                    @if($logo = $settings->get('general', collect())->firstWhere('key', 'site_logo')->value ?? null)
                                        <div class="w-14 h-14 bg-slate-50 rounded-lg flex items-center justify-center p-2 border border-slate-200">
                                            <img src="{{ Storage::url($logo) }}" alt="Logo" class="max-w-full max-h-full">
                                        </div>
                                    @endif
                                    <input type="file" name="site_logo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-colors">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Site Favicon</label>
                                <div class="flex items-center gap-4">
                                    @if($favicon = $settings->get('general', collect())->firstWhere('key', 'site_favicon')->value ?? null)
                                        <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center p-2 border border-slate-200">
                                            <img src="{{ Storage::url($favicon) }}" alt="Favicon" class="max-w-full max-h-full">
                                        </div>
                                    @endif
                                    <input type="file" name="site_favicon" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-colors">
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Site Description</label>
                                <textarea name="settings[site_description]" rows="3" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow resize-none">{{ $settings->get('general', collect())->firstWhere('key', 'site_description')->value ?? '' }}</textarea>
                            </div>

                            {{-- Email Verification Toggle --}}
                            <div class="md:col-span-2 pt-4 border-t border-slate-100" x-data="{ enabled: {{ \App\Models\Setting::get('email_verification_enabled', false) ? 'true' : 'false' }} }">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Email Verification</label>
                                        <p class="text-sm text-slate-500 mt-0.5">Require users to verify their email address after registration</p>
                                    </div>
                                    <div>
                                        <input type="hidden" name="settings[email_verification_enabled]" x-ref="emailVerificationInput" :value="enabled ? '1' : '0'"
                                            x-effect="if($refs.emailVerificationInput) $refs.emailVerificationInput.value = enabled ? '1' : '0'">
                                        <button type="button" @click="enabled = !enabled"
                                            :class="enabled ? 'bg-amber-500' : 'bg-slate-300'"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                            <span :class="enabled ? 'translate-x-6' : 'translate-x-1'"
                                                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-sm"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Save --}}
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl shadow-sm transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </button>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════ --}}
                {{-- CONTACT TAB --}}
                {{-- ═══════════════════════════════════════════════════ --}}
                <div x-show="activeTab === 'contact'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Contact Information</h2>
                                <p class="text-sm text-slate-500">How customers can reach you</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                                    <input type="email" name="settings[site_email]" value="{{ $settings->get('general', collect())->firstWhere('key', 'site_email')->value ?? '' }}" class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow" placeholder="admin@example.com">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></span>
                                    <input type="text" name="settings[site_phone]" value="{{ $settings->get('general', collect())->firstWhere('key', 'site_phone')->value ?? '' }}" class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow" placeholder="+1234567890">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">WhatsApp Number</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg></span>
                                    <input type="text" name="settings[whatsapp_number]" value="{{ $settings->get('contact', collect())->firstWhere('key', 'whatsapp_number')->value ?? '' }}" class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow" placeholder="+1234567890">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Address</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
                                    <input type="text" name="settings[site_address]" value="{{ $settings->get('general', collect())->firstWhere('key', 'site_address')->value ?? '' }}" class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow" placeholder="123 Main St, City">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl shadow-sm transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </button>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════ --}}
                {{-- SOCIAL MEDIA TAB --}}
                {{-- ═══════════════════════════════════════════════════ --}}
                <div x-show="activeTab === 'social'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Social Media</h2>
                                <p class="text-sm text-slate-500">Link your social media profiles</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Facebook</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></span>
                                    <input type="url" name="settings[facebook_url]" value="{{ $settings->get('contact', collect())->firstWhere('key', 'facebook_url')->value ?? '' }}" class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow" placeholder="https://facebook.com/yourpage">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Twitter / X</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-4 h-4 text-slate-800" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></span>
                                    <input type="url" name="settings[twitter_url]" value="{{ $settings->get('contact', collect())->firstWhere('key', 'twitter_url')->value ?? '' }}" class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow" placeholder="https://x.com/yourprofile">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Instagram</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></span>
                                    <input type="url" name="settings[instagram_url]" value="{{ $settings->get('contact', collect())->firstWhere('key', 'instagram_url')->value ?? '' }}" class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow" placeholder="https://instagram.com/yourprofile">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">LinkedIn</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><svg class="w-4 h-4 text-blue-700" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg></span>
                                    <input type="url" name="settings[linkedin_url]" value="{{ $settings->get('contact', collect())->firstWhere('key', 'linkedin_url')->value ?? '' }}" class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow" placeholder="https://linkedin.com/company/yours">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl shadow-sm transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </button>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════ --}}
                {{-- EMAIL / SMTP TAB --}}
                {{-- ═══════════════════════════════════════════════════ --}}
                <div x-show="activeTab === 'email'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Email / SMTP Configuration</h2>
                                <p class="text-sm text-slate-500">Configure your mail server to send real emails</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Mail Driver</label>
                                <select name="settings[mail_mailer]" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow">
                                    @php $currentMailer = $settings->get('mail', collect())->firstWhere('key', 'mail_mailer')->value ?? 'smtp'; @endphp
                                    <option value="smtp" {{ $currentMailer === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ $currentMailer === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="log" {{ $currentMailer === 'log' ? 'selected' : '' }}>Log (Testing Only)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Encryption</label>
                                <select name="settings[mail_encryption]" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow">
                                    @php $currentEnc = $settings->get('mail', collect())->firstWhere('key', 'mail_encryption')->value ?? 'tls'; @endphp
                                    <option value="tls" {{ $currentEnc === 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ $currentEnc === 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="none" {{ $currentEnc === 'none' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">SMTP Host</label>
                                <input type="text" name="settings[mail_host]" value="{{ $settings->get('mail', collect())->firstWhere('key', 'mail_host')->value ?? '' }}" placeholder="smtp.gmail.com" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">SMTP Port</label>
                                <input type="text" name="settings[mail_port]" value="{{ $settings->get('mail', collect())->firstWhere('key', 'mail_port')->value ?? '587' }}" placeholder="587" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">SMTP Username</label>
                                <input type="text" name="settings[mail_username]" value="{{ $settings->get('mail', collect())->firstWhere('key', 'mail_username')->value ?? '' }}" placeholder="your@email.com" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">SMTP Password</label>
                                <input type="password" name="settings[mail_password]" value="{{ $settings->get('mail', collect())->firstWhere('key', 'mail_password')->value ?? '' }}" placeholder="••••••••" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">From Email Address</label>
                                <input type="email" name="settings[mail_from_address]" value="{{ $settings->get('mail', collect())->firstWhere('key', 'mail_from_address')->value ?? '' }}" placeholder="noreply@yourdomain.com" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">From Name</label>
                                <input type="text" name="settings[mail_from_name]" value="{{ $settings->get('mail', collect())->firstWhere('key', 'mail_from_name')->value ?? '' }}" placeholder="Xenon Motors" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow">
                            </div>
                        </div>

                        {{-- Test Email --}}
                        <div class="mt-6 pt-5 border-t border-slate-100" x-data="{ testEmail: '{{ $settings->get('general', collect())->firstWhere('key', 'site_email')->value ?? '' }}', sending: false, result: '' }">
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Send Test Email To</label>
                                    <input type="email" x-model="testEmail" placeholder="recipient@example.com"
                                           class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow text-sm">
                                </div>
                                <button type="button" :disabled="sending || !testEmail"
                                        @click="
                                            sending = true; result = '';
                                            fetch('{{ route('admin.settings.test-email') }}', {
                                                method: 'POST',
                                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                                body: JSON.stringify({ test_email: testEmail })
                                            })
                                            .then(r => r.json())
                                            .then(d => { result = d.success ? '✅ ' + d.message : '❌ ' + d.message; })
                                            .catch(e => { result = '❌ Request failed: ' + e.message; })
                                            .finally(() => { sending = false; });
                                        "
                                        class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-500 hover:bg-blue-600 disabled:opacity-50 text-white rounded-lg text-sm font-medium transition-colors shadow-sm whitespace-nowrap">
                                    <svg x-show="!sending" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <svg x-show="sending" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    <span x-text="sending ? 'Sending...' : 'Send Test'"></span>
                                </button>
                            </div>
                            <p x-show="result" x-text="result" class="mt-3 text-sm font-medium" :class="result.startsWith('✅') ? 'text-green-600' : 'text-red-600'" x-transition></p>
                        </div>

                        {{-- Quick Reference --}}
                        <div class="mt-5 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <h4 class="text-sm font-semibold text-amber-800 mb-2">📋 Common SMTP Settings</h4>
                            <div class="text-xs text-amber-700 space-y-1">
                                <p><strong>Gmail:</strong> Host: smtp.gmail.com | Port: 587 | Encryption: TLS | Use App Password</p>
                                <p><strong>Hostinger:</strong> Host: smtp.hostinger.com | Port: 465 | Encryption: SSL</p>
                                <p><strong>Outlook:</strong> Host: smtp-mail.outlook.com | Port: 587 | Encryption: TLS</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl shadow-sm transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </button>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════ --}}
                {{-- SEO TAB --}}
                {{-- ═══════════════════════════════════════════════════ --}}
                <div x-show="activeTab === 'seo'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">SEO Configuration</h2>
                                <p class="text-sm text-slate-500">Optimize your site for search engines</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Meta Title (Default)</label>
                                <input type="text" name="settings[meta_title]" value="{{ $settings->get('seo', collect())->firstWhere('key', 'meta_title')->value ?? '' }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow" placeholder="Your Site Title - Tagline">
                                <p class="mt-1.5 text-xs text-slate-400">Recommended: 50-60 characters</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Meta Description (Default)</label>
                                <textarea name="settings[meta_description]" rows="2" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow resize-none" placeholder="A brief, compelling description of your site...">{{ $settings->get('seo', collect())->firstWhere('key', 'meta_description')->value ?? '' }}</textarea>
                                <p class="mt-1.5 text-xs text-slate-400">Recommended: 150-160 characters</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Meta Keywords</label>
                                <textarea name="settings[meta_keywords]" rows="2" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow resize-none" placeholder="car, selling, motors, vehicles">{{ $settings->get('seo', collect())->firstWhere('key', 'meta_keywords')->value ?? '' }}</textarea>
                                <p class="mt-1.5 text-xs text-slate-400">Comma-separated keywords</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl shadow-sm transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-layouts.admin>
