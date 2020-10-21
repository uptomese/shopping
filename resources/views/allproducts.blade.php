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
                <li class="active"><a href="{{ route('allProducts') }}">Home</a></li>
                <!-- <li><a href="#">Hot Deals</a></li> -->
                <li><a href="{{ route('allStore') }}">Store</a></li>
                <li><a href="{{ route('cartproducts') }}">Checkout</a></li>
                <li><a href="{{ route('checkOrder') }}">Check Order</a></li>
                <!-- <li><a href="#">Laptops</a></li>
						<li><a href="#">Smartphones</a></li>
						<li><a href="#">Cameras</a></li>
						<li><a href="#">Accessories</a></li> -->
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

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- shop -->
            <div class="col-md-4 col-xs-6">
                <div class="shop">
                    <div class="shop-img">
                        <img src="{{ asset('img/shop01.png') }}" alt="" height="360px">
                    </div>
                    <div class="shop-body">
                        <h3>Screws<br>Collection</h3>
                        <a href="{{ route('allStore') }}" class="cta-btn">Shop now <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- /shop -->

            <!-- shop -->
            <div class="col-md-4 col-xs-6">
                <div class="shop">
                    <div class="shop-img">
                        <img src="{{ asset('img/shop03.png') }}" alt="" height="360px">
                    </div>
                    <div class="shop-body">
                        <h3>Wrench<br>Collection</h3>
                        <a href="{{ route('allStore') }}" class="cta-btn">Shop now <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- /shop -->

            <!-- shop -->
            <div class="col-md-4 col-xs-6">
                <div class="shop">
                    <div class="shop-img">
                        <img src="{{ asset('img/shop02.png') }}" alt="" height="360px">
                    </div>
                    <div class="shop-body">
                        <h3>Spray<br>Collection</h3>
                        <a href="{{ route('allStore') }}" class="cta-btn">Shop now <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- /shop -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->



<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            <!-- section title -->
            <div class="col-md-12">
                <div class="section-title">
                    @if($searchText ?? '' == 'true')
                    <h3 class="title">Products</h3>
                    @else
                    <h3 class="title">New Products</h3>
                    @endif
                    <!-- <div class="section-nav">
                        <ul class="section-tab-nav tab-nav">
                            <li class="active"><a data-toggle="tab" href="#tab1">Laptops</a></li>
                            <li><a data-toggle="tab" href="#tab1">Smartphones</a></li>
                            <li><a data-toggle="tab" href="#tab1">Cameras</a></li>
                            <li><a data-toggle="tab" href="#tab1">Accessories</a></li>
                        </ul>
                    </div> -->
                </div>
            </div>
            <!-- /section title -->

            <!-- Products tab & slick -->

            <div class="col-md-12">
                <div class="row">
                    <div class="products-tabs">
                        <!-- tab -->
                        <div id="tab1" class="tab-pane active">
                            <div class="products-slick" data-nav="#slick-nav-1">

                                <!-- product -->
                                @foreach ($products as $product)
                                <div class="product">
                                    <a href="{{ route('getProduct', ['id'=>$product['id']]) }}">
                                        <div class="product-img">
                                            @if(gettype($product['image'])=="array")
                                                @foreach(array_slice($product['image'],0,1) as $image_array) 
                                                    <img src="{{ asset('storage') }}/product_images/{{$product['id']}}/{{$image_array}}" alt="">
                                                @endforeach                
                                            @else
                                            <img src="{{ Storage::disk('local')->url('product_images/'.$product['image']) }}" alt="">
                                            @endif
                                            <div class="product-label">
                                                <!-- <span class="sale">-30%</span> -->
                                                @if($searchText ?? '' == 'true')
                                                @else
                                                <span class="new">NEW</span>
                                                @endif                                                
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $product['categorie_name'] }}</p>
                                            <h3 class="product-name text_product_name"><a href="#">{{ $product['name'] }}</a></h3>
                                            <h4 class="product-price">${{ $product['price'] }} 
                                            <!-- <del class="product-old-price">{{ $product['price'] }}</del> -->
                                            </h4>
                                            <div class="product-rating">
                                                @php ($product['ratting']==5) ? $star = 5 : $star = $product['ratting'] % 5;  @endphp
                                                @for ($i = 0; $i < $star; $i++)
                                                <i class="fa fa-star"></i>
                                                @endfor
                                                @php $star_empty = 5 - $star @endphp
                                                @for ($i = 0; $i < $star_empty; $i++)
                                                    <i class="fa fa-star-o empty"></i>
                                                @endfor
                                            </div>
                                            <div class="product-btns">
                                                <button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
                                                <button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
                                                <button class="quick-view"><a href="{{ route('getProduct', ['id'=>$product['id']]) }}"><i class="fa fa-eye"></i></a><span class="tooltipp">quick view</span></button>
                                            </div>
                                        </div>
                                        <div class="add-to-cart">            
                                            @if($product['stock']!=0)
                                            <a href="{{ route('AddToCartProduct',['id' => $product['id'], 'qunatity' => 1]) }}">
                                                <button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button>
                                            </a>
                                            @else
                                            <button class="add-to-cart-btn" type="button" class="btn btn-lg btn-primary" disabled><i class="fa fa-times"></i> Out of stock</button>
                                            @endif                                            
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                                <!-- /product -->

                            </div>
                            <div id="slick-nav-1" class="products-slick-nav"></div>
                        </div>
                        <!-- /tab -->
                    </div>
                </div>
            </div>
            <!-- Products tab & slick -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

