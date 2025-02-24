<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Middleware\CheckIsAge18;
use App\Http\Middleware\CheckLogin;
use App\Models\ProductCategory;

Route::get('admin', function (){
    return view('admin.layout.master');
})->middleware('admin');

//Product Category
Route::prefix('admin/product_category')
->name('admin.product_category.')
->controller(ProductCategoryController::class)
->middleware('admin')
->group(function(){
    Route::get('index', 'index')->name('index');
    Route::get('create','create')->name('create');
    Route::post('store', 'store')->name('store');
    Route::get('slug', 'makeSlug')->name('make_slug');
    Route::post('slug_post', 'makeSlug')->name('make_slug_post');
    Route::get('detail/{id}', 'detail')->name('detail');
    Route::post('update/{id}', 'update')->name('update');
    Route::post('destroy/{id}', 'destroy')->name('destroy');
});

//Generate route 7(index, create, store, show , edit, update, destroy)
Route::resource('admin/product', ProductController::class)->names('admin.product')->middleware('admin');

Route::get('product/7-up', function(){
    echo '<h1>7 Up</h1>';
})->middleware('auth');

Route::get('product/coca-cola', function(){
    echo '<h1>Coca cola</h1>';
});

Route::get('product/chivas', function(){
    echo '<h1>Chivas</h1>';
})->middleware(['check.is.age.18']);

Route::get('product/heniken', function(){
    echo '<h1>Heniken</h1>';
})->middleware(['check.is.age.18']);

