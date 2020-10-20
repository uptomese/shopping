<footer id="footer">
			<!-- top footer -->
			<div class="section">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">About Us</h3>
								<p>{{$companyData[0]}}</p>
								<ul class="footer-links">
									<li><a href="#"><i class="fa fa-map-marker"></i>{{$companyData[1]}}</a></li>
									<li><a href="#"><i class="fa fa-phone"></i>{{$companyData[2]}}</a></li>
									<li><a href="#"><i class="fa fa-envelope-o"></i>{{$companyData[3]}}</a></li>
								</ul>
							</div>
						</div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Categories</h3>
								<ul class="footer-links">
									<li><a href="#">Hot deals</a></li>
									<li><a href="#">Laptops</a></li>
									<li><a href="#">Smartphones</a></li>
									<li><a href="#">Cameras</a></li>
									<li><a href="#">Accessories</a></li>
								</ul>
							</div>
						</div>

						<div class="clearfix visible-xs"></div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Information</h3>
								<ul class="footer-links">
									<li><a href="#">About Us</a></li>
									<li><a href="#">Contact Us</a></li>
									<li><a href="#">Privacy Policy</a></li>
									<li><a href="#">Orders and Returns</a></li>
									<li><a href="#">Terms & Conditions</a></li>
								</ul>
							</div>
						</div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Service</h3>
								<ul class="footer-links">
									<li><a href="#">My Account</a></li>
									<li><a href="#">View Cart</a></li>
									<li><a href="#">Wishlist</a></li>
									<li><a href="#">Track My Order</a></li>
									<li><a href="#">Help</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /row -->
				</div>
				<!-- /container -->
			</div>
			<!-- /top footer -->

			<!-- bottom footer -->
			<div id="bottom-footer" class="section">
				<div class="container">
					<!-- row -->
					<div class="row">
						<div class="col-md-12 text-center">
							<ul class="footer-payments">
								<li><a href="#"><i class="fa fa-cc-visa"></i></a></li>
								<li><a href="#"><i class="fa fa-credit-card"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-paypal"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-mastercard"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-discover"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-amex"></i></a></li>
							</ul>
							<span class="copyright">
								<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
								Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
							<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
							</span>
						</div>
					</div>
						<!-- /row -->
				</div>
				<!-- /container -->
			</div>
			<!-- /bottom footer -->
		</footer>
		<!-- /FOOTER -->

		<!-- jQuery Plugins -->
		<script src="{{asset('js/jquery.min.js')}}"></script>
		<script src="{{asset('js/bootstrap.min.js')}}"></script>
		<script src="{{asset('js/slick.min.js')}}"></script>
		<script src="{{asset('js/nouislider.min.js')}}"></script>
		<script src="{{asset('js/jquery.zoom.min.js')}}"></script>
		<script src="{{asset('js/main.js')}}"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js" integrity="sha512-rmZcZsyhe0/MAjquhTgiUcb4d9knaFc7b5xAfju483gbEXTkeJRUMIPk6s3ySZMYUHEcjKbjLjyddGWMrNEvZg==" crossorigin="anonymous"></script>
	
	
		<script>
		$(document).ready(function() {
			var interval = setInterval(function() {
				var momentNow = moment();
				$('#time-dd').html(momentNow.format('DD'));
				$('#time-hh').html(momentNow.format('hh'));
				$('#time-mm').html(momentNow.format('mm'));
				$('#time-ss').html(momentNow.format('ss'));
			}, 100);

			var array_choice = []
			var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')

			for (var i = 0; i < checkboxes.length; i++) {
				array_choice.push(checkboxes[i].value)
			}

			document.getElementById("categories_checked").value = array_choice;
		});

		// ------------------

		window.addEventListener("load", () => { 
			document.getElementById("loading").style.display = "none"; 
		}); 

		</script>

	</body>
</html>

<style>
.table-details {
  overflow: hidden;
}

.table-details tr:hover {
  background-color: #D3D3D3;
}

.table-details td,.table-details th {
  position: relative;
}
.table-details td:hover::after,
.table-details th:hover::after {
  content: "";
  position: absolute;
  background-color: #D3D3D3;
  left: 0;
  top: -5000px;
  height: 10000px;
  width: 100%;
  z-index: -1;
}

.dropdown-menu .cart-list .product-widget .product-body .product-price {
    color: #2b2d42;
}

.dropdown-menu .cart-btns {
    margin: 0px -17px -17px;
}

.dropdown-menu .cart-btns>a {
    display: inline-block;
    width: calc(50% - 0px);
    padding: 12px;
    background-color: #d10024;
    color: #fff;
    text-align: center;
    font-weight: 700;
    -webkit-transition: 0.2s all;
    transition: 0.2s all;
}

.dropdown-menu .cart-btns>a:first-child {
    margin-right: -4px;
    background-color: #1e1f29;
}

.dropdown-menu .cart-btns>a:hover {
    opacity: 0.9;
}

.dropdown-menu .cart-summary {
    border-top: 1px solid #e4e7ed;
    padding-top: 15px;
    padding-bottom: 15px;
}

</style>



