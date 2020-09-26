@extends('layouts.index')

@section('center')
	</section>
	<!-- Breadcrumb Section Begin -->
	<section class="breadcrumb-section set-bg" data-setbg="img/tools.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>T-20 | Shop</h2>
                        <div class="breadcrumb__option">
                            <a href="/">Home</a>
                            <span>Shop</span>
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
                            <tbody>
								@foreach($cartItems->items as $item)
                                <tr>
                                    <td class="shoping__cart__item">
                                        <img src="{{Storage::disk('local')-> url('product_images/'.$item['data']['image'])}}" style="width: 100px; height: 100px;" alt="">
                                        <h5>{{$item['data']['name']}}</h5>
                                    </td>
                                    <td class="shoping__cart__price">
										฿{{$item['data']['price']}}
                                    </td>				
									
									<td class="shoping__cart__quantity">
                                        <div class="quantity">
                                            <div class="pro-qty">
												
												<a href="{{route('DecreaseSingleProduct',['id' => $item['data']['id']]) }}">
													<span class="dec qtybtn">-</span>
												</a>

												<input type="text" value="{{$item['quantity']}}">

												<a href="{{route('IncreaseSingleProduct',['id' => $item['data']['id']]) }}">
													<span class="inc qtybtn">+</span>
												</a>
												
                                            </div>
                                        </div>
                                    </td>
                                    <td class="shoping__cart__total">
										฿{{$item['totalSinglePrice']}}
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        <a href="{{route('DeleteItemFromCart',['id' => $item['data']['id']]) }}"><span class="icon_close"></span></a> 
                                    </td>
								</tr>
								@endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
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
                            <li>All Quantity <span>{{$cartItems->totalQuantity}}</span></li>
                            <li>Total <span>฿{{$cartItems->totalPrice}}</span></li>
                        </ul>
                        <a href="{{route('checkoutProducts')}}" class="primary-btn">PROCEED TO CHECKOUT</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shoping Cart Section End -->

@endsection