<!-- HOT DEAL SECTION -->
<div id="hot-deal" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="hot-deal">
                    <ul class="hot-deal-countdown">
                        <li>
                            <div>
                                <h3 id="time-dd"></h3>
                                <span>Days</span>
                            </div>
                        </li>
                        <li>
                            <div>
                                <h3 id="time-hh"></h3>
                                <span>Hours</span>
                            </div>
                        </li>
                        <li>
                            <div>
                                <h3 id="time-mm"></h3>
                                <span>Mins</span>
                            </div>
                        </li>
                        <li>
                            <div>
                                <h3 id="time-ss"></h3>
                                <span>Secs</span>
                            </div>
                        </li>
                    </ul>
                    <h2 class="text-uppercase">hot deal this week</h2>
                    <p>New Collection Up to 50% OFF</p>
                    <a class="primary-btn cta-btn" href="{{ route('allStore') }}">Shop now</a>
                </div>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /HOT DEAL SECTION -->

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            <!-- section title -->
            <div class="col-md-12">
                <div class="section-title">
                    <h3 class="title">Top ratting</h3>
                    <div class="section-nav">
                        <!-- <ul class="section-tab-nav tab-nav">
                            <li class="active"><a data-toggle="tab" href="#tab2">Laptops</a></li>
                            <li><a data-toggle="tab" href="#tab2">Smartphones</a></li>
                            <li><a data-toggle="tab" href="#tab2">Cameras</a></li>
                            <li><a data-toggle="tab" href="#tab2">Accessories</a></li>
                        </ul> -->
                    </div>
                </div>
            </div>
            <!-- /section title -->

            <!-- Products tab & slick -->
            <div class="col-md-12">
                <div class="row">
                    <div class="products-tabs">
                        <!-- tab -->
                        <div id="tab2" class="tab-pane fade in active">
                            <div class="products-slick" data-nav="#slick-nav-2">
                                <!-- product -->
                                @foreach ($ratting_products as $item)
                                <div class="product">
                                <a href="{{ route('getProduct', ['id'=>$item['id']]) }}">
                                    <div class="product-img">
                                        @if(gettype($item['image'])=="array")       
                                            @foreach(array_slice($item['image'],0,1) as $image_array) 
                                                <img src="{{ asset('storage') }}/product_images/{{$item['id']}}/{{$image_array}}" alt="">
                                            @endforeach                                        
                                        @else
                                        <img src="{{ Storage::disk('local')->url('product_images/'.$item['image']) }}" alt="">
                                        @endif
                                        <div class="product-label">
                                            <!-- <span class="sale">-30%</span>
                                            <span class="new">NEW</span> -->
                                        </div>
                                    </div>
                                    <div class="product-body">
                                        <p class="product-category">{{ $item['categorie_name'] }}</p>
                                        <h3 class="product-name text_product_name"><a href="#">{{$item['name']}}</a></h3>
                                        <h4 class="product-price">${{$item['price']}} 
                                        <!-- <del class="product-old-price">$990.00</del> -->
                                        </h4>
                                        <div class="product-rating">
                                            @php ($item['ratting']==5) ? $star = 5 : $star = $item['ratting'] % 5;  @endphp
                                            @for ($i = 0; $i < $star; $i++)
                                            <i class="fa fa-star"></i>
                                            @endfor
                                            @php $star_empty = 5 - $star @endphp
                                            @for ($i = 0; $i < $star_empty; $i++)
                                                <i class="fa fa-star-o empty"></i>
                                            @endfor
                                        </div>
                                        <div class="product-btns">
                                            <button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
                                            <button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
                                            <button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
                                        </div>
                                    </div>
                                    <div class="add-to-cart">          
                                        @if($item['stock']!=0)
                                        <a href="{{ route('AddToCartProduct',['id' => $item['id'], 'qunatity' => 1]) }}">
                                            <button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button>
                                        </a>
                                        @else
                                        <button class="add-to-cart-btn" type="button" class="btn btn-lg btn-primary" disabled><i class="fa fa-times"></i> Out of stock</button>
                                        @endif 
                                    </div>
                                </a>
                                </div>
                                @endforeach
                                <!-- /product -->
                            </div>
                            <div id="slick-nav-2" class="products-slick-nav"></div>
                        </div>
                        <!-- /tab -->
                    </div>
                </div>
            </div>
            <!-- /Products tab & slick -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
        <div class="col-md-4 col-xs-6">
            <div class="section-title">
                    <h4 class="title">Top selling</h4>
                    <div class="section-nav">
                        <div id="slick-nav-3" class="products-slick-nav"></div>
                    </div>
                </div>
                <div class="products-widget-slick" data-nav="#slick-nav-3">
                    @if($top_products[0] != 0)
                        @if (count($top_products) < 3)
                            <div>
                                @foreach ($top_products as $key => $item)
                                    <div class="product-widget">
                                        <a href="{{ route('getProduct', ['id'=>$item['id']]) }}">
                                            <div class="product-img">
                                                @if(gettype($item['image'])=="array")                                            
                                                    @foreach(array_slice($item['image'],0,1) as $image_array) 
                                                        <img src="{{ asset('storage') }}/product_images/{{$item['id']}}/{{$image_array}}" alt="">
                                                    @endforeach 
                                                @else
                                                <img src="{{ Storage::disk('local')->url('product_images/'.$item['image']) }}" alt="">
                                                @endif
                                            </div>
                                            <div class="product-body">
                                                <p class="product-category">{{$item['categorie_name']}}</p>
                                                <h3 class="product-name"><a href="#">{{$item['name']}}</a></h3>
                                                <h4 class="product-price">${{$item['price']}}
                                                <!-- <del class="product-old-price">$990.00</del> -->
                                                </h4>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>  
                        @else
                            @for ($i = 1; $i <= (count($top_products)/3); $i++)
                                <div>
                                @foreach ($top_products as $key => $item)
                                @if($key >= (count($top_products)*($i-1)/3) && $key < (count($top_products)*$i/3))
                                    <div class="product-widget">
                                        <a href="{{ route('getProduct', ['id'=>$item['id']]) }}">
                                            <div class="product-img">
                                                @if(gettype($item['image'])=="array")                                            
                                                    @foreach(array_slice($item['image'],0,1) as $image_array) 
                                                        <img src="{{ asset('storage') }}/product_images/{{$item['id']}}/{{$image_array}}" alt="">
                                                    @endforeach  
                                                @else
                                                <img src="{{ Storage::disk('local')->url('product_images/'.$item['image']) }}" alt="">
                                                @endif
                                            </div>
                                            <div class="product-body">
                                                <p class="product-category">{{$item['categorie_name']}}</p>
                                                <h3 class="product-name"><a href="#">{{$item['name']}}</a></h3>
                                                <h4 class="product-price">${{$item['price']}}
                                                <!-- <del class="product-old-price">$990.00</del> -->
                                                </h4>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @endforeach
                                </div>
                            @endfor
                        @endif
                    @else
                        <p>Wanting</p>
                    @endif
                </div>
            </div>

            <!-- <div class="col-md-4 col-xs-6">
                <div class="section-title">
                    <h4 class="title">Top rating</h4>
                    <div class="section-nav">
                        <div id="slick-nav-4" class="products-slick-nav"></div>
                    </div>
                </div>

                <div class="products-widget-slick" data-nav="#slick-nav-4">
                    <div>                        
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('img/product04.png') }}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
                            </div>
                        </div>                        
                        
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('img/product05.png') }}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
                            </div>
                        </div>                        
                        
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('img/product06.png') }}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
                            </div>
                        </div>                        
                    </div>

                    <div>                        
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('img/product07.png') }}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
                            </div>
                        </div>                        
                        
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('img/product08.png') }}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
                            </div>
                        </div>                        
                        
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('img/product09.png') }}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>

            <div class="clearfix visible-sm visible-xs"></div>
            <div class="col-md-4 col-xs-6">
                <div class="section-title">
                    <h4 class="title">Top selling</h4>
                    <div class="section-nav">
                        <div id="slick-nav-5" class="products-slick-nav"></div>
                    </div>
                </div>
                <div class="products-widget-slick" data-nav="#slick-nav-5">
                    <div>                        
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('img/product01.png') }}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
                            </div>
                        </div>                        
                        
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('img/product02.png') }}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
                            </div>
                        </div>                        
                        
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('img/product03.png') }}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
                            </div>
                        </div>                        
                    </div>

                    <div>                        
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('img/product04.png') }}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
                            </div>
                        </div>                        
                        
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('img/product05.png') }}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
                            </div>
                        </div>                        
                        
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('img/product06.png') }}" alt="">
                            </div>
                            <div class="product-body">
                                <p class="product-category">Category</p>
                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

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
