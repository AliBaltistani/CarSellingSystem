<x-layouts.admin title="Banners">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Banner Management</h1>
            <p class="text-slate-600">Manage homepage banner slider</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" 
            class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-medium rounded-lg transition-all shadow-sm">
            Add New Banner
        </a>
    </div>

    @if($banners->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <div class="w-16 h-16 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-slate-900 mb-2">No Banners Yet</h3>
            <p class="text-slate-600 mb-6">Create your first banner to display on the homepage slider.</p>
            <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Banner
            </a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
        <table class="w-full min-w-[600px]">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Order</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Banner</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Title</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Schedule</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="banners-list">
                    @foreach($banners as $banner)
                        <tr class="hover:bg-slate-50" data-id="{{ $banner->id }}">
                            <td class="px-6 py-4">
                                <span class="cursor-move text-slate-400 hover:text-slate-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                    </svg>
                                </span>
                                <span class="ml-2 text-sm text-slate-600">{{ $banner->order }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" 
                                    class="w-24 h-14 object-cover rounded-lg shadow-sm">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-900">{{ $banner->title ?? '(No title)' }}</div>
                                @if($banner->subtitle)
                                    <div class="text-sm text-slate-500">{{ Str::limit($banner->subtitle, 40) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.banners.toggle-active', $banner) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors
                                        {{ $banner->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                @if($banner->starts_at || $banner->ends_at)
                                    <div>
                                        @if($banner->starts_at)
                                            From: {{ $banner->starts_at->format('M d, Y') }}
                                        @endif
                                    </div>
                                    <div>
                                        @if($banner->ends_at)
                                            Until: {{ $banner->ends_at->format('M d, Y') }}
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400">Always visible</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.banners.edit', $banner) }}" 
                                        class="p-2 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" 
                                        onsubmit="return confirm('Are you sure you want to delete this banner?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
    @endif
</x-layouts.admin>
