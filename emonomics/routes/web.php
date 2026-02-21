<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\Category;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

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

    // Most common emotion
    $commonEmotion = Transaction::where('user_id', $user->user_id)
        ->selectRaw('emotion, COUNT(*) as count')
        ->groupBy('emotion')
        ->orderByDesc('count')
        ->first();

    // Total transactions
    $totalTransactions = Transaction::where('user_id', $user->user_id)->count();

    // Spending by mood
    $spendingByMood = Transaction::where('user_id', $user->user_id)
        ->whereHas('type', fn($q) => $q->where('type_name', 'expense'))
        ->selectRaw('emotion, SUM(amount) as total')
        ->groupBy('emotion')
        ->get();

    // Pass all to dashboard
    return view('dashboard', [
        'transactions' => $transactions,
        'totalSpendings' => abs($totalSpendings),
        'commonEmotion' => $commonEmotion,
        'totalTransactions' => $totalTransactions,
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

    Route::get('/admin/users', function () {
        $users = User::withCount('transactions')->orderByDesc('created_at')->get();
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
});

require __DIR__.'/auth.php';