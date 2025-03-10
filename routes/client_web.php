<?php

use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\GoogleController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController;
use App\Jobs\TestSendMail;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('index', [HomeController::class, 'index'])->name('index');

//Google
Route::get('google/auth/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('google/auth/callback', [GoogleController::class, 'callback'])->name('google.callback');

//Cart
Route::get('cart', [CartController::class, 'index'])->name('cart.index')->middleware('auth');
Route::get('add-product-to-cart/{productId?}/{qty?}', [CartController::class, 'addProducToCart'])->name('add.product')->middleware('auth');
Route::get('cart/empty', [CartController::class, 'emptyCart'])->name('cart.empty')->middleware('auth');
Route::get('cart/remove-product/{productId}', [CartController::class, 'removeProduct'])->name('cart.remove.product')->middleware('auth');
Route::get('cart/update/{productId?}/{qty?}', [CartController::class, 'updateCart'])->name('cart.update')->middleware('auth');

//Checkout
Route::get('checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::post('checkout/place-order', [CartController::class, 'placeOrder'])->name('checkout.place_order')->middleware('auth');

Route::get('vnpay-callback', [CartController::class, 'vnpayCallback'])->name('vnpay.callback');

//Product
Route::get('product/{slug}', [ProductController::class, 'index'])->name('product.index');

Route::get('test-send-mail', function () {
    $user = Auth::check() ? Auth::user() : null;

    Mail::to('nguyenlyhuuphuc@gmail.com')->send(new TestMail($user));
});


Route::get('test-job', function () {
    TestSendMail::dispatch();
});

Route::get('test-send-email', [CartController::class, 'sendEmail']);
