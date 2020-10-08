@extends('layouts.index')

@section('center')

<!-- <div id="breadcrumb" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb-tree">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">All Categories</a></li>
                    <li><a href="#">Accessories</a></li>
                    <li><a href="#">Headphones</a></li>
                    <li class="active">Product name goes here</li>
                </ul>
            </div>
        </div>
    </div>
</div> -->

<!-- NAVIGATION -->
<nav id="navigation">
    <!-- container -->
    <div class="container">
        <!-- responsive-nav -->
        <div id="responsive-nav">
            <!-- NAV -->
            <ul class="main-nav nav navbar-nav">
                <li><a href="{{ route('allProducts') }}">Home</a></li>
                <!-- <li><a href="#">Hot Deals</a></li> -->
                <li><a href="{{ route('allStore') }}">Store</a></li>
                <li><a href="{{ route('cartproducts') }}">Checkout</a></li>
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


<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- Product main img -->
            <div class="col-md-5 col-md-push-2">
                <div id="product-main-img">
                    <div class="product-preview">
                        <img src="{{ Storage::disk('local')->url('product_images/'.$product['image']) }}" alt="">
                    </div>

                    <div class="product-preview">
                        <img src="{{ Storage::disk('local')->url('product_images/'.$product['image']) }}" alt="">
                    </div>

                    <div class="product-preview">
                        <img src="{{ Storage::disk('local')->url('product_images/'.$product['image']) }}" alt="">
                    </div>

                    <div class="product-preview">
                        <img src="{{ Storage::disk('local')->url('product_images/'.$product['image']) }}" alt="">
                    </div>
                </div>
            </div>
            <!-- /Product main img -->

            <!-- Product thumb imgs -->
            <div class="col-md-2  col-md-pull-5">
                <div id="product-imgs">
                    <div class="product-preview">
                        <img src="{{ Storage::disk('local')->url('product_images/'.$product['image']) }}" alt="">
                    </div>

                    <div class="product-preview">
                        <img src="{{ Storage::disk('local')->url('product_images/'.$product['image']) }}" alt="">
                    </div>

                    <div class="product-preview">
                        <img src="{{ Storage::disk('local')->url('product_images/'.$product['image']) }}" alt="">
                    </div>

                    <div class="product-preview">
                        <img src="{{ Storage::disk('local')->url('product_images/'.$product['image']) }}" alt="">
                    </div>
                </div>
            </div>
            <!-- /Product thumb imgs -->


            <!-- Product details -->
            <div class="col-md-5">
                <div class="product-details">
                    <h2 class="product-name">{{$product['name']}}</h2>
                    <div>
                        <div class="product-rating">
                            @php ($ratting_product==5) ? $star = 5 : $star = $ratting_product % 5;  @endphp
                            @for ($i = 0; $i < $star; $i++)
                            <i class="fa fa-star"></i>
                            @endfor
                            @php $star_empty = 5 - $star @endphp
                            @for ($i = 0; $i < $star_empty; $i++)
                                <i class="fa fa-star-o empty"></i>
                            @endfor
                        </div>
                        <a class="review-link" href="#">{{$review_count}} Review(s) | Add your review</a>
                    </div>
                    <div>
                        <h3 class="product-price">{{$product['price']}}
                            <!-- <del class="product-old-price">{{$product['price']}}</del> -->
                        </h3>
                        <span class="product-available">In Stock ({{ $product['stock'] }})</span>
                    </div>
                    <p>{{$product['description']}}</p>
 
                    <!-- <div class="product-options">
                        <label>
                            Size
                            <select class="input-select">
                                <option value="0">X</option>
                            </select>
                        </label>
                        <label>
                            Color
                            <select class="input-select">
                                <option value="0">Red</option>
                            </select>
                        </label>
                    </div>  -->

                    <div class="add-to-cart">
                        <form action="{{ route('AddToCartProduct',['id' => $product['id']]) }}" method="GET">
                            <div class="qty-label">
                                Qty
                                <div class="input-number">
                                    <input type="number" name="qunatity" value=1 min=0 max="{{$product['stock']}}">
                                    <span class="qty-up">+</span>
                                    <span class="qty-down">-</span>
                                </div>
                            </div>
                            <button type="submit" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button>
                        </form>
                    </div>

                    <ul class="product-btns">
                        <li><a href="#"><i class="fa fa-heart-o"></i> add to wishlist</a></li>
                        <li><a href="#"><i class="fa fa-exchange"></i> add to compare</a></li>
                    </ul>

                    <ul class="product-links">
                        <li>Category:</li>
                        <li><a href="#">Headphones</a></li>
                        <li><a href="#">Accessories</a></li>
                    </ul>

                    <ul class="product-links">
                        <li>Share:</li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                    </ul>

                </div>
            </div>
            <!-- /Product details -->

            <!-- Product tab -->
            @php $page = isset($_GET['page']) ? $_GET['page'] : 0 ; @endphp                   
            <div class="col-md-12">
                <div id="product-tab">
                    <!-- product tab nav --> 
                    <ul class="tab-nav">
                        <li @if($page==0) class="active" @endif><a data-toggle="tab" href="#tab1">Description</a></li>
                        @if($product['details'] ?? '')
                        <li><a data-toggle="tab" href="#tab2">Details</a></li>
                        @endif
                        <li @if($page!=0) class="active" @endif><a data-toggle="tab" href="#tab3">Reviews ({{$review_count}})</a></li>
                    </ul>
                    <!-- /product tab nav -->

                    <!-- product tab content -->
                    <div class="tab-content">
                    
                        <!-- tab1  -->
                        <div id="tab1" @if($page==0) class="tab-pane fade in active" @else class="tab-pane fade in" @endif>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">STANDARD</th>
                                                <th scope="col">MATERIAL</th>
                                                <th scope="col">COATING</th>
                                                <th scope="col">CODE</th>
                                                <th scope="col">UPDATE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>{{$product['standard'] ?? ''}}</th>
                                                <th>{{$product['material'] ?? ''}}</th>
                                                <th>{{$product['coating'] ?? ''}}</th>
                                                <th>{{$product['code'] ?? ''}}</th>
                                                <th>{{$product['update'] ?? ''}}</th>
                                            </tr>                          
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /tab1  -->

                        <!-- tab2  -->
                        @if($product['details'] ?? '')
                        <div id="tab2" class="tab-pane fade in">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-details">
                                        <thead>
                                            <tr>
                                            @foreach ($product['details'][0] ?? '' as $key => $items)
                                                <th scope="col">{{ $key }}  </th>
                                             @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($product['details'] ?? '' as $key => $items)
                                            <tr>
                                            @foreach ($items as $index => $item)
                                                <td>{{$item}}</td>
                                            @endforeach 
                                            </tr>
                                        @endforeach                         
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- /tab2  -->

                        <!-- tab3  -->
                        <div id="tab3" @if($page!=0) class="tab-pane fade in active" @else class="tab-pane fade in" @endif>
                            <div class="row">
                                <!-- Rating -->
                                <div class="col-md-3">
                                    <div id="rating">
                                        <div class="rating-avg">
                                            <span>{{$ratting_product}}</span>
                                            @php ($ratting_product==5) ? $star = 5 : $star = $ratting_product % 5;  @endphp
                                            <div class="rating-stars">
                                                @for ($i = 0; $i < $star; $i++)
                                                <i class="fa fa-star"></i>
                                                @endfor
                                                @php $star_empty = 5 - $star @endphp
                                                @for ($i = 0; $i < $star_empty; $i++)
                                                    <i class="fa fa-star-o empty"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <ul class="rating">
                                            <li>
                                                <div class="rating-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <div class="rating-progress">
                                                    <div style="width: {{$review_count_star[4][1]}}%;"></div>
                                                </div>
                                                <span class="sum">{{$review_count_star[4][0]}}</span>
                                            </li>
                                            <li>
                                                <div class="rating-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                                <div class="rating-progress">
                                                    <div style="width: {{$review_count_star[3][1]}}%;"></div>
                                                </div>
                                                <span class="sum">{{$review_count_star[3][0]}}</span>
                                            </li>
                                            <li>
                                                <div class="rating-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                                <div class="rating-progress">
                                                <div style="width: {{$review_count_star[2][1]}}%;"></div>
                                                </div>
                                                <span class="sum">{{$review_count_star[2][0]}}</span>
                                            </li>
                                            <li>
                                                <div class="rating-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                                <div class="rating-progress">
                                                <div style="width: {{$review_count_star[1][1]}}%;"></div>
                                                </div>
                                                <span class="sum">{{$review_count_star[1][0]}}</span>
                                            </li>
                                            <li>
                                                <div class="rating-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                                <div class="rating-progress">
                                                <div style="width: {{$review_count_star[0][1]}}%;"></div>
                                                </div>
                                                <span class="sum">{{$review_count_star[0][0]}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /Rating -->

                                <!-- Reviews -->
                                <div class="col-md-6">
                                    <div id="reviews">
                                        <ul class="reviews">
                                            @foreach($reviews->items as $item)
                                            <li>
                                                <div class="review-heading">
                                                    <h5 class="name">{{$item['user_name']}}</h5>
                                                    <p class="date">{{$item['date']}}</p>
                                                    <div class="review-rating">
                                                        @for ($i = 0; $i < $item['ratting']; $i++)
                                                        <i class="fa fa-star"></i>
                                                        @endfor
                                                        @php $star_empty = 5 - $item['ratting'] @endphp
                                                        @for ($i = 0; $i < $star_empty; $i++)
                                                        <i class="fa fa-star-o empty"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="review-body">
                                                    <p>{{$item['review']}}</p>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <ul class="reviews-pagination">
                                        @foreach ($reviews->links as $key => $values)                    
                                            <li @if($values['selected']==1) class="active" @endif>
                                                @if($values['selected']!=1)
                                                    @if($values['page']!=null)
                                                    <a href="?page={{$values['page']}}">{{ $values['icon'] }}</a>
                                                    @else
                                                    {{ $values['icon'] }}
                                                    @endif
                                                @else                                
                                                    {{ $values['icon'] }}
                                                @endif
                                            </li>         
                                        @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <!-- /Reviews -->

                                <!-- Review Form -->
                                @if(Auth::check())
                                <div class="col-md-3">
                                    <div id="review-form">
                                        <form class="review-form" action="{{ route('reviewProduct',['id'=>$product['id']]) }}" method="POST">
                                        {{csrf_field()}}
                                            <input class="input" type="text" value="{{Auth::user()->name}}" disabled>
                                            <input class="input" type="email" value="{{Auth::user()->email}}" disabled>
                                            <textarea class="input" name="text_review" placeholder="Your Review" required></textarea>
                                            <div class="input-rating">
                                                <span>Your Rating: </span>
                                                <div class="stars">
                                                    <input id="star5" name="rating" value="5" type="radio" required><label
                                                        for="star5"></label>
                                                    <input id="star4" name="rating" value="4" type="radio" required><label
                                                        for="star4"></label>
                                                    <input id="star3" name="rating" value="3" type="radio" required><label
                                                        for="star3"></label>
                                                    <input id="star2" name="rating" value="2" type="radio" required><label
                                                        for="star2"></label>
                                                    <input id="star1" name="rating" value="1" type="radio" required><label
                                                        for="star1"></label>
                                                </div>
                                            </div>
                                            <button class="primary-btn">Submit</button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                                <!-- /Review Form -->
                            </div>
                        </div>
                        <!-- /tab3  -->
                    </div>
                    <!-- /product tab content  -->
                </div>
            </div>
            <!-- /product tab -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

<!-- Section -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            <div class="col-md-12">
                <div class="section-title text-center">
                    <h3 class="title">New Products</h3>
                </div>
            </div>

            @foreach($new_product as $item)
            <!-- product -->
            <div class="col-md-3 col-xs-6">
            <a href="{{ route('getProduct', ['id'=>$item['id']]) }}">
                <div class="product">
                    <div class="product-img">
                        <img src="{{ Storage::disk('local')->url('product_images/'.$item['image']) }}" alt="">
                        <div class="product-label">
                            <!-- <span class="sale">-30%</span> -->
                            <span class="new">NEW</span>
                        </div>
                    </div>
                    <div class="product-body">
                        <p class="product-category">Category</p>
                        <h3 class="product-name text_product_name"><a href="#">{{$item['name']}}</a></h3>
                        <h4 class="product-price">${{$item['price']}} 
                        <!-- <del class="product-old-price">{{$item['price']}}</del> -->
                        </h4>
                        <div class="product-rating">
                        </div>
                        <div class="product-btns">
                            <button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to
                                    wishlist</span></button>
                            <button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to
                                    compare</span></button>
                            <button class="quick-view"><a href="{{ route('getProduct', ['id'=>$item['id']]) }}"><i
                                        class="fa fa-eye"></i></a><span class="tooltipp">quick view</span></button>
                        </div>
                    </div>
                    <div class="add-to-cart">
                        <a href="{{ route('AddToCartProduct',['id' => $item['id'], 'qunatity' => 1]) }}">
                            <button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button>
                        </a>
                    </div>
                </div>
            </a>
            </div>
            <!-- /product -->
            @endforeach

        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /Section -->

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
