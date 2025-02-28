<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(){
        $cart = session()->get('cart');
        return view('client.pages.shopping_cart', ['cart' => $cart]);
    }

    public function addProducToCard(int $productId){
        $cart = session()->get('cart', []);

        $product = Product::find($productId);

        if(!$product->qty){
            return response()->json([
                'status' => false,
                'message' => 'Product out of stock.'
            ]);
        }

        if(array_key_exists($productId, $cart)){
            $cart[$productId]['qty'] += 1;
        }else{
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'image' => asset('images').'/'.$product->image,
                'qty' => 1,
            ];
        }

        session()->put('cart',  $cart);

        return response()->json([
            'status' => true,
            'count' => count($cart),
            'message' => 'Add product to cart success.'
        ]);
    }
}
