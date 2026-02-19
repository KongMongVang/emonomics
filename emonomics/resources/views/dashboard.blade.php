<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Welcome back, ') }}{{ Auth::user()->first_name ?? Auth::user()->name ?? 'User' }}
            </h2>
            <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-black text-white rounded shadow 
          hover:bg-gray-900 focus:outline-none focus:ring-2 
          focus:ring-offset-2 focus:ring-black"> <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Transaction
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Spendings This Month -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-gray-500 text-sm mb-2">Total Spendings This Month</div>
                    <div class="text-3xl font-bold text-black mb-1">${{ number_format($totalSpendings, 2) }}</div>
                </div>
                <!-- Most Common Emotion -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-gray-500 text-sm mb-2">Most Common Emotion</div>
                    <div class="text-3xl font-bold text-black mb-1">
                        @if($commonEmotion)
                            {{ \App\Models\Transaction::emotions()[$commonEmotion->emotion] ?? 'Unknown' }}
                        @else
                            --
                        @endif
                    </div>
                </div>
                <!-- Total Transactions -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-gray-500 text-sm mb-2">Total Transactions</div>
                    <div class="text-3xl font-bold text-black mb-1">{{ $totalTransactions }}</div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Recent Transactions Table -->
                <div class="bg-white rounded-lg shadow p-6 flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-gray-500 text-sm font-semibold">Recent Transactions</div>
                    </div>
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 text-gray-700">Date</th>
                                <th class="py-2 px-4 text-gray-700">Description</th>
                                <th class="py-2 px-4 text-gray-700">Amount</th>
                                <th class="py-2 px-4 text-gray-700">Emotion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $txn)
                                <tr>
                                    <td class="py-2 px-4">{{ \Carbon\Carbon::parse($txn->date)->format('Y-m-d') }}</td>
                                    <td class="py-2 px-4">{{ $txn->description }}</td>
                                    <td class="py-2 px-4">${{ number_format(abs($txn->amount), 2) }}</td>
                                    <td class="py-2 px-4">
                                        {{ \App\Models\Transaction::emotions()[$txn->emotion] ?? 'Unknown' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-2 px-4 text-center text-gray-400">No transactions</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="flex justify-center mt-6">
                        <a href="{{ route('transactions.index') }}"
                            class="text-black underline hover:text-gray-700 text-sm">View all</a>
                    </div>
                </div>
                <!-- Spending by Mood (Progress Bar) -->
                <div class="bg-white rounded-lg shadow p-6 w-full md:w-96">
                    <div class="text-gray-500 text-sm mb-4 font-semibold">Spending by Mood</div>
                    <div class="space-y-4">
                        @php
                            $totalSpendingsForMood = $totalSpendings ?: 1;
                        @endphp
                        @foreach($spendingByMood as $mood)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-gray-700">
                                        {{ \App\Models\Transaction::emotions()[$mood->emotion] ?? 'Unknown' }}
                                    </span>
                                    <span class="text-gray-700">${{ number_format(abs($mood->total), 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-green-400 h-3 rounded-full transition-all duration-300"
                                        style="width: {{ $totalSpendingsForMood > 0 ? round((abs($mood->total) / $totalSpendingsForMood) * 100) : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if($spendingByMood->isEmpty())
                            <div class="text-gray-400">No spending data</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>