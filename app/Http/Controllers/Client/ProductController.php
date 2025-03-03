<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(string $slug){
        $product = Product::where('slug', $slug)->first();

        return view('client.pages.product_detail', ['product' => $product]);
    }
}
