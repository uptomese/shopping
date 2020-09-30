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
                <li><a href="{{ route('cartproducts') }}">Checkout</a></li>
                <li class="active"><a href="{{ route('checkOrder') }}">Check Order</a></li>
            </ul>
            <!-- /NAV -->
        </div>
        <!-- /responsive-nav -->
    </div>
    <!-- /container -->
</nav>
<!-- /NAVIGATION -->

<div class="container">
    @include('alert')
</div>

<!-- NEWSLETTER -->
<div id="newsletter" >
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="newsletter"><br>
                    <p><strong>ORDER</strong></p>
                    <form action="/send_order" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input class="input" type="text" name="order" placeholder="Enter Your Code">
                        <button type="submit" name="submit" class="newsletter-btn">Check</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
        @if(isset($array_orders))
            <div class="col-md-6 order-details" style="float:none;margin:auto;">
                <div class="order-summary">
                <div class="section-title text-center">
                @foreach($orders as $items)
                <h3 class="title">Order#{{$items['id']}}</h3><br><h5>{{$items['name']}}</h5>
                @endforeach
                </div>
                    <div class="order-col">
                        <div><strong>PRODUCT</strong></div>
                        <div><strong>TOTAL</strong></div>
                    </div>
                    <div class="order-products">
                    @foreach($array_orders as $items)
                        @foreach($items['items'] as $item)
                        <div class="order-col">
                            <div>{{$item['product_quantity']}}x {{$item['product_name']}}</div>
                            <div>${{$item['product_price']}}</div>
                        </div>
                        @endforeach
                    </div>
                    <div class="order-col">
                        <div>Shiping</div>
                        <div><strong>FREE</strong></div>
                    </div>
                    <div class="order-col">
                        <div><strong>TOTAL</strong></div>
                        <div><strong class="order-total">${{$items['order']['price']}}</strong></div>
                    </div>
                    <hr>
                    <div class="order-col">
                        <div><strong>Delivery Status</strong></div>
                        <div><strong class="order-total">
                            <span class="badge badge-dot mr-4">
                                            @if($items['order']['status']=='wait')
                                            <i class="bg-warning"></i>
                                            @else
                                            <i class="bg-success"></i>
                                            @endif
                                            <span class="status">{{$items['order']['status']}}</span>
                            </span></strong></div>
                    </div>
                    <div class="order-col">
                        <div><strong>Payment</strong></div>
                        <div><strong class="order-total" style="@if($items['order']['status_payment'] ?? '' == 1 ) color:green; @endif">@if($items['order']['status_payment'] ?? '' == 1 ) Success @else Fail @endif</strong></div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
            <br>
            <div class="col-md-12">
                <div class="newsletter">
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
        <br>
    </div>
    <!-- /container -->
</div>
<!-- /NEWSLETTER -->

@if(!isset($array_orders))
<br><br><br><br><br><br><br><br><br><br><br><br><br>
@endif


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
