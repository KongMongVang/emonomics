<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Emotions
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-6">Emotions</h2>
                <form method="POST" action="{{ route('admin.emotions.store') }}" class="flex gap-2 items-center mb-6">
                    @csrf
                    <input type="text" name="name" class="border rounded px-3 py-2 w-64" placeholder="Emotion name" required>
                    <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-900">Add</button>
                </form>
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 text-gray-700">Name</th>
                            <th class="py-2 px-4 text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emotions as $emotion)
                            <tr>
                                <form method="POST" action="{{ route('admin.emotions.edit', $emotion->id) }}" class="contents">
                                    @csrf
                                    @method('PUT')
                                    <td class="py-2 px-4">
                                        <input type="text" name="name" value="{{ $emotion->name }}" class="border rounded px-2 py-1 w-40" required />
                                    </td>
                                    <td class="py-2 px-4">
                                        <button type="submit" class="text-blue-600 hover:underline mr-2">Save</button>
                                </form>
                                <form action="{{ route('admin.emotions.delete', $emotion->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                                    </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-2 px-4 text-center text-gray-400">No emotions found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>