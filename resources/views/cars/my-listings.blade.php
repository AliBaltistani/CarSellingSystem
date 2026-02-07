<x-layouts.public>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">My Listings</h1>
                <p class="text-slate-600 mt-1">Manage your car listings</p>
            </div>
            <a href="{{ route('cars.create') }}" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-lg transition-all shadow-lg shadow-orange-500/25">
                + Add New Car
            </a>
        </div>

        @if($cars->count() > 0)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Car</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Price</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Views</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Inquiries</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($cars as $car)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="{{ $car->main_image }}" alt="{{ $car->title }}" class="w-16 h-12 object-cover rounded-lg mr-4">
                                        <div>
                                            <a href="{{ route('cars.show', $car) }}" class="font-medium text-slate-900 hover:text-amber-600">
                                                {{ Str::limit($car->title, 30) }}
                                            </a>
                                            <p class="text-sm text-slate-500">{{ $car->year }} • {{ $car->make }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-amber-600">{{ $car->formatted_price }}</td>
                                <td class="px-6 py-4">
                                    @if($car->is_published)
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full">Published</span>
                                    @else
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 text-sm font-medium rounded-full">Draft</span>
                                    @endif
                                    @if($car->is_sold)
                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full ml-1">Sold</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ number_format($car->views_count) }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ number_format($car->inquiries_count) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('cars.edit', $car) }}" class="text-amber-600 hover:text-amber-700 font-medium mr-4">Edit</a>
                                    <form action="{{ route('cars.destroy', $car) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $cars->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-xl">
                <svg class="w-16 h-16 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-slate-900">No listings yet</h3>
                <p class="mt-2 text-slate-600">Start selling by adding your first car</p>
                <a href="{{ route('cars.create') }}" class="inline-block mt-6 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-colors">
                    + Add Your First Car
                </a>
            </div>
        @endif
    </div>
</x-layouts.public>
