<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategoryStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index(){
        // $itemPerPage = env('ITEM_PER_PAGE', 3);
        $itemPerPage = config('test.a.b.c.d.itemPerPage', 99);
        $datas = DB::table('product_category_test')->orderBy('created_at', 'desc')->paginate($itemPerPage);

        return view('admin.pages.product_category.index', ['datas' => $datas]);
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
        //use Illuminate\Support\Str;
        return response()->json(['slug' => Str::slug($request->slug)]);
    }
}
