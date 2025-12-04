<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderAdminController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewAdminController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// FRONTEND (customer)
Route::get('/', [ShopController::class, 'index'])->name('shop.index')->middleware('auth');
Route::get('/product/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/category/{category:slug}', [ShopController::class, 'category'])->name('shop.category');

// AUTH (customer)
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman profil & riwayat pesanan (hanya untuk user login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::post('/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');

    Route::get('/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/orders/{order}', [AccountController::class, 'orderShow'])->name('account.orders.show');

     // CHECKOUT
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // review dari halaman detail pesanan
    Route::post('/orders/{order}/items/{item}/review', [ReviewController::class, 'storeFromOrder'])
        ->name('account.orders.items.review');

    // REVIEW PRODUK
    // Route::post('/product/{product}/reviews', [ReviewController::class, 'store'])
    //     ->name('product.review.store');
    // Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
    //     ->name('product.review.destroy');
});

// CART
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('orders', OrderAdminController::class)->only(['index', 'edit', 'update', 'show']);
    Route::resource('reviews', ReviewAdminController::class)->only(['index', 'destroy']);
});
