<?php

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['guestOrVerified'])->group(function () {
    Route::get('/', [\App\Http\Controllers\ProductController::class, 'index'])->name('product.index');
    Route::get('/product/{product:slug}', [FrontendProductController::class, 'show'])->name('product.show');
    Route::get('/category/{category:slug}', [\App\Http\Controllers\ProductController::class, 'byCategory'])->name('byCategory');

    Route::prefix('/cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product:slug}', [CartController::class, 'add'])->name('add');
        Route::post('/remove/{product:slug}', [CartController::class, 'remove'])->name('remove');
        Route::post('/update-quantity/{product:slug}', [CartController::class, 'updateQuantity'])->name('update-quantity');
    });
});


//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.update');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/profile/password-update', [ProfileController::class, 'passwordUpdate'])->name('profile_password.update');
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.session');
    Route::post('/checkout/unpaid/{order}', [CheckoutController::class, 'checkoutUnpaid'])->name('checkout.unpaid');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
    Route::post('/checkout/{order}', [CheckoutController::class, 'checkoutOrder'])->name('cart.checkout-order');
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/:order', [OrderController::class, 'show'])->name('order.show');
    Route::get('/order/{order}/view', [OrderController::class, 'view'])->name('order.view');


});
Route::post('/webhook/stripe', [CheckoutController::class, 'webhook'])->name('webhook.success');
require __DIR__ . '/auth.php';
