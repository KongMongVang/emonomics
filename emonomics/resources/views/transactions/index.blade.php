<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transactions</h2>
            <a href="{{ route('transactions.create') }}" class="px-4 py-2 bg-black text-white rounded">
                + Add Transaction
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow rounded">
            <table class="w-full text-left">
                <thead class="border-b">
                    <tr>
                        <th class="p-3">Date</th>
                        <th class="p-3">Description</th>
                        <th class="p-3">Amount</th>
                        <th class="p-3">Emotion</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        <tr class="border-b">
                            <td class="p-3">{{ \Carbon\Carbon::parse($t->date)->format('Y-m-d') }}</td>
                            <td class="p-3">{{ $t->description ?? '—' }}</td>
                            <td class="p-3 font-semibold">
                                @if($t->amount < 0)
                                    <span class="text-red-600">- ${{ number_format(abs($t->amount), 0) }}</span>
                                @else
                                    <span class="text-green-600">+ ${{ number_format($t->amount, 0) }}</span>
                                @endif
                            </td>
                            <td class="p-3">{{ $t->emotion_label ?? ($emotionOptions[$t->emotion] ?? 'Unknown') }}</td>
                            <td class="p-3 flex gap-2">
                                <a class="underline" href="{{ route('transactions.edit', $t->transaction_id) }}">Edit</a>

                                <form method="POST" action="{{ route('transactions.destroy', $t->transaction_id) }}"
                                    onsubmit="return confirm('Delete this transaction?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="underline text-red-600" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-3" colspan="5">No transactions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>