<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;

// Home page redirects to register
Route::get('/', [AuthController::class, 'showRegisterForm'])->name('home');

// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Protected routes (only accessible after login)
Route::middleware('auth')->group(function () {
    // Accounts dashboard
    Route::get('/accounts', [AccountController::class, 'dashboard'])->name('accounts.index');

    // Add new account
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
