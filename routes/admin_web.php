<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Models\ProductCategory;

Route::get('admin', function (){
    return view('admin.layout.master');
});

Route::get('admin/product_category/index', [ProductCategoryController::class, 'index'])->name('admin.product_category.index');

Route::get('admin/product_category/create',[ProductCategoryController::class, 'create'])->name('admin.product_category.create');

Route::post('admin/product_category/store', [ProductCategoryController::class, 'store'])->name('admin.product_category.store');

Route::get('admin/product_category/slug', [ProductCategoryController::class, 'makeSlug'])->name('admin.product_category.make_slug');

Route::post('admin/product_category/slug_post', [ProductCategoryController::class, 'makeSlug'])->name('admin.product_category.make_slug_post');

Route::get('admin/product_category/detail/{id}', [ProductCategoryController::class, 'detail'])->name('admin.product_category.detail');

Route::post('admin/product_category/update/{id}', [ProductCategoryController::class, 'update'])->name('admin.product_category.update');

Route::post('admin/product_category/destroy/{id}', [ProductCategoryController::class, 'destroy'])->name('admin.product_category.destroy');

//Generate route 7(index, create, store, show , edit, update, destroy)
Route::resource('admin/product', ProductController::class)->names('admin.product');