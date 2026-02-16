<x-layouts.public>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="bulkActions()">
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
            <!-- Bulk Action Bar -->
            <div x-show="selectedIds.length > 0" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="sticky top-4 z-30 mb-4 bg-slate-900 text-white rounded-xl shadow-2xl px-6 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 text-white text-sm font-bold rounded-full" x-text="selectedIds.length"></span>
                    <span class="text-sm font-medium">listing(s) selected</span>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="selectAll = false; selectedIds = []" class="px-4 py-1.5 text-sm font-medium text-slate-300 hover:text-white transition-colors">
                        Deselect All
                    </button>
                    <button @click="bulkDelete()" :disabled="deleting"
                        class="inline-flex items-center gap-2 px-4 py-1.5 bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg x-show="!deleting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <svg x-show="deleting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span x-text="deleting ? 'Deleting...' : 'Delete Selected'"></span>
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-4 text-left w-12">
                                <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                            </th>
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
                            <tr class="hover:bg-slate-50" :class="selectedIds.includes({{ $car->id }}) ? 'bg-amber-50/50' : ''" x-ref="row{{ $car->id }}">
                                <td class="px-4 py-4">
                                    <input type="checkbox" value="{{ $car->id }}" x-model.number="selectedIds"
                                        class="w-4 h-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                                </td>
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

    <script>
        function bulkActions() {
            return {
                selectedIds: [],
                selectAll: false,
                deleting: false,

                toggleAll() {
                    if (this.selectAll) {
                        this.selectedIds = @json($cars->pluck('id')->toArray());
                    } else {
                        this.selectedIds = [];
                    }
                },

                async bulkDelete() {
                    if (!confirm(`Are you sure you want to delete ${this.selectedIds.length} listing(s)? This action cannot be undone.`)) return;

                    this.deleting = true;
                    try {
                        const response = await fetch('{{ route("cars.bulk-delete") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ ids: this.selectedIds }),
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.selectedIds.forEach(id => {
                                const row = this.$refs['row' + id];
                                if (row) {
                                    row.style.transition = 'opacity 0.3s, transform 0.3s';
                                    row.style.opacity = '0';
                                    row.style.transform = 'translateX(20px)';
                                    setTimeout(() => row.remove(), 300);
                                }
                            });

                            this.selectedIds = [];
                            this.selectAll = false;

                            this.showToast(`${data.deleted} listing(s) deleted successfully!`);
                        } else {
                            alert('Failed to delete listings. Please try again.');
                        }
                    } catch (e) {
                        console.error('Bulk delete error:', e);
                        alert('An error occurred. Please try again.');
                    }
                    this.deleting = false;
                },

                showToast(message) {
                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 right-4 z-50 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl shadow-2xl max-w-sm';
                    toast.style.animation = 'slideIn 0.3s ease-out';
                    toast.innerHTML = `<div class="flex items-center gap-3"><svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><p class="text-sm font-medium">${message}</p></div>`;
                    document.body.appendChild(toast);
                    setTimeout(() => { toast.style.animation = 'fadeOut 0.3s ease-in'; setTimeout(() => toast.remove(), 300); }, 3000);
                },
            };
        }

        const toastStyles = document.createElement('style');
        toastStyles.textContent = `
            @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
            @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
        `;
        document.head.appendChild(toastStyles);
    </script>
</x-layouts.public>
