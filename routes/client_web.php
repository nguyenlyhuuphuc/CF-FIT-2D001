<?php

use App\Http\Controllers\Client\GoogleController;
use App\Http\Controllers\Client\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('google/auth/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
 
Route::get('google/auth/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('index', [HomeController::class, 'index'])->name('index');