<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// =====================================================
// PUBLIC ROUTES
// =====================================================
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');

// =====================================================
// AUTH ROUTES
// =====================================================
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
Route::post('/register',[AuthController::class, 'register'])->name('register.post');
Route::get('/logout',   [AuthController::class, 'logout'])->name('logout');

// =====================================================
// USER ROUTES (harus login)
// =====================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/cart',            [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart',           [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}',       [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}',    [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/transactions',    [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/checkout',       [TransactionController::class, 'checkout'])->name('checkout');

    Route::get('/profile',         [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile',         [AuthController::class, 'updateProfile'])->name('profile.update');
});

// =====================================================
// ADMIN ROUTES (harus login & role admin)
// =====================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Products
    Route::get('/products',              [AdminController::class, 'products'])->name('products');
    Route::get('/products/create',       [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products',             [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit',    [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}',         [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}',      [AdminController::class, 'destroyProduct'])->name('products.destroy');

    // Users
    Route::get('/users',              [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}',      [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Transactions
    Route::get('/transactions',              [AdminController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/{id}',         [AdminController::class, 'showTransaction'])->name('transactions.show');
    Route::put('/transactions/{id}/status',  [AdminController::class, 'updateTransactionStatus'])->name('transactions.status');

    // Export
    Route::get('/export/pdf',   [AdminController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/excel', [AdminController::class, 'exportExcel'])->name('export.excel');
});

// =====================================================
// FALLBACK 404
// =====================================================
Route::fallback(function () {
    return view('errors.404');
});
