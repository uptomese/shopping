    <!-- Footer -->
    <footer class="footer pt-0">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6">
                <div class="copyright text-center  text-lg-left  text-muted">
                    &copy; 2020 <a href="#" class="font-weight-bold ml-1">Nan Dev</a>
                </div>
            </div>
            <div class="col-lg-6">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                    <li class="nav-item">
                        <a href="#" class="nav-link">Creative Tim</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">MIT License</a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

</div>

<!-- Argon Scripts -->
<!-- Core -->
<script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
<!-- Optional JS -->
<script src="{{ asset('assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
<!-- Argon JS -->
<script src="{{ asset('assets/js/argon.js?v=1.2.0') }}"></script>

<script type="text/javascript">

    let header = [];
    
    function addHeader() {
        var text = document.getElementById("add_header").value;
        header.push(text);
        document.getElementById("add_header").value = '';
        if(header) {
            var tr = '<th>'+text+'<button type="button" id="'+text+'" class="btn btn-danger remove-th float-right">Remove</button></th>'
            $("#dynamicTable").append(tr);
        }
        $(document).on('click', '.remove-th', function(event){  
            $(this).parents('th').remove();
            var name_header = jQuery(this).attr("id");
            removeA(header, name_header);
        }); 
    }

    function removeA(arr) {
        var what, a = arguments, L = a.length, ax;
        while (L > 1 && arr.length) {
            what = a[--L];
            while ((ax= arr.indexOf(what)) !== -1) {
                arr.splice(ax, 1);
            }
        }
        return arr;
    }

    var i = 0;
    $("#add").click(function(){
        ++i;
        let result = '';
        header.forEach(function(value, index) {
            var td = '<td><input type="text" name="addmore['+i+']['+value+']" placeholder="Enter '+value+'" class="form-control" /></td>';
            result += td;
        });
        $("#dynamicTable").append('<tr>'+result+'<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
    });

    $(document).on('click', '.remove-tr', function(){  
         $(this).parents('tr').remove();
    }); 

    

</script>

</body>

</html>