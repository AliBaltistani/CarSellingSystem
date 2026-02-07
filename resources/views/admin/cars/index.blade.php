<x-layouts.admin title="Cars">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Car Listings</h1>
            <p class="text-slate-600">Manage all car listings</p>
        </div>
        <a href="{{ route('admin.cars.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg">
            + Add New Car
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.cars.index') }}" method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cars..."
                class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
            <select name="status" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                <option value="">All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
            </select>
            <select name="category" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                <option value="">All Categories</option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg">
                Filter
            </button>
            <a href="{{ route('admin.cars.index') }}" class="px-4 py-2 text-slate-600 hover:text-slate-800">Clear</a>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Car</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Category</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Price</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Views</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($cars ?? [] as $car)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img src="{{ $car->main_image }}" alt="" class="w-16 h-12 object-cover rounded-lg mr-4">
                                <div>
                                    <p class="font-medium text-slate-900">{{ Str::limit($car->title, 35) }}</p>
                                    <p class="text-sm text-slate-500">{{ $car->year }} • {{ $car->make }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $car->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 font-semibold text-amber-600">{{ $car->formatted_price }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @if($car->is_published)
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-full">Published</span>
                                @else
                                    <span class="px-2 py-1 bg-slate-100 text-slate-600 text-xs rounded-full">Draft</span>
                                @endif
                                @if($car->is_featured)
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-full">Featured</span>
                                @endif
                                @if($car->is_sold)
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Sold</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ number_format($car->views_count) }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <form action="{{ route('admin.cars.toggle-featured', $car) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Toggle Featured" class="p-2 hover:bg-amber-50 rounded-lg text-amber-600">
                                        <svg class="w-5 h-5" fill="{{ $car->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </button>
                                </form>
                                <a href="{{ route('admin.cars.edit', $car) }}" class="p-2 hover:bg-slate-100 rounded-lg text-slate-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.cars.destroy', $car) }}" method="POST" class="inline" onsubmit="return confirm('Delete this car?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 rounded-lg text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">No cars found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($cars) && $cars->hasPages())
        <div class="mt-6">
            {{ $cars->withQueryString()->links() }}
        </div>
    @endif
</x-layouts.admin>
