<x-layouts.admin>
    <x-slot name="title">Assign {{ $attribute->name }} to Categories</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.attributes.index') }}" class="p-2 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Assign to Categories</h1>
                <p class="text-slate-600">{{ $attribute->name }} ({{ $attribute->getTypeLabel() }})</p>
            </div>
        </div>

        <form action="{{ route('admin.attributes.update-categories', $attribute) }}" method="POST" 
              class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            @csrf

            <p class="text-sm text-slate-600 mb-4">Select categories that will use this attribute:</p>

            <div class="space-y-3">
                @foreach($categories as $category)
                    <label class="flex items-center gap-4 p-4 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition-colors">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            {{ in_array($category->id, $assignedIds) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                        <div class="flex-1">
                            <span class="font-medium text-slate-900">{{ $category->name }}</span>
                            @if($category->description)
                                <p class="text-xs text-slate-500">{{ Str::limit($category->description, 60) }}</p>
                            @endif
                        </div>
                        <span class="text-xs text-slate-400">{{ $category->cars_count }} cars</span>
                    </label>
                @endforeach
            </div>

            @if($categories->isEmpty())
                <p class="text-center text-slate-500 py-6">No categories found.</p>
            @endif

            <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-end gap-4">
                <a href="{{ route('admin.attributes.index') }}" class="px-4 py-2 text-slate-600 hover:text-slate-900">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                    Save Category Assignments
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
