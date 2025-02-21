<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Middleware\CheckIsAge18;
use App\Models\ProductCategory;
use Faker\Guesser\Name;

Route::get('admin', function (){
    return view('admin.layout.master');
});

//Product Category
Route::prefix('admin/product_category')
->name('admin.product_category.')
->controller(ProductCategoryController::class)
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
Route::resource('admin/product', ProductController::class)->names('admin.product');

Route::get('product/7-up', function(){
    echo '<h1>7 Up</h1>';
})->middleware(CheckIsAge18::class);