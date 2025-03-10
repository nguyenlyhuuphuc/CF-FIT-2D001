<?php

namespace App\Http\Controllers\Client;

use App\Events\OrderSucceed;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Jobs\TestSendMail;
use App\Mail\OrderAdminEmail;
use App\Mail\OrderUserEmail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentMethod;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    public function vnpayCallback(Request $request)
    {
        $errorMessage = match ($request->vnp_ResponseCode) {
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
            default => 'Thanh cong'
        };

        $orderId = $request->vnp_TxnRef;
        $order = Order::find($orderId);

        $orderPaymentMethod = $order->orderPaymentMethods[0];

        $orderPaymentMethod->reason_decline = $errorMessage;
        $orderPaymentMethod->save();

        if ($request->vnp_ResponseCode === '00') {
            //Empty cart
            session()->put('cart', []);

            //Emit event - OrderSucceed
            event(new OrderSucceed($order));
        }

        return redirect()->route('checkout');
    }


    public function placeOrder(PlaceOrderRequest $request)
    {
        try {
            DB::beginTransaction();

            //Cart
            $cart = session()->get('cart', []);
            $total = 0;
            foreach ($cart as $productId => $item) {
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
            foreach ($cart as $productId => $item) {
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

            DB::commit();

            if ($request->payment_method === 'vnpay') {
                date_default_timezone_set('Asia/Ho_Chi_Minh');

                $startTime = date("YmdHis");
                $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

                $vnp_TxnRef = (string)$order->id; //Mã giao dịch thanh toán tham chiếu của merchant
                $vnp_Amount = $order->total; // Số tiền thanh toán
                $vnp_Locale = 'vn'; //Ngôn ngữ chuyển hướng thanh toán
                $vnp_BankCode = 'VNBANK'; //Mã phương thức thanh toán
                $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; //IP Khách hàng thanh toán

                $inputData = array(
                    "vnp_Version" => "2.1.0",
                    "vnp_TmnCode" => env('VNP_TMNCODE'),
                    "vnp_Amount" => $vnp_Amount * 23000 * 100,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef,
                    "vnp_OrderType" => "other",
                    "vnp_ReturnUrl" => env('VNP_RETURNURL'),
                    "vnp_TxnRef" => $vnp_TxnRef,
                    "vnp_ExpireDate" => $expire,
                    "vnp_BankCode" => $vnp_BankCode
                );

                // dd($inputData);

                ksort($inputData);
                $query = "";
                $i = 0;
                $hashdata = "";
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashdata .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }
                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }

                $vnp_Url = env('VNP_URL') . "?" . $query;
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, env('VNP_HASHSECRET')); //  
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

                return Redirect::to($vnp_Url);
            }


            //Empty cart
            session()->put('cart', []);

            //Emit event - OrderSucceed
            event(new OrderSucceed($order));

            return redirect()->route('checkout');
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception('Create order failed.' . $e->getMessage());
        }
    }

    public function checkout()
    {
        $cart = session('cart', []);

        $user = Auth::check() ? Auth::user() : null;

        return view('client.pages.checkout', ['cart' => $cart, 'user' => $user]);
    }


    public function updateCart(int $productId, int $qty)
    {
        $cart = session('cart', []);

        if (array_key_exists($productId, $cart)) {
            if (!$qty) {
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


    public function removeProduct(int $productId)
    {
        $cart = session('cart', []);

        if (array_key_exists($productId, $cart)) {
            unset($cart[$productId]);
        }

        session()->put('cart',  $cart);

        return response()->json([
            'status' => true,
            'count' => count($cart),
            'message' => 'Remove product success.'
        ]);
    }


    public function emptyCart()
    {
        session()->put('cart',  []);

        return response()->json([
            'status' => true,
            'count' => 0,
            'message' => 'Remove cart success.'
        ]);
    }

    public function index()
    {
        $cart = session()->get('cart', []);

        return view('client.pages.shopping_cart', ['cart' => $cart]);
    }

    public function addProducToCart(int $productId, int $qty = 1)
    {
        $cart = session()->get('cart', []);

        $product = Product::find($productId);

        if (!$product->qty) {
            return response()->json([
                'status' => false,
                'message' => 'Product out of stock.'
            ]);
        }

        $cart[$productId] = [
            'name' => $product->name,
            'price' => $product->price,
            'image' => asset('images') . '/' . $product->image,
            'qty' => isset($cart[$productId]['qty']) ? $cart[$productId]['qty'] + $qty : $qty,
        ];

        session()->put('cart',  $cart);

        return response()->json([
            'status' => true,
            'count' => count($cart),
            'message' => 'Add product to cart success.'
        ]);
    }

    public function sendEmail()
    {
        //1k user
        $users = User::all();

        foreach ($users as $user) {
            TestSendMail::dispatch();
        }
    }
}
