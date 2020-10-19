  <!-- Footer -->
  <footer class="py-5" id="footer-main">
    <div class="container">
      <div class="row align-items-center justify-content-xl-between">
        <div class="col-xl-6">
          <div class="copyright text-center text-xl-left text-muted">
            &copy; 2020 <a href="#" class="font-weight-bold ml-1" >Nan Dev</a>
          </div>
        </div>
        <div class="col-xl-6">
          <ul class="nav nav-footer justify-content-center justify-content-xl-end">
            <li class="nav-item">
              <a href="#" class="nav-link" >Creative Tim</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link" >About Us</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link" >Blog</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link" >MIT License</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
  
 
  
  </div>

  <script>
    window.addEventListener("load", () => { 
			document.getElementById("loading").style.display = "none"; 
		}); 
  </script>

  <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>

  <script src="{{URL::asset('js/box.js')}} "></script>
  <script src="{{ asset('js/app.js') }}" ></script>
  <!-- <script src="{{ mix('js/app.js') }}"></script> -->

  <!-- Argon JS -->
  <!-- <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script> -->
  <script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>
  <script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
 
  <script src="{{ asset('assets/js/argon.js?v=1.2.0') }}"></script>

  <script>
  // Dynamic Password Strength Validation
  var result = $("#strength");
  
  $('#user_password').keyup(function(){
      $(".result").html(checkStrength($('#user_password').val()))
  })  

  function checkStrength(password){

  //initial strength
  var strength = 0
  
  if (password.length == 0) {
      result.removeClass()
      return ''
  }
  //if the password length is less than 6, return message.
  if (password.length < 6) {
      result.removeClass()
      result.addClass('short')
      return 'too short'
  }

  //length is ok, lets continue.

  //if length is 8 characters or more, increase strength value
  if (password.length > 7) strength += 1

  //if password contains both lower and uppercase characters, increase strength value
  if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1

  //if it has numbers and characters, increase strength value
  if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1 

  //if it has one special character, increase strength value
  if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1

  //if it has two special characters, increase strength value
  if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,",%,&,@,#,$,^,*,?,_,~])/)) strength += 1

  //now we have calculated strength value, we can return messages

  //if value is less than 2
  if (strength < 2) {
      result.removeClass()
      result.addClass('weak')
      return 'weak'
  } else if (strength == 2 ) {
      result.removeClass()
      result.addClass('good')
      return 'good'
  } else {
      result.removeClass()
      result.addClass('strong')
      return 'strong'
  }
}
  </script>
  
</body>

</html>

<style>
.str-box {
  position: relative;
  width: 100px;
  height: 12px;
  float: left;
}
.str-box div {
  position: absolute;
  width: 0%;
  height: 100%;
  -moz-transition: 1s;
  -o-transition: 1s;
  -webkit-transition: 1s;
  transition: 1s;
}

.short {
  color: #FF0000;
}
.short .str-box.box1 div {
  background: #f6dfc9;
  width: 100%;
}

.weak {
  color: #E66C2C;
}
.weak .str-box.box1 div {
  background: #f4c9a0;
  width: 100%;
}
.weak .str-box.box2 div {
  background: #f4c9a0;
  width: 100%;
}

.good {
  color: #2D98F3;
}
.good .str-box.box1 div {
  background: #f6b578;
  width: 100%;
}
.good .str-box.box2 div {
  background: #f6b578;
  width: 100%;
}
.good .str-box.box3 div {
  background: #f6b578;
  width: 100%;
}

.strong {
  color: #006400;
}
.strong .str-box.box1 div {
  background: #f29d4b;
  width: 100%;
}
.strong .str-box.box2 div {
  background: #f29d4b;
  width: 100%;
}
.strong .str-box.box3 div {
  background: #f29d4b;
  width: 100%;
}
.strong .str-box.box4 div {
  background: #f29d4b;
  width: 100%;
}

.result {
  font-size: 18px;
  font-family: arial;
  width: auto;
  display: none;
  -moz-transition: 0.5s;
  -o-transition: 0.5s;
  -webkit-transition: 0.5s;
  transition: 0.5s;
  font-variant: small-caps;
}
</style>
