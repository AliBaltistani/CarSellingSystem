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
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">No categories found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
