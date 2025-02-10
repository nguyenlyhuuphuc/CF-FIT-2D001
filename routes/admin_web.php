<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductCategoryController;

Route::get('admin', function (){
    return view('admin.layout.master');
});

Route::get('admin/product_category/index', [ProductCategoryController::class, 'index'])->name('admin.product_category.index');

Route::get('admin/product_category/create',[ProductCategoryController::class, 'create'])->name('admin.product_category.create');

Route::post('admin/product_category/store', [ProductCategoryController::class, 'store'])->name('admin.product_category.store');

Route::get('admin/product_category/slug', [ProductCategoryController::class, 'makeSlug'])->name('admin.product_category.make_slug');

Route::post('admin/product_category/slug_post', [ProductCategoryController::class, 'makeSlug'])->name('admin.product_category.make_slug_post');