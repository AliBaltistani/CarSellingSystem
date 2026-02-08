<x-layouts.admin title="Edit Category">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Edit Category</h1>
        <p class="text-slate-600">Update category details</p>
    </div>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 font-mono">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                <x-forms.rich-editor name="description" :value="$category->description" height="120px" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Icon</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}"
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
            </div>

            @if($category->image)
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Current Image</label>
                <img src="{{ $category->imageUrl }}" alt="" class="w-32 h-20 object-cover rounded-lg">
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">{{ $category->image ? 'Replace Image' : 'Image' }}</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Parent Category</label>
                <select name="parent_id" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="">None (Top Level)</option>
                    @foreach($parentCategories ?? [] as $parent)
                        @if($parent->id !== $category->id)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Display Order</label>
                <input type="number" name="order" value="{{ old('order', $category->order) }}" min="0"
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
            </div>

            <label class="flex items-center space-x-3">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                    class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                <span class="text-sm font-medium text-slate-700">Active</span>
            </label>
        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg">Update Category</button>
        </div>
    </form>
</x-layouts.admin>
