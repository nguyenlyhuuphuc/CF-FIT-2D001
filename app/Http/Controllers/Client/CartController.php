<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function removeProduct(int $productId){
        $cart = session('cart', []);

        if(array_key_exists($productId, $cart)){
            unset($cart[$productId]);
        }

        session()->put('cart',  $cart);

        return response()->json([
            'status' => true,
            'count' => count($cart),
            'message' => 'Remove product success.'
        ]);
    }


    public function emptyCart(){
        session()->put('cart',  []);

        return response()->json([
            'status' => true,
            'count' => 0,
            'message' => 'Remove cart success.'
        ]);
    }

    public function index(){
        $cart = session()->get('cart');
        return view('client.pages.shopping_cart', ['cart' => $cart]);
    }

    public function addProducToCart(int $productId, int $qty = 1){
        $cart = session()->get('cart', []);

        $product = Product::find($productId);

        if(!$product->qty){
            return response()->json([
                'status' => false,
                'message' => 'Product out of stock.'
            ]);
        }

        $cart[$productId] = [
            'name' => $product->name,
            'price' => $product->price,
            'image' => asset('images').'/'.$product->image,
            'qty' => isset($cart[$productId]['qty']) ? $cart[$productId]['qty'] + $qty : $qty,
        ];
    
        session()->put('cart',  $cart);

        return response()->json([
            'status' => true,
            'count' => count($cart),
            'message' => 'Add product to cart success.'
        ]);
    }
}
