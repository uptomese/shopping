<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>Abpon | Thailandpages</title>

		<link rel="dns-prefetch" href="//fonts.gstatic.com">
  		<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

		<script src="https://unpkg.com/vue"></script>
		<script src="https://unpkg.com/marked@0.3.6"></script>
		<script src="https://unpkg.com/lodash@4.16.0"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

		<!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
		<!-- <link href="{{ mix('css/app.css') }}" rel="stylesheet"> -->

		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<link rel="stylesheet" href="{{URL::asset('css/box2.css')}} ">
  		<link rel="stylesheet" href="{{URL::asset('css/chatbtn.css')}} ">
		

		<link rel="icon" href="{{ asset('assets/img/brand/abpon.png') }}" type="image/png">

		<!-- Google font -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

		<!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}"/>

		<!-- Slick -->
		<link type="text/css" rel="stylesheet" href="{{asset('css/slick.css')}}"/>
		<link type="text/css" rel="stylesheet" href="{{asset('css/slick-theme.css')}}"/>

		<!-- nouislider -->
		<link type="text/css" rel="stylesheet" href="{{asset('css/nouislider.min.css')}}"/>

		<!-- Font Awesome Icon -->
		<link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">

		<!-- Custom stlylesheet -->
		<!-- <link type="text/css" rel="stylesheet" href="{{asset('css/style.css')}}"/> -->
		<link type="text/css" rel="stylesheet" href="{{asset('css/main_style.css')}}"/>	

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

    </head>
	<body>
		<!-- HEADER -->
		<header>
			<!-- TOP HEADER -->
			<div id="top-header">
				<div class="container">
					<ul class="header-links pull-left">
						<li><a href="#"><i class="fa fa-phone"></i> 02-760-8880</a></li>
						<li><a href="#"><i class="fa fa-envelope-o"></i> pornsawatv@gmail.com</a></li>
						<li><a href="#"><i class="fa fa-map-marker"></i> 3300/111 Elephant Tower ( Zone B) 21 Fl., Phahonyothin Rd., Chompol, Jatujak, Bangkok 10900, Thailand</a></li>
					</ul>
					<ul class="header-links pull-right">
						<li><a href="#"><i class="fa fa-dollar"></i> THB</a></li>
						@if(Auth::check())
						<li><a href="/home"><i class="fa fa-user-o"></i> {{Auth::user()->name}}</a></li>
						@else
						<li><a href="/login"><i class="fa fa-user-o"></i> LOGIN</a></li>
						@endif
					</ul>
				</div>
			</div>
			<!-- /TOP HEADER -->

			<!-- MAIN HEADER -->
			<div id="header">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<!-- LOGO -->
						<div class="col-md-3">
							<div class="header-logo">
								<a href="{{ route('allProducts') }}" class="logo">
									<img src="{{ asset('img/logo.png') }}" alt="" width="200" high="50">
								</a>
							</div>
						</div>
						<!-- /LOGO -->

						<!-- SEARCH BAR -->
						<div class="col-md-6">
							<div class="header-search">
								<form action="{{ route('searchText') }}" method="GET">
									<select class="input-select">
										<option value="0">All Categories</option>
										<!-- <option value="1">Category 01</option>
										<option value="1">Category 02</option> -->
									</select>
									<input class="input" name="searchText" placeholder="Search here">
									<button class="search-btn">Search</button>
								</form>
							</div>
						</div>
						<!-- /SEARCH BAR -->

						<!-- ACCOUNT -->
						<div class="col-md-3 clearfix">
							<div class="header-ctn">
								<!-- Wishlist -->
								<!-- <div>
									<a href="#">
										<i class="fa fa-heart-o"></i>
										<span>Your Wishlist</span>
										<div class="qty">2</div>
									</a>
								</div> -->
								<!-- /Wishlist -->
								<!-- Cart -->
								<div class="btn-group">
									<a class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fa fa-shopping-cart"></i>
										<span>Your Cart</span>
										@if($totalQuantity>0)
											<div class="qty"> {{ $totalQuantity }}</div>
										@endif
									</a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="width:300px;padding:15px;">
										<div class="cart-list">
										<!-- @php print_r($cartItems->items)  @endphp -->
										@foreach($cartItems->items as $item)
											<div class="product-widget">
												<div class="product-img">
													<img src="{{ Storage::disk('local')->url('product_images/'.$item['data'][0]['image']) }}" alt="">
												</div>
												<div class="product-body">
													<h3 class="product-name"><a href="#">{{ $item['data'][0]['name'] }}</a></h3>
													<h4 class="product-price"><span class="qty">{{ $item['quantity'] }}x</span>{{ $item['totalSinglePrice'] }}</h4>
												</div>
												<a href="{{ route('DeleteItemFromCart', ['id' => $item['data'][0]['id']]) }}"><button class="delete"><i class="fa fa-close"></i></button></a>
											</div>
										@endforeach
										</div>
										<div class="cart-summary">
											<small>{{ $totalQuantity }} Item(s) selected</small>
											<h5>SUBTOTAL: {{ $totalPrice }}</h5>
										</div>
										<div class="cart-btns">
											<a href="{{ route('viewCart') }}">View Cart</a>
											<a href="{{ route('cartproducts') }}">Checkout  <i class="fa fa-arrow-circle-right"></i></a>
										</div>
									</div>
								</div>
								<!-- /Cart -->

								<!-- Menu Toogle -->
								<div class="menu-toggle">
									<a href="#">
										<i class="fa fa-bars"></i>
										<span>Menu</span>
									</a>
								</div>
								<!-- /Menu Toogle -->
							</div>
						</div>
						<!-- /ACCOUNT -->
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- /MAIN HEADER -->
		</header>
		<!-- /HEADER -->
