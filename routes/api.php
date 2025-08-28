<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\GoalController;
// use App\Http\Controllers\DashboardController;

// Route::middleware('auth:api')->get('/dashboard-data', [DashboardController::class, 'getData']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);




Route::middleware('auth:api')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/refresh', [AuthController::class, 'refresh']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('budgets', BudgetController::class);
    Route::apiResource('bills', BillController::class);
    Route::apiResource('goals', GoalController::class); // in routes/api.php
    Route::post('/accounts/{id}/deposit', [AccountController::class, 'deposit']);
    Route::post('/accounts/{id}/withdraw', [AccountController::class, 'withdraw']);

    // use App\Http\Controllers\BillController;

    // use Illuminate\Support\Facades\Route;
    // use App\Http\Controllers\BillController;

    // Bills API (JWT protected)
    Route::middleware('auth:api')->group(function () {
        Route::get('/bills', [BillController::class, 'index'])->name('bills.index');
        Route::post('/bills', [BillController::class, 'store'])->name('bills.store');
        Route::get('/bills/{id}', [BillController::class, 'show'])->name('bills.show');
        Route::put('/bills/{id}', [BillController::class, 'update'])->name('bills.update');
        Route::delete('/bills/{id}', [BillController::class, 'destroy'])->name('bills.destroy');
    });




    Route::middleware('auth:api')->get('/profile', function () {
        $user = Auth::user();
        $accounts = $user->accounts;
        return response()->json([
            'user' => $user,
            'accounts' => $accounts
        ]);
    });
});
