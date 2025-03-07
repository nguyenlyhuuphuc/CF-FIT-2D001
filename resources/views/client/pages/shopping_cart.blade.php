@extends('client.layout.master')

@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Shopping Cart</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.html">Home</a>
                            <span>Shopping Cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shoping Cart Section Begin -->
    <section class="shoping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th class="shoping__product">Products</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tbody-cart">
                                @php $total = 0; @endphp
                                @foreach ($cart as $productId => $item)
                                    @php
                                        $totalItem = $item['price'] * $item['qty'];
                                        $total += $totalItem
                                    @endphp
                                    <tr id="product-{{ $productId }}">
                                        <td class="shoping__cart__item">
                                            <img src="img/cart/cart-1.jpg" alt="">
                                            <h5>{{ $item['name'] }}</h5>
                                        </td>
                                        <td class="shoping__cart__price">
                                            ${{ number_format($item['price'], 2) }}
                                        </td>
                                        <td class="shoping__cart__quantity">
                                            <div class="quantity">
                                                <div class="pro-qty" data-product-id="{{ $productId }}">
                                                    <input class="product-qty" type="text" value="{{ $item['qty'] }}">
                                                </div>
                                            </div>
                                        </td>
                                        <td data-product-price="{{ $item['price'] }}" class="shoping__cart__total">
                                            ${{ number_format($item['price'] * $item['qty'], 2) }}
                                        </td>
                                        <td class="shoping__cart__item__close">
                                            <span data-remove-url="{{ route('cart.remove.product', ['productId' => $productId]) }}" data-product-id="{{ $productId }}" class="button-remove-product icon_close"></span>
                                        </td>
                                    </tr>    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__btns">
                        <a href="#" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                        <a href="#" class="primary-btn cart-btn cart-btn-right button-empty-cart"><span class="icon_close"></span>
                            Empty Cart</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shoping__continue">
                        <div class="shoping__discount">
                            <h5>Discount Codes</h5>
                            <form action="#">
                                <input type="text" placeholder="Enter your coupon code">
                                <button type="submit" class="site-btn">APPLY COUPON</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shoping__checkout">
                        <h5>Cart Total</h5>
                        <ul>
                            <li>Subtotal <span>${{ number_format($total, 2) }}</span></li>
                            <li>Total <span>${{ number_format($total, 2) }}</span></li>
                        </ul>
                        <a href="#" class="primary-btn">PROCEED TO CHECKOUT</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shoping Cart Section End -->
@endsection

@section('my-js')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.button-empty-cart').on('click', function(e){
                e.preventDefault();
        
                $.ajax({
                    url: "{{ route('cart.empty') }}", //Action of form
                    method: 'GET', //method of form
                    success:function(response){
                        $('.count-cart').html(response.count);

                        var icon = response.status ? 'success' : 'error';

                        if(response.status){
                            $('#tbody-cart').empty();
                        }

                        Swal.fire({
                            icon: icon,
                            text: response.message,
                        });
                    },
                    error: function(respsonse){
                    
                    }
                });
            });

            $('.button-remove-product').on('click', function(e){
                e.preventDefault();

                var url = $(this).data('remove-url');
                var productId = $(this).data('product-id');
        
                $.ajax({
                    url: url, //Action of form
                    method: 'GET', //method of form
                    success:function(response){
                        $('.count-cart').html(response.count);

                        var icon = response.status ? 'success' : 'error';

                        if(response.status){
                            $('#product-'+ productId).empty();
                        }

                        Swal.fire({
                            icon: icon,
                            text: response.message,
                        });
                    },
                    error: function(respsonse){
                    
                    }
                });
            });

            $('.qtybtn').on('click', function(){
                var qty = parseInt($(this).siblings('.product-qty').val());

                var type = $(this).hasClass('dec') ? 'decrease' : 'increase';

                var productId = $(this).parent().data('product-id');

                if(type === 'decrease'){
                    qty -= 1;

                    if(qty <= 0){
                        qty = 0;
                        $('#product-'+ productId).empty();
                    }
                }else{
                    qty += 1;
                }

                var url = "{{ route('cart.update') }}";
                url += "/" + productId + "/" + qty;

                $.ajax({
                    url: url, //Action of form
                    method: 'GET', //method of form
                    success:function(response){
                        $('.count-cart').html(response.count);
                        var icon = response.status ? 'success' : 'error';
                        Swal.fire({
                            icon: icon,
                            text: response.message,
                        });

                        if(response.status){
                            var total = $('tr#product-' + productId).children('.shoping__cart__total');
                            var price = parseInt(total.data('product-price'));
                            var totalProduct= price * qty;

                            total.html("$" + totalProduct.toFixed(2));
                        }
                    },
                    error: function(respsonse){
                    
                    }
                });
            })
        });
    </script>
@endsection