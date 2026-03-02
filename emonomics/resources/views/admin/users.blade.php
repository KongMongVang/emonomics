<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Users
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-gray-500 text-sm font-semibold mb-4">All Users</div>
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 text-gray-700">User ID</th>
                            <th class="py-2 px-4 text-gray-700">Name</th>
                            <th class="py-2 px-4 text-gray-700">Email</th>
                            <th class="py-2 px-4 text-gray-700">Joined</th>
                            <th class="py-2 px-4 text-gray-700">Status</th>
                            <th class="py-2 px-4 text-gray-700">Total Transactions</th>
                            <th class="py-2 px-4 text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            @if(!$user->is_admin)
                                <tr>
                                    <td class="py-2 px-4">{{ $user->user_id }}</td>
                                    <td class="py-2 px-4">{{ $user->name }} </td>
                                    <td class="py-2 px-4">{{ $user->email }}</td>
                                    <td class="py-2 px-4">{{ $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A' }}
                                    </td>
                                    <td class="py-2 px-4">
                                        @if($user->is_suspended)
                                            <span class="text-red-600 font-semibold">Suspended</span>
                                        @else
                                            <span class="text-green-600 font-semibold">Active</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4">{{ $user->transactions_count }}</td>
                                    <td class="py-2 px-4 flex gap-2">
                                        <a href="{{ route('admin.users.view', $user) }}"
                                            class="text-black underline hover:text-gray-700 text-sm">View</a>
                                        <form action="{{ route('admin.users.suspend', $user) }}" method="POST"
                                            style="display:inline">
                                            @csrf
                                            <button type="submit"
                                                class="text-{{ !$user->is_suspended ? 'yellow-600 hover:text-yellow-800' : 'green-600 hover:text-green-800' }} underline text-sm">
                                                {{ !$user->is_suspended ? 'Suspend' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.delete', $user) }}" method="POST"
                                            style="display:inline"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 underline hover:text-red-800 text-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="py-2 px-4 text-center text-gray-400">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>