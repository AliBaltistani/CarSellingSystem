<x-layouts.admin title="Settings">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Site Settings</h1>
        <p class="text-slate-600">Manage application configuration</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- General Settings --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                General Settings
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Site Name --}}
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Site Name</label>
                    <input type="text" name="settings[site_name]" value="{{ $settings['general']->firstWhere('key', 'site_name')->value ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                {{-- Site Tagline --}}
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Site Tagline</label>
                    <input type="text" name="settings[site_tagline]" value="{{ $settings['general']->firstWhere('key', 'site_tagline')->value ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                {{-- Site Logo --}}
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Site Logo</label>
                    <div class="flex items-center gap-4">
                        @if($logo = $settings['general']->firstWhere('key', 'site_logo')->value ?? null)
                            <div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center p-2 border border-slate-200">
                                <img src="{{ Storage::url($logo) }}" alt="Logo" class="max-w-full max-h-full">
                            </div>
                        @endif
                        <input type="file" name="site_logo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    </div>
                </div>
                {{-- Site Favicon --}}
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Site Favicon</label>
                    <div class="flex items-center gap-4">
                        @if($favicon = $settings['general']->firstWhere('key', 'site_favicon')->value ?? null)
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center p-2 border border-slate-200">
                                <img src="{{ Storage::url($favicon) }}" alt="Favicon" class="max-w-full max-h-full">
                            </div>
                        @endif
                        <input type="file" name="site_favicon" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    </div>
                </div>
                {{-- Site Description --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Site Description</label>
                    <textarea name="settings[site_description]" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">{{ $settings['general']->firstWhere('key', 'site_description')->value ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- Contact Settings --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                Contact Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                    <input type="email" name="settings[site_email]" value="{{ $settings['general']->firstWhere('key', 'site_email')->value ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                    <input type="text" name="settings[site_phone]" value="{{ $settings['general']->firstWhere('key', 'site_phone')->value ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">WhatsApp Number</label>
                    <input type="text" name="settings[whatsapp_number]" value="{{ $settings['contact']->firstWhere('key', 'whatsapp_number')->value ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Address</label>
                    <input type="text" name="settings[site_address]" value="{{ $settings['general']->firstWhere('key', 'site_address')->value ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
        </div>

        {{-- Social Media --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                Social Media
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Facebook URL</label>
                    <input type="url" name="settings[facebook_url]" value="{{ $settings['contact']->firstWhere('key', 'facebook_url')->value ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Twitter URL</label>
                    <input type="url" name="settings[twitter_url]" value="{{ $settings['contact']->firstWhere('key', 'twitter_url')->value ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Instagram URL</label>
                    <input type="url" name="settings[instagram_url]" value="{{ $settings['contact']->firstWhere('key', 'instagram_url')->value ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">LinkedIn URL</label>
                    <input type="url" name="settings[linkedin_url]" value="{{ $settings['contact']->firstWhere('key', 'linkedin_url')->value ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
        </div>

        {{-- SEO Settings --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                SEO Configuration
            </h2>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Meta Title (Default)</label>
                    <input type="text" name="settings[meta_title]" value="{{ $settings['seo']->firstWhere('key', 'meta_title')->value ?? '' }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Meta Description (Default)</label>
                    <textarea name="settings[meta_description]" rows="2" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">{{ $settings['seo']->firstWhere('key', 'meta_description')->value ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Meta Keywords</label>
                    <textarea name="settings[meta_keywords]" rows="2" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">{{ $settings['seo']->firstWhere('key', 'meta_keywords')->value ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex justify-end sticky bottom-6 z-10">
            <button type="submit" class="px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg transition-all transform hover:scale-105 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save All Settings
            </button>
        </div>
    </form>
</x-layouts.admin>
