@extends('layouts.index')

@section('center')

<!-- NAVIGATION -->
<nav id="navigation">
    <!-- container -->
    <div class="container">
        <!-- responsive-nav -->
        <div id="responsive-nav">
            <!-- NAV -->
            <ul class="main-nav nav navbar-nav">
                <li><a href="{{ route('allProducts') }}">Home</a></li>
                <li><a href="{{ route('allStore') }}">Store</a></li>
                <li class="active"><a href="{{ route('viewCart') }}">View Cart</a></li>
                <li><a href="{{ route('cartproducts') }}">Checkout</a></li>
                <li><a href="{{ route('checkOrder') }}">Check Order</a></li>
            </ul>
            <!-- /NAV -->
        </div>
        <!-- /responsive-nav -->
    </div>
    <!-- /container -->
</nav>
<!-- /NAVIGATION -->

<br>
<div class="container">
    @include('alert')
</div>

<!-- NEWSLETTER -->
<div id="newsletter" >
    <!-- container -->
    <div class="container">
        <div class="card shopping-cart">
            <div class="card-header bg-dark text-light">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                Shipping cart
                <a href="{{ route('allStore') }}" class="btn btn-outline-info btn-sm pull-right">Continiu shopping</a>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                @foreach($cartItems->items as $item)
                    <!-- PRODUCT -->
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-2 text-center">
                                <img class="img-responsive" src="{{ Storage::disk('local')->url('product_images/'.$item['data'][0]['image']) }}" alt="prewiew" width="120" height="80">
                        </div>
                        <div class="col-12 text-sm-center col-sm-12 text-md-left col-md-6">
                            <h4 class="product-name"><strong>{{ $item['data'][0]['name'] }}</strong></h4>
                            <h4>
                                <small>@foreach($item['data'] as $item_description) {{ $item_description['description'] }} @endforeach</small>
                            </h4>
                        </div>
                        <div class="col-12 col-sm-12 text-sm-center col-md-4 text-md-right row">
                            <div class="product-details">
                                <div class="col-3 col-sm-3 col-md-6 text-md-right" style="padding-top: 15px">
                                    <h6><strong>{{ $item['data'][0]['price'] }} <span class="text-muted">x</span></strong></h6>
                                </div>
                                <div class="add-to-cart">
                                    <div class="qty-label">
                                        <div class="input-number">
                                            <input type="number" name="qunatity" value="{{ $item['quantity'] }}" disabled>
                                            <a href="{{ route('increaseSingleProduct', ['id' => $item['data'][0]['id']]) }}"><span class="qty-up">+</span></a>
                                            <a href="{{ route('decreaseSingleProduct', ['id' => $item['data'][0]['id']]) }}"><span class="qty-down">-</span></a>
                                        </div>
                                    </div>   
                                    {{-- <h6><strong>{{ $item['totalSinglePrice'] }}</strong></h6>                            --}}
                                    <div class="pull-right" style="padding-top: 5px">
                                        <a href="{{ route('DeleteItemFromCart', ['id' => $item['data'][0]['id']]) }}"><button class="delete"><i class="fa fa-trash"></i></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- END PRODUCT -->
                @endforeach
                    
                {{-- <div class="pull-right">
                    <a href="" class="btn btn-outline-secondary pull-right">
                        Update shopping cart
                    </a>
                </div> --}}
                <div class="pull-right" style="margin: 10px">
                    <div class="cart-summary">
                        <small>{{ $totalQuantity }} Item(s) selected</small>
                        <h3>SUBTOTAL: {{ $totalPrice }}</h3>
					</div>
                </div>
            </div>
            <div class="card-footer">
                {{-- <div class="coupon col-md-5 col-sm-5 no-padding-left pull-left">
                    <div class="row">
                        <div class="col-6">
                            <input type="text" class="form-control" placeholder="cupone code">
                        </div>
                        <div class="col-6">
                            <input type="submit" class="btn btn-default" value="Use cupone">
                        </div>
                    </div>
                </div> --}}
                <div class="pull-right" style="margin: 10px">                    
                    <a href="{{ route('cartproducts') }}"><button type="submit" name="submit" class="primary-btn order-submit">Checkout</button></a>
                    {{-- <a href="" class="btn btn-success pull-right">Checkout</a> --}}
                </div>
            </div>
        </div>   
    </div>
    <!-- /container -->
</div>
<!-- /NEWSLETTER -->

<!-- NEWSLETTER -->
<div id="newsletter" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="newsletter">
                    <p>Sign Up for the <strong>NEWSLETTER</strong></p>
                    <form>
                        <input class="input" type="email" placeholder="Enter Your Email">
                        <button class="newsletter-btn"><i class="fa fa-envelope"></i> Subscribe</button>
                    </form>
                    <ul class="newsletter-follow">
                        <li>
                            <a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /NEWSLETTER -->
{{-- @if(!isset($array_orders))
<br><br><br><br><br><br><br><br><br><br><br><br><br>
@endif --}}


@if(Auth::check())

<div id="app">
    @php
    $user = array(
        'id' => Auth::user()->id,
        'name' => Auth::user()->name,
        'email' => Auth::user()->email,
        'image' => Auth::user()->image,
        'status' => Auth::user()->status,
        );
    @endphp

    <chat-component 
        v-bind:user="{{  json_encode($user) }}"
        :messages="messages" 
        v-on:messagesent="addMessage"
        v-on:session="addSession"
        v-on:delete_message="delMessage"
    ></chat-component>
</div>

<script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ URL::asset('js/box.js') }} "></script>
<script src="{{ asset('js/app.js') }}"></script>

@endif

@endsection
