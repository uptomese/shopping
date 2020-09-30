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
                <!-- <li><a href="#">Hot Deals</a></li> -->
                <li class="active"><a href="{{ route('allStore') }}">Store</a></li>
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


<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- ASIDE -->
            <div id="aside" class="col-md-3">
                <!-- aside Widget -->
                <div class="aside">
                    <h3 class="aside-title">Categories</h3>
                    <form action="/choose_categories" method="GET" id="form_categories" name="form_categories">
                        <div class="checkbox-filter">
                            <input type="hidden" name="pattern" value="{{$pattern}}">
                            <input type="hidden" name="myselect" value="{{$myselect}}">
                            @foreach($array_categories as $item)
                                @if($item['count'] > 0)                         
                                <div class="input-checkbox">
                                    <input onChange="this.form.submit()" type="checkbox" name="categories[]" id="{{$item[0]['id']}}" value="{{$item[0]['id']}}"
                                        @foreach($categories_id as $list) @if($list==$item[0]['id']) checked @endif @endforeach>
                                    <label for="{{$item[0]['id']}}">
                                        <span></span>
                                        {{$item[0]['name']}}
                                        <b>({{$item['count']}})</b>
                                    </label>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        <hr>
                    </form>
                </div>
                <!-- /aside Widget -->

                <!-- aside Widget -->
                <div class="aside">
                    <h3 class="aside-title">Price</h3>
                    <div class="price-filter">
                        <form action="/choose_price" method="GET">
                            <input type="hidden" name="pattern" value="{{$pattern}}">
                            <input type="hidden" name="myselect" value="{{$myselect}}">
                            <input type="hidden" name="categories" value="{{json_encode($categories_id,TRUE)}}">
                            <div class="price-filter">
                                <div id="price-slider"></div>                                
                                <div class="input-number price-min">
                                    <input id="price-min" name="price-min" type="number" value="{{$min_price ?? 1}}" max="9999" min="1" step="1" oninput="" />
                                    <span class="qty-up">+</span>
                                    <span class="qty-down">-</span>
                                </div>
                                <span>-</span>
                                <div class="input-number price-max">
                                    <input id="price-max" name="price-max" type="number" value="{{$max_price ?? 9999}}" max="9999" min="1" step="1" oninput="" />
                                    <span class="qty-up">+</span>
                                    <span class="qty-down">-</span>
                                </div>
                            </div>                                        
                            <br>
                            <input class="primary-btn cta-btn" type="submit" value="Search">
                        </form>
                    </div>
                </div>
                <hr>
                <!-- /aside Widget -->

                <!-- aside Widget -->
                <!-- <div class="aside">
							<h3 class="aside-title">Brand</h3>
							<div class="checkbox-filter">
								<div class="input-checkbox">
									<input type="checkbox" id="brand-1">
									<label for="brand-1">
										<span></span>
										SAMSUNG
										<small>(578)</small>
									</label>
								</div>
								<div class="input-checkbox">
									<input type="checkbox" id="brand-2">
									<label for="brand-2">
										<span></span>
										LG
										<small>(125)</small>
									</label>
								</div>
								<div class="input-checkbox">
									<input type="checkbox" id="brand-3">
									<label for="brand-3">
										<span></span>
										SONY
										<small>(755)</small>
									</label>
								</div>
								<div class="input-checkbox">
									<input type="checkbox" id="brand-4">
									<label for="brand-4">
										<span></span>
										SAMSUNG
										<small>(578)</small>
									</label>
								</div>
								<div class="input-checkbox">
									<input type="checkbox" id="brand-5">
									<label for="brand-5">
										<span></span>
										LG
										<small>(125)</small>
									</label>
								</div>
								<div class="input-checkbox">
									<input type="checkbox" id="brand-6">
									<label for="brand-6">
										<span></span>
										SONY
										<small>(755)</small>
									</label>
								</div>
							</div>
						</div> -->
                <!-- /aside Widget -->

                <!-- aside Widget -->
                <div class="aside">
                    <div class="section-title">
                        <h4 class="title">Top selling</h4>
                        <div class="section-nav">
                            <div id="slick-nav-3" class="products-slick-nav"></div>
                        </div>
                    </div>
                    <div class="products-widget-slick" data-nav="#slick-nav-3">
                        @if($top_products[0] ?? '' !=0)
                            @for ($i = 1; $i <= (count($top_products)/3); $i++)
                                <div>
                                @foreach ($top_products as $key => $item)
                                @if($key >= (count($top_products)*($i-1)/3) && $key < (count($top_products)*$i/3))
                                    <div class="product-widget">
                                        <a href="{{ route('getProduct', ['id'=>$item['id']]) }}">
                                            <div class="product-img">
                                                <img src="{{ Storage::disk('local')->url('product_images/'.$item['image']) }}" alt="">
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
                        @else
                            <p>Wanting</p>
                        @endif
                    </div>
                </div>
                <!-- /aside Widget -->
            </div>
            <!-- /ASIDE -->

            <!-- STORE -->
            <div id="store" class="col-md-9">
            
            @php $id_checked_categories = ''; @endphp
            @foreach($categories_id as $key => $list)
                @if($list!=0)
                @php
                    $text = '&categories[]='.$list ;
                    $id_checked_categories .= $text ;
                @endphp 
                @endif               
            @endforeach
                <!-- store top filter -->
                <div class="store-filter clearfix">
                    <div class="store-sort">
                        <!-- <form action="/store" method="GET">
                            <label>
                                Sort By:
                                <select class="input-select">
                                    <option value="0">น้อย-มาก</option>
                                    <option value="1">มาก-น้อย</option>
                                </select>
                            </label>
                            <label>
                                Show:
                                <select onchange='this.form.submit()' class="input-select" form="carform">
                                    <option value="0">20</option>
                                    <option value="1">50</option>
                                </select>
                            </label>
                            <noscript><input type="submit" value="Submit"></noscript>
                        </form> -->

                        <form action="/store" method="GET" id="form_pattern">
                            <label>Show:
                                <input type="hidden" id="categories_checked" name="categories_checked">
                                <input type="hidden" name="pattern" value="{{$pattern}}">
                                <select name="myselect" id="myselect" onchange="this.form.submit()" class="input-select">
                                    <option value="5" @if($myselect == '5') selected @endif >5</option>
                                    <option value="10" @if($myselect == '10') selected @endif >10</option>
                                    <option value="20" @if($myselect == '20') selected @endif >20</option>
                                    <option value="50" @if($myselect == '50') selected @endif >50</option>
                                </select>
                            </label>
                        </form>

                    </div>
                    <ul class="store-grid">
                    <!-- class="active" -->
                        <li @if($pattern == 'card') class="active" @endif>
                            @if($pattern == 'card')
                            <i class="fa fa-th"></i>
                            @else
                            <a href="/choose_categories?pattern=card{{$id_checked_categories}}"><i class="fa fa-th"></i></a>
                            @endif
                        </li>
                        <li @if($pattern == 'table') class="active" @endif>
                            @if($pattern == 'table')
                            <i class="fa fa-th-list"></i>
                            @else
                            <a href="/choose_categories?pattern=table{{$id_checked_categories}}"><i class="fa fa-th-list"></i></a>
                            @endif
                        </li>
                    </ul>
                </div>
                <!-- /store top filter -->

                <!-- store products -->
                @if($pattern == 'card')
                <div class="row">
                    @foreach($array_products->items as $item)
                    <!-- product -->
                    <div class="col-md-4 col-xs-6">
                    <a href="{{ route('getProduct', ['id'=>$item['id']]) }}">
                        <div class="product">
                            <div class="product-img">
                                <img src="{{ Storage::disk('local')->url('product_images/'.$item['image']) }}" alt="" >
                                <div class="product-label">
                                    <!-- <span class="sale">-30%</span>
                                    <span class="new">NEW</span> -->
                                </div>
                            </div>
                            <div class="product-body">
                                <!-- <p class="product-category">Category</p> -->
                                <h3 class="product-name text_product_name"><a href="#">{{$item['name']}}</a></h3>
                                <h4 class="product-price">{{$item['price']}} 
                                <!-- <del class="product-old-price">{{$item['price']}}</del> -->
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
                                    <button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span
                                            class="tooltipp">add to wishlist</span></button>
                                    <button class="add-to-compare"><i class="fa fa-exchange"></i><span
                                            class="tooltipp">add to compare</span></button>
                                    <button class="quick-view"><a href="{{ route('getProduct', ['id'=>$item['id']]) }}"><i
                                                class="fa fa-eye"></i></a><span class="tooltipp">quick
                                            view</span></button>
                                </div>
                            </div>
                            <div class="add-to-cart">
                                <a href="{{ route('AddToCartProduct',['id' => $item['id'], 'qunatity' => 1]) }}">
                                    <button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to
                                        cart</button>
                                </a>
                            </div>
                        </div>
                    </a>
                    </div>
                    <!-- /product -->
                    @endforeach
                </div>
                @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                        <th scope="col">Id.</th>
                        <th scope="col">Ratting</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Price</th>
                        <th scope="col">Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($array_products->items as $item)
                            <tr>
                                <td>{{$item['id']}}</td>
                                <td scope="row">
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
                                </td>
                                <td>{{$item['name']}}</td>
                                <td>{{$item['description']}}</td>
                                <td>${{$item['price']}}</td>
                                <td>  
                                    <a href="{{ route('getProduct', ['id'=>$item['id']]) }}">
                                        <img alt="Image placeholder" src="{{ asset('storage') }}/product_images/{{$item['image']}}" style="width:100px;height:90px;border-radius: 50%;"> 
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
               
                @endif
                <!-- /store products -->

                <!-- store bottom filter -->

                <!-- currentPage -->
                <div class="store-filter clearfix">
                    <span class="store-qty">All {{$array_products->total}} products</span>
                    <ul class="store-pagination">
                        @foreach ($array_products->links as $key => $values)                    
                            <li @if($values['selected']==1) class="active" @endif>
                                @if($values['selected']!=1)
                                    @if($values['page']!=null)
                                    <a href="?page={{$values['page']}}&myselect={{$myselect}}&pattern={{$pattern}}{{$id_checked_categories}}&price-min={{$min_price??1}}&price-max={{$max_price??9999}}">{{ $values['icon'] }}</a>
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

                <!-- /store bottom filter -->
            </div>
            <!-- /STORE -->
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
