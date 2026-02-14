<x-layouts.admin>
    <x-slot name="title">Pages</x-slot>

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Pages</h1>
            <p class="text-slate-600 mt-1">Manage static website pages</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" 
           class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Page
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
        <div class="flex gap-4 items-end flex-wrap">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-medium text-slate-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Title or slug..."
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
            </div>
            <div class="w-40">
                <label class="block text-xs font-medium text-slate-500 mb-1">Template</label>
                <select name="template" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                    <option value="">All Templates</option>
                    @foreach(\App\Models\Page::TEMPLATES as $key => $label)
                        <option value="{{ $key }}" {{ request('template') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
                <select name="active" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                    <option value="">All</option>
                    <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">
                    Filter
                </button>
                <a href="{{ route('admin.pages.index') }}" class="px-4 py-2 text-slate-500 hover:text-slate-700">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full min-w-[600px]">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Template</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase">Navigation</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($pages as $page)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-4">
                            <div>
                                <p class="font-medium text-slate-900">{{ $page->title }}</p>
                                <p class="text-xs text-slate-500 font-mono">/page/{{ $page->slug }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded">
                                {{ $page->template_label }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex justify-center gap-2">
                                <span title="Show in Header" class="w-6 h-6 rounded {{ $page->show_in_header ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xs font-bold">H</span>
                                <span title="Show in Footer" class="w-6 h-6 rounded {{ $page->show_in_footer ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xs font-bold">F</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <form action="{{ route('admin.pages.toggle-active', $page) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs font-medium rounded {{ $page->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $page->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('page.show', $page->slug) }}" target="_blank"
                                   class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.pages.edit', $page) }}" 
                                   class="p-2 text-slate-400 hover:text-amber-600 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" 
                                      onsubmit="return confirm('Delete this page?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Delete">
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
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            No pages found. <a href="{{ route('admin.pages.create') }}" class="text-amber-600 hover:underline">Create one</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $pages->withQueryString()->links() }}
    </div>
</x-layouts.admin>
