<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-gray-500 text-sm mb-2">Total Users</div>
                    <div class="text-3xl font-bold text-black mb-1">{{ $totalUsers }}</div>
                </div>
                <!-- Total Transactions -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-gray-500 text-sm mb-2">Total Transactions</div>
                    <div class="text-3xl font-bold text-black mb-1">{{ $totalTransactions }}</div>
                </div>
                <!-- Suspended Accounts -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-gray-500 text-sm mb-2">Suspended Accounts</div>
                    <div class="text-3xl font-bold text-black mb-1">{{ $suspendedAccounts }}</div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Recent User Registrations Table -->
                <div class="bg-white rounded-lg shadow p-6 flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-gray-500 text-sm font-semibold">Recent User Registrations</div>
                    </div>
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 text-gray-700">User Email</th>
                                <th class="py-2 px-4 text-gray-700">Joined</th>
                                <th class="py-2 px-4 text-gray-700">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $user)
                                @if(!$user->is_admin)
                                    <tr>
                                        <td class="py-2 px-4">{{ $user->email }}</td>
                                        <td class="py-2 px-4">
                                            {{ $user->created_at ? $user->created_at->diffForHumans() : 'N/A' }}
                                        </td>
                                        <td class="py-2 px-4">
                                            <a href="{{ route('admin.users.view', ['user' => $user->user_id]) }}" class="text-black underline hover:text-gray-700 text-sm">View</a>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr><td colspan="3" class="py-2 px-4 text-center text-gray-400">No recent users</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6 w-full md:w-96 flex flex-col gap-4">
                    <div class="text-gray-500 text-sm mb-2 font-semibold">Quick Actions</div>
                    <a href="#" class="inline-block px-4 py-2 bg-black text-white rounded shadow hover:bg-gray-900">Add Mood Category</a>
                    <a href="#" class="inline-block px-4 py-2 bg-black text-white rounded shadow hover:bg-gray-900">Add Spending Category</a>
                    <a href="#" class="inline-block px-4 py-2 bg-gray-700 text-white rounded shadow hover:bg-gray-900">View Suspended Accounts</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>