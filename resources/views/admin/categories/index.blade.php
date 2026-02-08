<x-layouts.admin title="Categories">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Categories</h1>
            <p class="text-slate-600">Manage car categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg">
            + Add Category
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Order</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Icon/Image</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Category</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Slug</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Cars</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories ?? [] as $category)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-600">{{ $category->order }}</td>
                        <td class="px-6 py-4">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                                @if($category->image)
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-8 h-8 object-contain rounded">
                                @elseif($category->icon)
                                    <span class="text-slate-500 text-sm font-mono">{{ Str::limit($category->icon, 10) }}</span>
                                @else
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                                    </svg>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-slate-500 font-mono text-sm">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $category->cars_count ?? 0 }}</td>
                        <td class="px-6 py-4">
                            @if($category->is_active)
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-full">Active</span>
                            @else
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 text-xs rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-amber-600 hover:text-amber-700 mr-3">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">No categories found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
