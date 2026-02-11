<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Transaction
        </h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto px-4">
        <form method="POST" action="{{ route('transactions.update', $transaction->transaction_id) }}"
            class="bg-white shadow rounded p-6 space-y-4">

            @csrf
            @method('PUT')

            {{-- Date --}}
            <div>
                <x-input-label for="date" value="Date" />
                <x-text-input id="date" class="block mt-1 w-full" type="date" name="date"
                    value="{{ old('date', \Carbon\Carbon::parse($transaction->date)->format('Y-m-d')) }}" required />
                <x-input-error :messages="$errors->get('date')" class="mt-2" />
            </div>

            {{-- Description --}}
            <div>
                <x-input-label for="description" value="Description" />
                <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                    value="{{ old('description', $transaction->description) }}" />
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            {{-- Amount --}}
            <div>
                <x-input-label for="amount" value="Amount (enter positive number)" />
                <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount" min="1"
                    value="{{ old('amount', abs($transaction->amount)) }}" required />
                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
            </div>

            {{-- Type --}}
            <div>
                <x-input-label for="type_id" value="Type" />
                <select id="type_id" name="type_id" class="mt-1 w-full rounded border-gray-300" required>
                    <option value="">Select type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->type_id }}" @selected(old('type_id', $transaction->type_id) == $type->type_id)>
                            {{ $type->type_name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('type_id')" class="mt-2" />
            </div>

            {{-- Category --}}
            <div>
                <x-input-label for="category_id" value="Category" />
                <select id="category_id" name="category_id" class="mt-1 w-full rounded border-gray-300" required>
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->category_id }}" @selected(old('category_id', $transaction->category_id) == $cat->category_id)>
                            {{ $cat->category_name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            {{-- Emotion --}}
            <div>
                <x-input-label for="emotion" value="Emotion" />
                <select id="emotion" name="emotion" class="mt-1 w-full rounded border-gray-300" required>
                    <option value="">Select emotion</option>
                    @foreach($emotionOptions as $key => $label)
                        <option value="{{ $key }}" @selected(old('emotion', $transaction->emotion) == $key)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('emotion')" class="mt-2" />
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <button type="submit" class="px-4 py-2 bg-black text-white rounded">
                    Update
                </button>

                <a href="{{ route('transactions.index') }}" class="px-4 py-2 border rounded">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</x-app-layout>