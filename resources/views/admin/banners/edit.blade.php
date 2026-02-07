<x-layouts.admin title="Edit Banner">
    <div class="mb-6">
        <a href="{{ route('admin.banners.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Banners
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-slate-100">
            <h1 class="text-xl font-bold text-slate-900">Edit Banner</h1>
            <p class="text-slate-600">Update banner details and image</p>
        </div>

        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Banner Image -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Banner Image</label>
                <div class="mb-4">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" 
                        class="max-h-48 rounded-lg shadow-sm" id="current-image">
                </div>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-amber-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <div id="image-preview" class="hidden mb-4">
                            <img src="" alt="Preview" class="mx-auto max-h-48 rounded-lg">
                        </div>
                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" id="upload-icon">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-amber-600 hover:text-amber-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-amber-500">
                                <span>Upload new image</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                            </label>
                        </div>
                        <p class="text-xs text-slate-500">PNG, JPG, WEBP up to 5MB. Leave empty to keep current.</p>
                    </div>
                </div>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Title (Optional)</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $banner->title) }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        placeholder="e.g., Summer Sale - Up to 50% Off">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-medium text-slate-700 mb-2">Display Order</label>
                    <input type="number" name="order" id="order" value="{{ old('order', $banner->order) }}" min="0"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        placeholder="0">
                    @error('order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Subtitle -->
            <div>
                <label for="subtitle" class="block text-sm font-medium text-slate-700 mb-2">Subtitle (Optional)</label>
                <textarea name="subtitle" id="subtitle" rows="2"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    placeholder="Additional text to display on the banner">{{ old('subtitle', $banner->subtitle) }}</textarea>
                @error('subtitle')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Link URL -->
                <div>
                    <label for="link_url" class="block text-sm font-medium text-slate-700 mb-2">Link URL (Optional)</label>
                    <input type="url" name="link_url" id="link_url" value="{{ old('link_url', $banner->link_url) }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        placeholder="https://example.com/page">
                    @error('link_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link Text -->
                <div>
                    <label for="link_text" class="block text-sm font-medium text-slate-700 mb-2">Button Text (Optional)</label>
                    <input type="text" name="link_text" id="link_text" value="{{ old('link_text', $banner->link_text) }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        placeholder="e.g., Shop Now">
                    @error('link_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Start Date -->
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-slate-700 mb-2">Start Date (Optional)</label>
                    <input type="datetime-local" name="starts_at" id="starts_at" 
                        value="{{ old('starts_at', $banner->starts_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-slate-500">Leave empty to start immediately</p>
                    @error('starts_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="ends_at" class="block text-sm font-medium text-slate-700 mb-2">End Date (Optional)</label>
                    <input type="datetime-local" name="ends_at" id="ends_at" 
                        value="{{ old('ends_at', $banner->ends_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-slate-500">Leave empty to display indefinitely</p>
                    @error('ends_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Active Toggle -->
            <div class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" 
                    class="w-4 h-4 text-amber-600 border-slate-300 rounded focus:ring-amber-500"
                    {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 text-sm text-slate-700">Active - Display this banner on the homepage</label>
            </div>

            <!-- Submit -->
            <div class="flex justify-end pt-6 border-t border-slate-100">
                <button type="submit" 
                    class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-medium rounded-lg transition-all shadow-sm">
                    Update Banner
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('image-preview').querySelector('img').src = e.target.result;
                    document.getElementById('upload-icon').classList.add('hidden');
                    document.getElementById('current-image').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
</x-layouts.admin>
