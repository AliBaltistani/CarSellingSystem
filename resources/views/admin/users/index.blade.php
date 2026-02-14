<x-layouts.admin title="Users">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Users</h1>
            <p class="text-slate-600">Manage system users</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg">
            + Add User
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
                class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
            <select name="role" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                <option value="">All Roles</option>
                @foreach($roles ?? [] as $role)
                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">User</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Role</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Listings</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Joined</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users ?? [] as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center mr-3">
                                    <span class="font-medium text-slate-600">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $user->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @foreach($user->roles as $role)
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">{{ ucfirst($role->name) }}</span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $user->cars_count ?? 0 }}</td>
                        <td class="px-6 py-4">
                            @if($user->is_active)
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-full">Active</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-slate-600 hover:text-slate-800 mr-3">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-amber-600 hover:text-amber-700 mr-3">Edit</a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @if(isset($users) && $users->hasPages())
        <div class="mt-6">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</x-layouts.admin>
