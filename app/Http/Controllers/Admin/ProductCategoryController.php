<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategoryStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{
    public function index(){
        return view('admin.pages.product_category.index');
    }

    public function create(){
        return view('admin.pages.product_category.create');
    }

    public function store(ProductCategoryStoreRequest $request){
        //Receive data
        $name = $request->name;
        $slug = $request->slug;
        $status = $request->status;

        //Query Builder
        $check = DB::table('product_category_test')->insert([
            'name' => $name,
            'slug' => $slug,
            'status' => $status
        ]);

        return redirect()->route('admin.product_category.index')->with('message', $check ? 'Insert thanh cong' : 'Insert that bai');
    }

    public function makeSlug(Request $request){
        return response()->json(['slug' => 'nguyen-van-a']);
    }
}
