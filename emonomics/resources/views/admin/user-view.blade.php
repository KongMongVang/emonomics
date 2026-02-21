<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            User Details
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <a href="{{ route('admin.users') }}" class="inline-block mb-6 px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">&larr; Back to Users</a>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- User Info Card -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col gap-2 md:row-span-2 md:h-full" style="min-height:220px;">
                    <div class="text-lg font-bold mb-2">User Info</div>
                    <div><span class="font-semibold">Name:</span> {{ $user->first_name }} {{ $user->last_name }}</div>
                    <div><span class="font-semibold">Email:</span> {{ $user->email }}</div>
                    <div><span class="font-semibold">User ID:</span> {{ $user->user_id }}</div>
                    <div><span class="font-semibold">Joined:</span> {{ $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A' }}</div>
                    <div><span class="font-semibold">Status:</span> <span class="{{ $user->is_suspended ? 'text-red-600' : 'text-green-600' }} font-semibold">{{ $user->is_suspended ? 'Suspended' : 'Active' }}</span></div>
                    <div><span class="font-semibold">Last Logged In:</span> {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'N/A' }}</div>
                    <div class="flex gap-2 mt-4">
                        <form action="{{ route('admin.users.suspend', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded {{ !$user->is_suspended ? 'bg-yellow-500 text-white' : 'bg-green-600 text-white' }}">
                                {{ !$user->is_suspended ? 'Suspend Account' : 'Activate Account' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white">Delete Account</button>
                        </form>
                    </div>
                </div>
                <!-- Total Transactions Card -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center justify-center min-h-[140px]">
                    <div class="text-gray-500 text-sm mb-2">Total Transactions</div>
                    <div class="text-3xl font-bold text-black mb-1">{{ $user->transactions_count }}</div>
                </div>
                <!-- Total Spending Card -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center justify-center min-h-[140px]">
                    <div class="text-gray-500 text-sm mb-2">Total Spending</div>
                    <div class="text-3xl font-bold text-black mb-1">${{ number_format(abs($totalSpending), 2) }}</div>
                </div>
                <!-- Recent Activity Table -->
                <div class="md:col-span-2 bg-white rounded-lg shadow p-6 mt-0">
                    <div class="text-gray-500 text-sm font-semibold mb-4">Recent Activity</div>
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 text-gray-700">Description</th>
                                <th class="py-2 px-4 text-gray-700">Price</th>
                                <th class="py-2 px-4 text-gray-700">When</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $txn)
                                <tr>
                                    <td class="py-2 px-4">{{ $txn->description }}</td>
                                    <td class="py-2 px-4">
                                        <span class="font-mono {{ $txn->amount < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $txn->amount < 0 ? '-' : '+' }}${{ number_format(abs($txn->amount), 2) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4">{{ $txn->date ? \Carbon\Carbon::parse($txn->date)->diffForHumans() : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-2 px-4 text-center text-gray-400">No recent activity</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>