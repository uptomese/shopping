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
            </div>
        </div>
    </div>

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
    function Add() { 
        ++i;
        let result = '';
        header.forEach(function(value, index) {
            var td = '<td><input type="text" name="addmore['+i+']['+value+']" placeholder="Enter '+value+'" class="form-control" /></td>';
            result += td;
        });
        $("#dynamicTable").append('<tr>'+result+'<td><button type="button" class="btn btn-danger" onclick="deleteRow(this)">Remove</button></td></tr>');
    }

    function upTo(el, tagName) {
        tagName = tagName.toLowerCase();

        while (el && el.parentNode) {
            el = el.parentNode;
            if (el.tagName && el.tagName.toLowerCase() == tagName) {
            return el;
            }
        }
        return null;
    } 

    function deleteRow(el) {
        var row = upTo(el, 'tr')
        if (row) row.parentNode.removeChild(row);
    }



    </script>

    <!-- <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{URL::asset('js/box.js')}} "></script>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>


    <!-- Argon Scripts -->
    <!-- Core -->

    <!-- <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script> -->
    <script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
    <!-- Optional JS -->
    <script src="{{ asset('assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
    <!-- Argon JS -->
    <script src="{{ asset('assets/js/argon.js?v=1.2.0') }}"></script>

    </body>

    </html>
