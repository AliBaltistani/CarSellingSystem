<x-layouts.admin title="Offers">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Offers Management</h1>
            <p class="text-slate-600">Manage service packages and special offers</p>
        </div>
        <a href="{{ route('admin.offers.create') }}" 
            class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-medium rounded-lg transition-all shadow-sm">
            Add New Offer
        </a>
    </div>

    @if($offers->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <div class="w-16 h-16 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-slate-900 mb-2">No Offers Yet</h3>
            <p class="text-slate-600 mb-6">Create service packages and special offers to display on the homepage.</p>
            <a href="{{ route('admin.offers.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors">
                Add Offer
            </a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
        <table class="w-full min-w-[600px]">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Offer</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Badge</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Pricing</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Expires</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($offers as $offer)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($offer->icon_url)
                                        <img src="{{ $offer->icon_url }}" alt="{{ $offer->title }}" class="w-10 h-10 object-contain mr-3">
                                    @else
                                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-slate-900">{{ $offer->title }}</div>
                                        @if($offer->is_featured)
                                            <span class="text-xs text-amber-600">Featured</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($offer->badge)
                                    <span class="inline-flex px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">
                                        {{ $offer->badge }}
                                    </span>
                                @else
                                    <span class="text-sm text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($offer->price_from)
                                    <div class="text-sm text-slate-900">From: AED {{ number_format($offer->price_from) }}</div>
                                @endif
                                @if($offer->price_upgrade)
                                    <div class="text-xs text-slate-500">Upgrade: AED {{ number_format($offer->price_upgrade) }}</div>
                                @endif
                                @if(!$offer->price_from && !$offer->price_upgrade)
                                    <span class="text-sm text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($offer->expires_at)
                                    <span class="text-sm {{ $offer->is_expired ? 'text-red-600' : 'text-slate-600' }}">
                                        {{ $offer->expires_at->format('M d, Y') }}
                                        @if($offer->is_expired) (Expired) @endif
                                    </span>
                                @else
                                    <span class="text-sm text-slate-400">Never</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.offers.toggle-active', $offer) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors
                                        {{ $offer->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        {{ $offer->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.offers.edit', $offer) }}" 
                                        class="p-2 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.offers.destroy', $offer) }}" method="POST" 
                                        onsubmit="return confirm('Are you sure you want to delete this offer?')">
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
            {{ $offers->links() }}
        </div>
    @endif
</x-layouts.admin>
