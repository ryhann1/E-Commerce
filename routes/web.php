<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// ---------- Toko (publik, tanpa login) ----------
Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/produk', [ShopController::class, 'index'])->name('produk.index');
Route::get('/produk/{product}', [ShopController::class, 'show'])->name('produk.show');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{product}', [CartController::class, 'remove'])->name('cart.remove');

// ---------- Login satu klik (demo) ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login/{role}', [AuthController::class, 'demo'])
        ->whereIn('role', ['user', 'admin'])
        ->name('login.demo');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------- Area user (harus login) ----------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{order}/bayar', [OrderController::class, 'pay'])->name('pesanan.bayar');
});

// ---------- Area admin ----------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    Route::resource('produk', AdminProductController::class)
        ->parameters(['produk' => 'product'])
        ->except('show');

    Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('pesanan.index');
    Route::patch('/pesanan/{order}', [AdminOrderController::class, 'update'])->name('pesanan.update');
});
