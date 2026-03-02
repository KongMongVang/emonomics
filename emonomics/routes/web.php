<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\Category;
use App\Models\User;
use App\Models\Emotion;

Route::get('/', function () {
    return view('welcome');
});

// About page route
Route::get('/about', function () {
    return view('about');
})->name('about');

// User dashboard route (only for non-admin users)
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    $transactions = Transaction::with(['type', 'category'])
        ->where('user_id', $user->user_id)
        ->orderByDesc('date')
        ->take(5)
        ->get();

    // Total spendings this month
    $month = now()->month;
    $year = now()->year;
    $totalSpendings = Transaction::where('user_id', $user->user_id)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->whereHas('type', fn($q) => $q->where('type_name', 'expense'))
        ->sum('amount');

    // Total transactions
    $totalTransactions = Transaction::where('user_id', $user->user_id)->count();

    // Most common emotion
    $commonEmotion = null;
    if (\Schema::hasColumn('transactions', 'emotion')) {
        $commonEmotion = Transaction::where('user_id', $user->user_id)
            ->selectRaw('emotion, COUNT(*) as count')
            ->groupBy('emotion')
            ->orderByDesc('count')
            ->first();
    }

    // Spending by mood (filtered for current month)
    $spendingByMood = \App\Models\Transaction::where('user_id', $user->user_id)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->whereHas('type', fn($q) => $q->where('type_name', 'expense'))
        ->whereNotNull('emotion')
        ->select('emotion', \DB::raw('SUM(amount) as total'))
        ->groupBy('emotion')
        ->get();

    // Pass all to dashboard
    return view('dashboard', [
        'transactions' => $transactions,
        'totalSpendings' => abs($totalSpendings),
        'totalTransactions' => $totalTransactions,
        'commonEmotion' => $commonEmotion,
        'spendingByMood' => $spendingByMood,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// User-only routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Get categories for a given type (AJAX)
    Route::get('/transactions/categories/{typeId}', function ($typeId) {
        $categories = \App\Models\Category::where('type_id', $typeId)
            ->orderBy('category_name')
            ->get(['category_id', 'category_name']);
        return response()->json($categories);
    });
});

// Admin dashboard route (admin only, no user dashboard or transaction routes)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $suspendedAccounts = User::where('is_suspended', true)->count();
        $recentUsers = User::orderByDesc('created_at')->take(5)->get();
        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalTransactions' => $totalTransactions,
            'suspendedAccounts' => $suspendedAccounts,
            'recentUsers' => $recentUsers,
        ]);
    })->name('admin.dashboard');

    Route::get('/admin/users', function (\Illuminate\Http\Request $request) {
        $usersQuery = User::withCount('transactions')->orderByDesc('created_at');
        if ($request->has('suspended')) {
            $usersQuery->where('is_suspended', true);
        }
        $users = $usersQuery->get();
        return view('admin.users', [
            'users' => $users,
        ]);
    })->name('admin.users');

    // Suspend or activate user
    Route::post('/admin/users/{user}/suspend', function (User $user) {
        $user->is_suspended = !$user->is_suspended;
        $user->save();
        return redirect()->route('admin.users');
    })->name('admin.users.suspend');

    // Delete user
    Route::delete('/admin/users/{user}', function (User $user) {
        $user->delete();
        return redirect()->route('admin.users');
    })->name('admin.users.delete');

    Route::get('/admin/users/{user}', function (App\Models\User $user) {
        if ($user->is_admin) abort(404);
        $user->loadCount('transactions');
        $totalSpending = $user->transactions()->whereHas('type', fn($q) => $q->where('type_name', 'expense'))->sum('amount');
        $recentTransactions = $user->transactions()->orderByDesc('date')->take(10)->get();
        return view('admin.user-view', [
            'user' => $user,
            'totalSpending' => $totalSpending,
            'recentTransactions' => $recentTransactions,
        ]);
    })->name('admin.users.view');

    // Manage Categories Page
    Route::get('/admin/categories', function () {
        $expenseType = Type::where('type_name', 'expense')->first();
        $spendingCategories = collect();
        if ($expenseType) {
            $spendingCategories = Category::where('type_id', $expenseType->type_id)
                ->withCount(['transactions as users_count' => function ($query) {
                    $query->select(Transaction::raw('count(distinct user_id)'));
                }])
                ->get();
        }
        return view('admin.categories', [
            'spendingCategories' => $spendingCategories,
        ]);
    })->name('admin.categories');

    // Store Spending Category (Expense)
    Route::post('/admin/categories/spending', function (\Illuminate\Http\Request $request) {
        $request->validate(['category_name' => 'required|string|max:255']);
        $expenseType = Type::where('type_name', 'expense')->first();
        if ($expenseType) {
            Category::create([
                'category_name' => $request->category_name,
                'type_id' => $expenseType->type_id,
            ]);
        }
        return redirect()->route('admin.categories')->with('success', 'Spending category added!');
    })->name('admin.categories.spending.store');

    // Edit Spending Category (Expense)
    Route::put('/admin/categories/{category}', function (\Illuminate\Http\Request $request, $categoryId) {
        $request->validate(['category_name' => 'required|string|max:255']);
        $category = Category::findOrFail($categoryId);
        $category->category_name = $request->category_name;
        $category->save();
        return redirect()->route('admin.categories')->with('success', 'Spending category updated!');
    })->name('admin.categories.edit');

    // Delete Category
    Route::delete('/admin/categories/{category}', function ($categoryId) {
        $category = Category::findOrFail($categoryId);
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Category deleted!');
    })->name('admin.categories.delete');

    // Manage Emotions Page
    Route::get('/admin/emotions', function () {
        $emotions = Emotion::orderBy('name')->get();
        return view('admin.emotions', [
            'emotions' => $emotions,
        ]);
    })->name('admin.emotions');

    // Store Emotion
    Route::post('/admin/emotions', function (\Illuminate\Http\Request $request) {
        $request->validate(['name' => 'required|string|max:255|unique:emotions,name']);
        Emotion::create(['name' => $request->name]);
        return redirect()->route('admin.emotions')->with('success', 'Emotion added!');
    })->name('admin.emotions.store');

    // Edit Emotion
    Route::put('/admin/emotions/{emotion}', function ($emotionId, \Illuminate\Http\Request $request) {
        $request->validate(['name' => 'required|string|max:255|unique:emotions,name,' . $emotionId]);
        $emotion = \App\Models\Emotion::findOrFail($emotionId);
        $emotion->name = $request->name;
        $emotion->save();
        return redirect()->route('admin.emotions')->with('success', 'Emotion updated!');
    })->name('admin.emotions.edit');

    // Delete Emotion
    Route::delete('/admin/emotions/{emotion}', function ($emotionId) {
        $emotion = Emotion::findOrFail($emotionId);
        $emotion->delete();
        return redirect()->route('admin.emotions')->with('success', 'Emotion deleted!');
    })->name('admin.emotions.delete');
});

require __DIR__.'/auth.php';