<x-layouts.admin title="Orders">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Orders Management</h1>
            <p class="text-slate-600">View and manage customer orders</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order #, name, or email..."
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm">
            </div>
            <select name="status" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm">
                <option value="">All Status</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white font-medium rounded-lg hover:bg-slate-800 transition-colors text-sm">
                Filter
            </button>
        </form>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <div class="w-16 h-16 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-slate-900 mb-2">No Orders Yet</h3>
            <p class="text-slate-600">Orders will appear here when customers purchase offers.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
        <table class="w-full min-w-[600px]">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Order</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Customer</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Package</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Amount</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Date</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($orders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono font-medium text-slate-900">{{ $order->order_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-900">{{ $order->customer_name }}</div>
                                <div class="text-xs text-slate-500">{{ $order->customer_email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-700">{{ $order->offer->title ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-slate-900">{{ $order->formatted_amount }}</span>
                            </td>
                            <td class="px-6 py-4">
                                {!! $order->status_badge !!}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600">{{ $order->created_at->format('M d, Y') }}</span>
                                <div class="text-xs text-slate-400">{{ $order->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                    class="p-2 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors inline-block">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
        
        <div class="mt-6">
            {{ $orders->withQueryString()->links() }}
        </div>
    @endif
</x-layouts.admin>
