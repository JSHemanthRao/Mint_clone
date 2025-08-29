<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController; 


Route::get('/profile', function () {
    return view('profile');
})->name('profile');


Route::view('/register', 'register');
Route::view('/', 'home');

Route::view('/login', 'login');

Route::view('/dashboard', 'dashboard')->name('dashboard');

Route::view('/accounts', 'Account')->name('accounts');
Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');


Route::get('/budgets', [BudgetController::class, 'display'])->name('budgets');
Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');

Route::view('/bills', 'Bills')->name('bills');
Route::post('/bills', [BillController::class, 'store'])->name('bills.store');

Route::get('/transactions', [TransactionController::class, 'display'])->name('transactions');
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

Route::view('/goals', 'Goals')->name('goals');
Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');

Route::view('/categories', 'Categories')->name('categories');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');