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
				<li><a href="{{ route('allStore') }}">Store</a></li>
                <li class="active"><a href="{{ route('cartproducts') }}">Checkout</a></li>
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
            <form action="/create_order" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="col-md-7">
                    <!-- Billing Details -->
                    @if(!Auth::check())
                    <div class="billing-details">
                        <div class="section-title">
                            <h3 class="title">Billing address</h3>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <input class="input" type="text" name="first_name" placeholder="First Name" required>
                        </div>
                        <div class="form-group">
                            <input class="input" type="text" name="last_name" placeholder="Last Name" required>
                        </div>
                        <div class="form-group">
                            <input class="input" type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <input class="input" type="text" name="address" placeholder="Address" required>
                        </div>
                        <div class="form-group">
                            <input class="input" type="tel" name="tel" placeholder="Telephone" required>
                        </div>
                        <div class="form-group">
                            <div class="input-checkbox">
                                <input type="checkbox" name="create_account" id="create-account">
                                <label for="create-account">
                                    <span></span>
                                    Create Account?
                                </label>
                                <div class="caption">
                                    <p>Password</p>
                                    <input class="input" type="password" name="password"
                                        placeholder="Enter Your Password">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- /Billing Details -->

                    <!-- Order notes -->
                    <!-- <div class="order-notes">
							<textarea class="input" placeholder="Order Notes"></textarea>
						</div> -->
                    <!-- /Order notes -->
                </div>

                <!-- Order Details -->
                <div class="col-md-5 order-details">
                    <div class="section-title text-center">
                        <h3 class="title">Your Order</h3>
                    </div>
                    <div class="order-summary">
                        <div class="order-col">
                            <div><strong>PRODUCT</strong></div>
                            <div><strong>TOTAL</strong></div>
                        </div>
                        @foreach($cartItems->items as $item)
                        <div class="order-products">
                            <div class="order-col">
                                <div>{{ $item['quantity'] }}x {{ $item['data'][0]['name'] }}</div>
                                <div>{{ $item['totalSinglePrice'] }}</div>
                            </div>
                        </div>
                        @endforeach
                        <div div class="order-col">
                            <div>Shiping</div>
                            <div><strong>FREE</strong></div>
                        </div>
                        <div class="order-col">
                            <div><strong>TOTAL</strong></div>
                            <div><strong class="order-total">{{ $totalPrice }}</strong></div>
                        </div>
                    </div>
                    <hr>
                    @if(isset($address))
                    <div class="payment-method">
                        <div class="order-col">
                            <div><strong>At delivery</strong></div>
                        </div>
                        <br>
                        <div class="input-radio">
                            <input type="radio" name="address" id="radio_address-1" value="{{$userData->address[1] ?? ''}}" required @if(isset($userData->address[0]) && $userData->address[0] == 'address_a') checked @endif>
                            @if(!isset($userData->address[0]))
                            <div class="form-group">
                                <input class="input" type="text" name="new_address" placeholder="Delivery address" required>
                            </div>
                            @else
                            <label for="radio_address-1">
                                <span></span>
                                @php if($userData->address[0] == 'address_a'){ echo '(default)';} @endphp {{$userData->address[1] ?? ''}}
                            </label>
                        </div>
                        @if(count($address) > 0)  
                        <br>
                        <div class="input-radio">
                            <input type="radio" name="address" id="radio_address-2" value="address_b" required @if($userData->address[0] == 'address_b') checked @endif >
                            <label for="radio_address-2">
                                <span></span>
                                <select id="mySelect" name="address_mult"  class="input-select" style="width: 100%;">
                                    @foreach ($address as $key => $item)
                                    <option value="{{$item['address']}}" name="{{$key}}" @if(isset($userData->address[2]) && $item['address'] == $userData->address[2]) selected @endif>
                                    @if(isset($userData->address[2]) && $item['address'] == $userData->address[2]) (default) @endif {{$item['title']}} [ {{$item['address']}} ]
                                    </option>
                                    @endforeach
                                </select>
                            </label>
                        </div> 
                            @endif
                        @endif
                    </div>
                    @endif
                    <hr>
                    <div class="payment-method">
                        <div class="input-radio">
                            <input type="radio" name="payment" id="payment-1" value="credit_card" required>
                            <label for="payment-1">
                                <span></span>
                                Pay by Credit Card   
                            </label>
                            <div class="caption">
                                <p>Once credit card payment is clicked, customer leads to card inform fill up page.</p>
                            </div>
                        </div>
                        <div class="input-radio">
                            <input type="radio" name="payment" id="payment-2" value="pay_pal" required>
                            <label for="payment-2">
                                <span></span>
                                PayPal  
                            </label>
                            <div class="caption">
                                <p>Pay for items or services you've purchased easily and more securely. All you need is an email address or mobile number to send a payment.</p>
                            </div>
                        </div>

                        <!-- <div class="input-radio">
                            <input type="radio" name="payment" id="payment-2">
                            <label for="payment-2">
                                <span></span>
                                Direct Bank Transfer
                            </label>
                            <div class="caption">
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                        <div class="input-radio">
                            <input type="radio" name="payment" id="payment-3">
                            <label for="payment-3">
                                <span></span>
                                Cheque Payment
                            </label>
                            <div class="caption">
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                        <div class="input-radio">
                            <input type="radio" name="payment" id="payment-4">
                            <label for="payment-4">
                                <span></span>
                                Paypal System
                            </label>
                            <div class="caption">
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua.</p>
                            </div>
                        </div> -->
                    </div>
                    <!-- <div class="input-checkbox">
                        <input type="checkbox" id="terms">
                        <label for="terms">
                            <span></span>
                            I've read and accept the <a href="#">terms & conditions</a>
                        </label>
                    </div> -->
                    <!-- <a href="{{ route('createOrder') }}" class="primary-btn order-submit" type="submit">Place order</a> -->
                    <button type="submit" name="submit" class="primary-btn order-submit">Place order</button>

                    <button onclick="sendAddress()" style="display:none;">Click me</button>
                </div>
                <!-- /Order Details -->
            </form>
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

<script>
function sendAddress() {

    const rbs = document.querySelectorAll('input[name="address"]');

    let selectedValue;

    for (const rb of rbs) {
        if (rb.checked) {
            selectedValue = rb.value;
            break;
        }
    }

    if(selectedValue=="address_b"){

        var x = document.getElementById("mySelect").selectedIndex;
        var y = document.getElementById("mySelect").options;

        selectedValue = "address_b_" + y[x].index;

    }else{

        selectedValue = "address_a";
    }

    console.log(selectedValue);
}
</script>
