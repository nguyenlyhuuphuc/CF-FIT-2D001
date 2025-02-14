<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategoryStoreRequest;
use App\Http\Requests\ProductCategoryUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index(Request $request){
        $name = $request->name ?? null;
        $sort = $request->sort ?? 'latest';
        $itemPerPage = config('test.a.b.c.d.itemPerPage', 99);

        $datas = DB::table('product_category_test');
    
        if($name){
            $datas->where('name', 'like', "%$name%");
        }

        if($sort === 'latest'){
            $datas->orderBy('created_at', 'desc');
        }else if($sort === 'oldest'){
            $datas->orderBy('created_at', 'asc');
        }

        $datas = $datas->paginate($itemPerPage);

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

    public function detail($id){
        //Query Builder
        $data = DB::table('product_category_test')->find($id);

        if(!$data){
            return abort(404);
        }

        return view('admin.pages.product_category.detail', ['data' => $data]);
    }

    public function update(ProductCategoryUpdateRequest $request, string $id){
        //Query Builder
        $check = DB::table('product_category_test')->where('id', $id)->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status
        ]);
        
        return redirect()->route('admin.product_category.index')->with('message', $check ? 'Update thanh cong' : 'Update that bai');
    }

    public function destroy(string $id){
        $check = DB::table('product_category_test')->where('id', $id)->delete();

        return redirect()->route('admin.product_category.index')->with('message', $check ? 'Delete thanh cong' : 'Delete that bai');
    }
}
