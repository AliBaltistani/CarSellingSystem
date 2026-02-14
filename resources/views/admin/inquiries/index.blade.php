<x-layouts.admin title="Inquiries">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Inquiries</h1>
            <p class="text-slate-600">Manage customer inquiries</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.inquiries.index') }}" method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search inquiries..."
                class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
            <select name="status" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                <option value="">All Status</option>
                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg">
                Filter
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full min-w-[600px]">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">From</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Car</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Message</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Date</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($inquiries ?? [] as $inquiry)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-slate-900">{{ $inquiry->name }}</p>
                                <p class="text-sm text-slate-500">{{ $inquiry->email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ Str::limit($inquiry->car->title ?? '-', 25) }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ Str::limit($inquiry->message, 40) }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.inquiries.update-status', $inquiry) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                    class="px-3 py-1 text-sm rounded-full border-0 focus:ring-2 focus:ring-amber-500
                                        {{ $inquiry->status === 'new' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $inquiry->status === 'contacted' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $inquiry->status === 'closed' ? 'bg-slate-100 text-slate-600' : '' }}">
                                    <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="contacted" {{ $inquiry->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="closed" {{ $inquiry->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $inquiry->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="text-amber-600 hover:text-amber-700 mr-3">View</a>
                            <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" class="inline" onsubmit="return confirm('Delete this inquiry?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">No inquiries found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @if(isset($inquiries) && $inquiries->hasPages())
        <div class="mt-6">
            {{ $inquiries->withQueryString()->links() }}
        </div>
    @endif
</x-layouts.admin>
