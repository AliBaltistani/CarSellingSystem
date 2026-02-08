<x-layouts.admin>
    <x-slot name="title">{{ $option ? 'Edit' : 'Add' }} Dropdown Option</x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('admin.dropdown-options.index') }}" class="text-slate-500 hover:text-slate-700 flex items-center gap-1 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Dropdown Options
            </a>
            <h1 class="text-2xl font-bold text-slate-900 mt-2">{{ $option ? 'Edit' : 'Add New' }} Dropdown Option</h1>
        </div>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $option ? route('admin.dropdown-options.update', $option) : route('admin.dropdown-options.store') }}" 
              method="POST" 
              class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-6">
            @csrf
            @if($option)
                @method('PUT')
            @endif

            <!-- Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-slate-700 mb-1">Type *</label>
                <select name="type" id="type" required
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <option value="">Select Type</option>
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}" {{ old('type', $option?->type) === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-slate-500">The category this option belongs to</p>
            </div>

            <!-- Label -->
            <div>
                <label for="label" class="block text-sm font-medium text-slate-700 mb-1">Label *</label>
                <input type="text" name="label" id="label" required
                    value="{{ old('label', $option?->label) }}"
                    placeholder="e.g., Toyota, Automatic, White"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                <p class="mt-1 text-xs text-slate-500">Display name shown to users</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-slate-700 mb-1">Slug</label>
                    <input type="text" name="slug" id="slug"
                        value="{{ old('slug', $option?->slug) }}"
                        placeholder="Auto-generated if empty"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-slate-500">URL-friendly identifier</p>
                </div>

                <!-- Value -->
                <div>
                    <label for="value" class="block text-sm font-medium text-slate-700 mb-1">Value</label>
                    <input type="text" name="value" id="value"
                        value="{{ old('value', $option?->value) }}"
                        placeholder="Defaults to slug"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-slate-500">Stored value in database</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-slate-700 mb-1">Icon</label>
                    <input type="text" name="icon" id="icon"
                        value="{{ old('icon', $option?->icon) }}"
                        placeholder="e.g., 🚗 or SVG path"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-slate-500">Emoji or icon identifier</p>
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-slate-700 mb-1">Color</label>
                    <div class="flex gap-2">
                        <input type="color" name="color_picker" id="color_picker"
                            value="{{ old('color', $option?->color) ?? '#000000' }}"
                            class="w-12 h-10 p-1 border border-slate-300 rounded cursor-pointer"
                            onchange="document.getElementById('color').value = this.value">
                        <input type="text" name="color" id="color"
                            value="{{ old('color', $option?->color) }}"
                            placeholder="#FFFFFF"
                            class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            onchange="document.getElementById('color_picker').value = this.value || '#000000'">
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Hex color code (used for color options)</p>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="2"
                    placeholder="Optional description or tooltip text"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">{{ old('description', $option?->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-medium text-slate-700 mb-1">Display Order</label>
                    <input type="number" name="order" id="order" min="0"
                        value="{{ old('order', $option?->order ?? 0) }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-slate-500">Lower numbers appear first</p>
                </div>

                <!-- Active -->
                <div class="flex items-center pt-7">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                            {{ old('is_active', $option?->is_active ?? true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                        <span class="ml-3 text-sm font-medium text-slate-700">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('admin.dropdown-options.index') }}" 
                   class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-lg transition-all shadow-lg">
                    {{ $option ? 'Update' : 'Create' }} Option
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
