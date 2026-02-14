<x-layouts.admin title="Locations">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Locations</h1>
            <p class="text-slate-600">Manage locations for car listings</p>
        </div>
        <a href="{{ route('admin.locations.create') }}" 
            class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Location
        </a>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.locations.index') }}" method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search locations..."
                class="flex-1 px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
            <button type="submit" class="px-6 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700">
                Search
            </button>
        </form>
    </div>

    <!-- Locations Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full min-w-[600px]">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">City</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">State</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Country</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Coordinates</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($locations as $location)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $location->city }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $location->state ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $location->country }}</td>
                        <td class="px-6 py-4 text-slate-500 text-sm">
                            @if($location->latitude && $location->longitude)
                                {{ number_format($location->latitude, 4) }}, {{ number_format($location->longitude, 4) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.locations.toggle-active', $location) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $location->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $location->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.locations.edit', $location) }}" 
                                    class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" 
                                    onsubmit="return confirm('Delete this location?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Delete">
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
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            No locations found. <a href="{{ route('admin.locations.create') }}" class="text-amber-600 hover:underline">Add your first location</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($locations->hasPages())
        <div class="mt-6">
            {{ $locations->links() }}
        </div>
    @endif
</x-layouts.admin>
