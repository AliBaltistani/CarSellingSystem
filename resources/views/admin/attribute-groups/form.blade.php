<x-layouts.admin>
    <x-slot name="title">{{ $group->exists ? 'Edit' : 'Create' }} Attribute Group</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.attribute-groups.index') }}" class="p-2 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">{{ $group->exists ? 'Edit' : 'Create' }} Attribute Group</h1>
        </div>

        <form action="{{ $group->exists ? route('admin.attribute-groups.update', $group) : route('admin.attribute-groups.store') }}" 
              method="POST" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            @csrf
            @if($group->exists) @method('PUT') @endif

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Group Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $group->name) }}" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        placeholder="e.g., Engine, Interior, Safety">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-slate-700 mb-2">Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $group->slug) }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent font-mono"
                        placeholder="Auto-generated if left empty">
                    @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                    <x-forms.rich-editor name="description" :value="$group->description" height="100px" />
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-slate-700 mb-2">Icon (Emoji or Class)</label>
                    <input type="text" id="icon" name="icon" value="{{ old('icon', $group->icon) }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        placeholder="e.g., ⚙️ or heroicon-engine">
                    @error('icon') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-medium text-slate-700 mb-2">Display Order</label>
                    <input type="number" id="order" name="order" value="{{ old('order', $group->order ?? 0) }}"
                        class="w-32 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Active Toggle -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $group->is_active ?? true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                    <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-end gap-4">
                <a href="{{ route('admin.attribute-groups.index') }}" class="px-4 py-2 text-slate-600 hover:text-slate-900">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                    {{ $group->exists ? 'Update' : 'Create' }} Group
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
