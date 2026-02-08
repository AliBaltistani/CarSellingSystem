<x-layouts.admin>
    <x-slot name="title">Dropdown Options</x-slot>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dropdown Options</h1>
            <p class="text-slate-600 mt-1">Manage all dropdown values used across the frontend</p>
        </div>
        <a href="{{ route('admin.dropdown-options.create') }}" 
           class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-lg transition-all flex items-center gap-2 shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Option
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Type Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
        <form method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-xs font-medium text-slate-500 mb-1">Filter by Type</label>
                <select name="type" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <option value="">All Types</option>
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}" {{ $selectedType === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                    Filter
                </button>
            </div>
            @if($selectedType)
            <div>
                <a href="{{ route('admin.dropdown-options.index') }}" class="px-4 py-2 text-slate-600 hover:text-slate-900">
                    Clear
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Options Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Label</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Value</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase">Color</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase">Order</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($options as $option)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                @if($option->icon)
                                    <span class="text-xl">{{ $option->icon }}</span>
                                @endif
                                <div>
                                    <p class="font-medium text-slate-900">{{ $option->label }}</p>
                                    <p class="text-xs text-slate-400">{{ $option->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded">
                                {{ $option->type_label }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-600">
                            {{ $option->value }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($option->color)
                                <div class="inline-flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full border border-slate-200 shadow-sm" 
                                          style="background-color: {{ $option->color }}"></span>
                                    <span class="text-xs text-slate-500">{{ $option->color }}</span>
                                </div>
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center text-sm text-slate-600">
                            {{ $option->order }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <form action="{{ route('admin.dropdown-options.toggle-active', $option) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $option->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $option->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.dropdown-options.edit', $option) }}" 
                                   class="p-2 text-slate-400 hover:text-amber-600 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.dropdown-options.destroy', $option) }}" method="POST" 
                                      onsubmit="return confirm('Delete this option?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-slate-500">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <p>No dropdown options found.</p>
                            <a href="{{ route('admin.dropdown-options.create') }}" class="text-amber-600 hover:underline mt-2 inline-block">
                                Add your first option
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($options->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                {{ $options->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
