<?php

use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\GoogleController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('index', [HomeController::class, 'index'])->name('index');

//Google
Route::get('google/auth/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('google/auth/callback', [GoogleController::class, 'callback'])->name('google.callback');

//Cart
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::get('add-product-to-cart/{productId?}/{qty?}', [CartController::class, 'addProducToCart'])->name('add.product');
Route::get('cart/empty', [CartController::class, 'emptyCart'])->name('cart.empty');
Route::get('cart/remove-product/{productId}', [CartController::class, 'removeProduct'])->name('cart.remove.product');

//Product
Route::get('product/{slug}', [ProductController::class, 'index'])->name('product.index');