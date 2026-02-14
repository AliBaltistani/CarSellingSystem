<x-layouts.admin>
    <x-slot name="title">Contact Messages</x-slot>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Contact Messages</h1>
            <p class="text-sm text-slate-500 mt-1">Manage messages from the contact form</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('admin.contact-messages.index') }}"
           class="bg-white rounded-xl p-4 border {{ !request('status') ? 'border-amber-300 ring-2 ring-amber-100' : 'border-slate-200' }} hover:shadow-md transition-all">
            <div class="text-2xl font-bold text-slate-900">{{ $counts['all'] }}</div>
            <div class="text-sm text-slate-500">All Messages</div>
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'new']) }}"
           class="bg-white rounded-xl p-4 border {{ request('status') === 'new' ? 'border-blue-300 ring-2 ring-blue-100' : 'border-slate-200' }} hover:shadow-md transition-all">
            <div class="text-2xl font-bold text-blue-600">{{ $counts['new'] }}</div>
            <div class="text-sm text-slate-500">New</div>
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}"
           class="bg-white rounded-xl p-4 border {{ request('status') === 'read' ? 'border-yellow-300 ring-2 ring-yellow-100' : 'border-slate-200' }} hover:shadow-md transition-all">
            <div class="text-2xl font-bold text-yellow-600">{{ $counts['read'] }}</div>
            <div class="text-sm text-slate-500">Read</div>
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'replied']) }}"
           class="bg-white rounded-xl p-4 border {{ request('status') === 'replied' ? 'border-green-300 ring-2 ring-green-100' : 'border-slate-200' }} hover:shadow-md transition-all">
            <div class="text-2xl font-bold text-green-600">{{ $counts['replied'] }}</div>
            <div class="text-sm text-slate-500">Replied</div>
        </a>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="flex gap-3">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, subject..."
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-medium transition-colors">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.contact-messages.index', request('status') ? ['status' => request('status')] : []) }}"
                   class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Messages Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        @if($messages->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Sender</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($messages as $msg)
                            <tr class="hover:bg-slate-50 transition-colors {{ $msg->status === 'new' ? 'bg-blue-50/30' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                            {{ strtoupper(substr($msg->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-semibold text-slate-900 truncate {{ $msg->status === 'new' ? 'font-bold' : '' }}">
                                                {{ $msg->name }}
                                            </div>
                                            <div class="text-sm text-slate-500 truncate">{{ $msg->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-900 truncate max-w-xs {{ $msg->status === 'new' ? 'font-semibold' : '' }}">
                                        {{ $msg->subject }}
                                    </div>
                                    <div class="text-xs text-slate-400 truncate max-w-xs mt-0.5">
                                        {{ Str::limit($msg->message, 60) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {!! $msg->status_badge !!}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">
                                    {{ $msg->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.contact-messages.show', $msg) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg text-xs font-medium transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('admin.contact-messages.destroy', $msg) }}"
                                              onsubmit="return confirm('Delete this message permanently?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg text-xs font-medium transition-colors">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($messages->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $messages->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-slate-700 mb-1">No messages found</h3>
                <p class="text-sm text-slate-500">Contact form submissions will appear here.</p>
            </div>
        @endif
    </div>
</x-layouts.admin>
