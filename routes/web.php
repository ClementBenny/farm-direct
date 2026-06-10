<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Staff\StockController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Wholesale;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use Illuminate\Support\Facades\Auth;


Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/produce', [App\Http\Controllers\LandingController::class, 'produce'])->name('produce');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    $user = Auth::user();
    switch ($user->role) {
        case 'admin':
            return view('dashboard.admin');
        case 'staff':
            return view('dashboard.staff');
        case 'customer':
            return view('landing');
        case 'shop':
            return view('landing');
        default:
            return view('landing'); 
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin routes — only admin role can access
Route::middleware(['auth', 'role:admin', 'no.back'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
});

// Customer routes — only customer role can access
Route::middleware(['auth', 'role:customer', 'no.back'])->prefix('shop')->name('shop.')->group(function () {
    Route::get('/category/{category}', [ShopController::class, 'category'])->name('category');
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.store');
});

// Wholesale (Shop role)
Route::middleware(['auth', 'role:shop', 'no.back'])->prefix('wholesale')->name('wholesale.')->group(function () {
    // Browse
    Route::get('/', [Wholesale\ShopController::class, 'index'])->name('index');
    Route::get('/category/{category}', [Wholesale\ShopController::class, 'category'])->name('category');
    // Cart
    Route::get('/cart', [Wholesale\CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [Wholesale\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [Wholesale\CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [Wholesale\CartController::class, 'remove'])->name('cart.remove');
    // Checkout
    Route::get('/checkout', [Wholesale\CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [Wholesale\CartController::class, 'placeOrder'])->name('checkout.store');
    // Orders
    Route::get('/orders', [Wholesale\OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [Wholesale\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [Wholesale\OrderController::class, 'cancel'])->name('orders.cancel');
});

// Staff routes — only staff role can access
Route::middleware(['auth', 'role:staff', 'no.back'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/', [StaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [StaffController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [StaffController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [StaffController::class, 'updateStatus'])->name('orders.status');
    Route::get('/stock', [StockController::class, 'index'])->name('stock');
    Route::patch('/stock/{product}', [StockController::class, 'update'])->name('stock.update');
});