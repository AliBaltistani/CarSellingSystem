<x-layouts.admin :title="$page->exists ? 'Edit Page' : 'Add Page'">
    <div class="max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Pages
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h1 class="text-xl font-bold text-slate-900 mb-6">
                {{ $page->exists ? 'Edit Page' : 'Add New Page' }}
            </h1>

            <form action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}" 
                method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if($page->exists)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Page Title *</label>
                        <input type="text" id="title" name="title" 
                            value="{{ old('title', $page->title) }}" required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-slate-700 mb-1">Slug</label>
                        <input type="text" id="slug" name="slug" 
                            value="{{ old('slug', $page->slug) }}" placeholder="auto-generated-from-title"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent font-mono text-sm">
                        <p class="mt-1 text-xs text-slate-500">Leave empty to auto-generate from title</p>
                    </div>
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="block text-sm font-medium text-slate-700 mb-1">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" rows="2" placeholder="Brief summary of the page..."
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">{{ old('excerpt', $page->excerpt) }}</textarea>
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-slate-700 mb-1">Content *</label>
                    <textarea id="content" name="content" rows="15" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent font-mono text-sm">{{ old('content', $page->content) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">You can use HTML for formatting</p>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Template -->
                    <div>
                        <label for="template" class="block text-sm font-medium text-slate-700 mb-1">Template *</label>
                        <select id="template" name="template" required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            @foreach($templates as $key => $label)
                                <option value="{{ $key }}" {{ old('template', $page->template) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Featured Image -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Featured Image</label>
                        @if($page->featured_image)
                            <div class="mb-2">
                                <img src="{{ $page->featured_image_url }}" alt="Current image" class="h-20 rounded">
                            </div>
                        @endif
                        <input type="file" name="featured_image" accept="image/*"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    </div>
                </div>

                <!-- Navigation Settings -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-medium text-slate-700 mb-4">Navigation Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="show_in_header" value="1" 
                                    {{ old('show_in_header', $page->show_in_header) ? 'checked' : '' }}
                                    class="w-4 h-4 text-amber-500 border-slate-300 rounded focus:ring-amber-500">
                                <span class="ml-2 text-sm text-slate-700">Show in Header</span>
                            </label>
                            <input type="number" name="header_order" value="{{ old('header_order', $page->header_order ?? 0) }}" 
                                placeholder="Order" min="0"
                                class="w-20 px-3 py-1 border border-slate-300 rounded text-sm">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="show_in_footer" value="1" 
                                    {{ old('show_in_footer', $page->show_in_footer) ? 'checked' : '' }}
                                    class="w-4 h-4 text-amber-500 border-slate-300 rounded focus:ring-amber-500">
                                <span class="ml-2 text-sm text-slate-700">Show in Footer</span>
                            </label>
                            <input type="number" name="footer_order" value="{{ old('footer_order', $page->footer_order ?? 0) }}" 
                                placeholder="Order" min="0"
                                class="w-20 px-3 py-1 border border-slate-300 rounded text-sm">
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="border-t border-slate-200 pt-6">
                    <h3 class="text-sm font-medium text-slate-700 mb-4">SEO Settings</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-slate-700 mb-1">Meta Title</label>
                            <input type="text" id="meta_title" name="meta_title" 
                                value="{{ old('meta_title', $page->meta_title) }}" placeholder="Defaults to page title"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-slate-700 mb-1">Meta Description</label>
                            <textarea id="meta_description" name="meta_description" rows="2" placeholder="Brief description for search engines..."
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">{{ old('meta_description', $page->meta_description) }}</textarea>
                        </div>
                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-slate-700 mb-1">Meta Keywords</label>
                            <input type="text" id="meta_keywords" name="meta_keywords" 
                                value="{{ old('meta_keywords', $page->meta_keywords) }}" placeholder="keyword1, keyword2, keyword3"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="border-t border-slate-200 pt-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" 
                            {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-amber-500 border-slate-300 rounded focus:ring-amber-500">
                        <span class="ml-2 text-sm text-slate-700">Active (visible on website)</span>
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('admin.pages.index') }}" 
                        class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-medium rounded-lg transition-all shadow-sm">
                        {{ $page->exists ? 'Update Page' : 'Create Page' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
