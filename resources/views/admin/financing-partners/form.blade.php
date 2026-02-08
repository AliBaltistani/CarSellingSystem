<x-layouts.admin :title="$partner->exists ? 'Edit Partner' : 'Add Partner'">
    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('admin.financing-partners.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Partners
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h1 class="text-xl font-bold text-slate-900 mb-6">
                {{ $partner->exists ? 'Edit Financing Partner' : 'Add New Financing Partner' }}
            </h1>

            <form action="{{ $partner->exists ? route('admin.financing-partners.update', $partner) : route('admin.financing-partners.store') }}" 
                method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if($partner->exists)
                    @method('PUT')
                @endif

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Partner Name *</label>
                    <input type="text" id="name" name="name" 
                        value="{{ old('name', $partner->name) }}" required placeholder="e.g. Emirates NBD, ADCB"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Logo Upload -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Logo {{ $partner->exists ? '' : '*' }}</label>
                    @if($partner->logo)
                        <div class="mb-3">
                            <img src="{{ $partner->logo_url }}" alt="Current logo" class="h-12 max-w-32 object-contain bg-slate-100 p-2 rounded">
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*" {{ $partner->exists ? '' : 'required' }}
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    <p class="mt-1 text-sm text-slate-500">Recommended: PNG with transparent background, max 2MB</p>
                    @error('logo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Website URL -->
                <div>
                    <label for="website_url" class="block text-sm font-medium text-slate-700 mb-1">Website URL</label>
                    <input type="url" id="website_url" name="website_url" 
                        value="{{ old('website_url', $partner->website_url) }}" placeholder="https://www.example.com"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                    <x-forms.rich-editor name="description" :value="$partner->description" height="120px" />
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-medium text-slate-700 mb-1">Display Order</label>
                    <input type="number" id="order" name="order" 
                        value="{{ old('order', $partner->order ?? 0) }}" min="0"
                        class="w-32 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" 
                            {{ old('is_active', $partner->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-amber-500 border-slate-300 rounded focus:ring-amber-500">
                        <span class="ml-2 text-sm text-slate-700">Active (visible on website)</span>
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('admin.financing-partners.index') }}" 
                        class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-medium rounded-lg transition-all shadow-sm">
                        {{ $partner->exists ? 'Update Partner' : 'Create Partner' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
