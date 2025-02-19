<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//SELECT `product`.*, product_categories.name
// FROM `product` 
// LEFT JOIN product_categories on product.product_category_id = product_categories.id
// ORDER BY created_at DESC;

        //Query Builder
        // $products = DB::table('product')->orderBy('created_at', 'desc')->paginate(10);
        $products = DB::table('product')
        ->leftJoin('product_categories', 'product.product_category_id', '=', 'product_categories.id')
        ->select(['product.*', 'product_categories.name as product_category_name'])
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        return view('admin.pages.product.index', ['datas' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Query Builder 
        // $productCategories = DB::table('product_categories')->where('status', 1)->get();
    
        //Eloquent
        // $productCategories = ProductCategory::all();
        $productCategories = ProductCategory::where('status', 1)->get();

        return view('admin.pages.product.create', ['productCategories' => $productCategories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {

        //Query Builder
        // $check = DB::table('product')->insert([
        //     'name' => $request->name,
        //     'price' => $request->price,
        //     'short_description' => $request->short_description,
        //     'qty' => $request->qty,
        //     'product_category_id' => $request->product_category_id
        // ]);

        //Eloquent
        // $product = new Product();
        // $product->name = $request->name;
        // $product->price = $request->price;
        // $product->short_description = $request->short_description;
        // $product->qty = $request->qty;
        // $product->product_category_id = $request->product_category_id;

        // $check = $product->save(); //Insert record

        //Mass Assigment + fillable + guarded

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'short_description' => $request->short_description,
            'qty' => $request->qty,
            'product_category_id' => $request->product_category_id,
            // 'image' => $fileName
        ]);

        //Upload file
        foreach ($request->file('image') as $image){
            $originName = $image->getClientOriginalName();
    
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $fileName = $fileName . '_' . uniqid() . '.' . $extension;
    
            $image->move(public_path('images'), $fileName);

            $productImage = new ProductImage();
            $productImage->image = $fileName;
            $productImage->product_id = $product->id;
            $productImage->save();
        }
    
        return redirect()->route('admin.product.index')->with('message', $product ? 'Insert thanh cong' : 'Insert that bai');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
