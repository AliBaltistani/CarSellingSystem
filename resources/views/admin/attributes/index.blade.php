<x-layouts.admin>
    <x-slot name="title">Attributes</x-slot>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Attributes</h1>
            <p class="text-slate-600 mt-1">Custom fields for vehicle specifications</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.attributes.import-export') }}" 
               class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Import/Export
            </a>
            <a href="{{ route('admin.attribute-groups.index') }}" 
               class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                Manage Groups
            </a>
            <a href="{{ route('admin.attributes.create') }}" 
               class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Attribute
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <form method="GET" class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
        <div class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-xs font-medium text-slate-500 mb-1">Filter by Group</label>
                <select name="group" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                    <option value="">All Groups</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ request('group') == $group->id ? 'selected' : '' }}>
                            {{ $group->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-medium text-slate-500 mb-1">Filter by Type</label>
                <select name="type" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                    <option value="">All Types</option>
                    @foreach(\App\Models\Attribute::TYPES as $value => $label)
                        <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">
                    Filter
                </button>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Group</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase">Display Options</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($attributes as $attribute)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-4">
                            <div>
                                <p class="font-medium text-slate-900">{{ $attribute->name }}</p>
                                <p class="text-xs text-slate-500 font-mono">{{ $attribute->slug }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded">
                                {{ $attribute->getTypeLabel() }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-600">
                            {{ $attribute->group?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex justify-center gap-2">
                                <span title="Show in Filters" class="w-6 h-6 rounded {{ $attribute->show_in_filters ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xs font-bold">F</span>
                                <span title="Show in Card" class="w-6 h-6 rounded {{ $attribute->show_in_card ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xs font-bold">C</span>
                                <span title="Show in Details" class="w-6 h-6 rounded {{ $attribute->show_in_details ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xs font-bold">D</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <form action="{{ route('admin.attributes.toggle-active', $attribute) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs font-medium rounded {{ $attribute->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $attribute->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.attributes.categories', $attribute) }}" 
                                   class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Assign Categories">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.attributes.edit', $attribute) }}" 
                                   class="p-2 text-slate-400 hover:text-amber-600 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.attributes.destroy', $attribute) }}" method="POST" 
                                      onsubmit="return confirm('Delete this attribute?')">
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
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            No attributes found. <a href="{{ route('admin.attributes.create') }}" class="text-amber-600 hover:underline">Create one</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $attributes->withQueryString()->links() }}
    </div>
</x-layouts.admin>
