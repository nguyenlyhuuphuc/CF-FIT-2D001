<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Mail\OrderAdminEmail;
use App\Mail\OrderUserEmail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentMethod;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    public function placeOrder(PlaceOrderRequest $request){
        try{
            DB::beginTransaction();

            //Cart
            $cart = session()->get('cart', []);
            $total = 0;
            foreach($cart as $productId => $item){
                $total += $item['price'] * $item['qty'];
            }

            //Save order
            $order = Order::create([
                'address' => $request->address,
                'note' => $request->note,
                'subtotal' => $total,
                'total' => $total,
                'status' => 'pending',
                'user_id' => Auth::user()->id
            ]);

            //Save order item
            $i = 1;
            foreach($cart as $productId => $item){
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $productId;
                $orderItem->price = $item['price'];
                $orderItem->name = $item['name'];                
                $orderItem->qty = $item['qty'];
                $orderItem->image = $item['image'];

                $orderItem->save(); //insert new record
                $i++;
            }

            //Save order payment method
            $orderPaymentMethod = new OrderPaymentMethod();
            $orderPaymentMethod->order_id = $order->id;
            $orderPaymentMethod->payment_method = $request->payment_method;
            $orderPaymentMethod->total = $total;
            $orderPaymentMethod->note = sprintf('Thanh toan cho hoa don: %s ', $order->id);
            $orderPaymentMethod->save();

            //Update phone user
            $user = Auth::user();
            $user->phone = $request->phone;
            $user->save(); //update old record

            //Empty cart
            session()->put('cart', []);

            DB::commit();

            //Send mail to user
            // Mail::to($user->email)->send(new OrderUserEmail($order));

            //Minus qty
            foreach($cart as $productId => $item){
                $product = Product::find($productId);
                $product->qty -= $item['qty'];
                $product->save();
            }

            //Send mail to admin
            // Mail::to(env('ADMIN_EMAIL'))->send(new OrderAdminEmail($order));

            return redirect()->route('checkout');
        }catch(\Exception $e){
            DB::rollBack();
            throw new Exception('Create order failed.');
        }
    }

    public function checkout(){
        $cart = session('cart', []);

        $user = Auth::check() ? Auth::user() : null;

        return view('client.pages.checkout', ['cart' => $cart, 'user' => $user]);
    }


    public function updateCart(int $productId, int $qty) {
        $cart = session('cart', []);

        if(array_key_exists($productId, $cart)){
            if(!$qty){
                unset($cart[$productId]);
            } else {
                $cart[$productId]['qty'] = $qty;
            }
        }

        session()->put('cart',  $cart);

        return response()->json([
            'status' => true,
            'count' => count($cart),
            'message' => 'Update cart success.'
        ]);
    }


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
        $cart = session()->get('cart', []);

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
