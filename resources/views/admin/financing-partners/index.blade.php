<x-layouts.admin title="Financing Partners">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Financing Partners</h1>
            <p class="text-slate-600">Manage trusted financing partner logos</p>
        </div>
        <a href="{{ route('admin.financing-partners.create') }}" 
            class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-medium rounded-lg transition-all shadow-sm">
            Add New Partner
        </a>
    </div>

    @if($partners->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <div class="w-16 h-16 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-slate-900 mb-2">No Financing Partners Yet</h3>
            <p class="text-slate-600 mb-6">Add bank and finance partner logos to display on the homepage.</p>
            <a href="{{ route('admin.financing-partners.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors">
                Add Partner
            </a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
        <table class="w-full min-w-[600px]">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Order</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Logo</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Website</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($partners as $partner)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $partner->order }}</td>
                            <td class="px-6 py-4">
                                <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" 
                                    class="h-10 max-w-24 object-contain">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-900">{{ $partner->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($partner->website_url)
                                    <a href="{{ $partner->website_url }}" target="_blank" class="text-sm text-amber-600 hover:underline">
                                        {{ Str::limit($partner->website_url, 30) }}
                                    </a>
                                @else
                                    <span class="text-sm text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.financing-partners.toggle-active', $partner) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors
                                        {{ $partner->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        {{ $partner->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.financing-partners.edit', $partner) }}" 
                                        class="p-2 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.financing-partners.destroy', $partner) }}" method="POST" 
                                        onsubmit="return confirm('Are you sure you want to delete this partner?')">
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
        
        <div class="mt-6">
            {{ $partners->links() }}
        </div>
    @endif
</x-layouts.admin>
