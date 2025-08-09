<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\GoalController;

Route::apiResource('users', UserController::class);
Route::apiResource('transactions', TransactionController::class);
Route::apiResource('accounts', AccountController::class);
Route::apiResource('bills', BillController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('budgets', BudgetController::class);
Route::apiResource('goals', GoalController::class);