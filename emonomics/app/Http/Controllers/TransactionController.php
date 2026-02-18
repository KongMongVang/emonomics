<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Type;
use App\Models\Category;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->user_id;

        $transactions = Transaction::with(['type', 'category'])
            ->where('user_id', $userId)
            ->orderByDesc('date')
            ->get();

        return view('transactions.index', [
            'transactions' => $transactions,
            'emotionOptions' => Transaction::emotions(),
        ]);
    }

    public function create(Request $request)
    {
        // Default to first type, or null
        $types = Type::orderBy('type_name')->get();
        $selectedTypeId = old('type_id') ?? ($types->first()->type_id ?? null);
        $categories = $selectedTypeId
            ? Category::where('type_id', $selectedTypeId)->orderBy('category_name')->get()
            : collect();

        return view('transactions.create', [
            'types' => $types,
            'categories' => $categories,
            'emotionOptions' => Transaction::emotions(),
        ]);
    }

    public function store(Request $request)
    {
        $userId = $request->user()->user_id;

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:1'],
            'type_id' => ['required', 'integer', 'exists:types,type_id'],
            'category_id' => ['required', 'integer', 'exists:categories,category_id'],
            'emotion' => ['required', 'integer', 'between:1,8'],
        ]);

        // Enforce: category belongs to selected type
        $categoryTypeId = Category::where('category_id', $validated['category_id'])->value('type_id');
        if ((int)$categoryTypeId !== (int)$validated['type_id']) {
            return back()->withErrors([
                'category_id' => 'That category does not match the selected type.',
            ])->withInput();
        }

        // Store expenses as negative, income as positive
        $typeName = Type::where('type_id', $validated['type_id'])->value('type_name');
        $signedAmount = (strtolower($typeName) === 'expense') ? -abs($validated['amount']) : abs($validated['amount']);

        Transaction::create([
            'user_id' => $userId,
            'date' => $validated['date'],
            'description' => $validated['description'],
            'amount' => $signedAmount,
            'emotion' => $validated['emotion'],
            'type_id' => $validated['type_id'],
            'category_id' => $validated['category_id'],
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction added.');
    }

    public function edit(Request $request, Transaction $transaction)
    {
        $this->authorizeOwner($request, $transaction);

        return view('transactions.edit', [
            'transaction' => $transaction,
            'types' => Type::orderBy('type_name')->get(),
            'categories' => Category::orderBy('category_name')->get(),
            'emotionOptions' => Transaction::emotions(),
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeOwner($request, $transaction);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:1'],
            'type_id' => ['required', 'integer', 'exists:types,type_id'],
            'category_id' => ['required', 'integer', 'exists:categories,category_id'],
            'emotion' => ['required', 'integer', 'between:1,8'],
        ]);

        $categoryTypeId = Category::where('category_id', $validated['category_id'])->value('type_id');
        if ((int)$categoryTypeId !== (int)$validated['type_id']) {
            return back()->withErrors([
                'category_id' => 'That category does not match the selected type.',
            ])->withInput();
        }

        $typeName = Type::where('type_id', $validated['type_id'])->value('type_name');
        $signedAmount = (strtolower($typeName) === 'expense') ? -abs($validated['amount']) : abs($validated['amount']);

        $transaction->update([
            'date' => $validated['date'],
            'description' => $validated['description'],
            'amount' => $signedAmount,
            'emotion' => $validated['emotion'],
            'type_id' => $validated['type_id'],
            'category_id' => $validated['category_id'],
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated.');
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        $this->authorizeOwner($request, $transaction);
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted.');
    }

    private function authorizeOwner(Request $request, Transaction $transaction): void
    {
        if ((int)$transaction->user_id !== (int)$request->user()->user_id) {
            abort(403);
        }
    }
}