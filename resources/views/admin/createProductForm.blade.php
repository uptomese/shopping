@extends('layouts/admin.admin_index')

@section('center')

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adminDisplayProducts') }}">Products</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row ">
        <div class="col-xl-12">
            <div class="container col-12">
                @include('../alert')
            </div>
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Product</h6>
                            <h5 class="h3 mb-0">Create</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            <li>{{!! print_r($errors->all()) !!}}</li>
                        </ul>
                    </div>
                    @endif
                    <form action="/admin/sendCreateProductForm" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <!-- Address -->
                        <!-- <h6 class="heading-small text-muted mb-4">Product</h6> -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-city">Name</label>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-country">Categorie</label>
                                        <select name="categorie_id" class="form-control">
                                            @foreach($categories as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-country">Price</label>
                                        <input type="number" id="price" name="price" class="form-control" step=0.1 placeholder="Price" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-country">Stock</label>
                                        <input type="number" id="stock" name="stock" class="form-control" min=0 step=1 placeholder="Stock" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <!-- <h6 class="heading-small text-muted mb-4">Description</h6> -->
                        <div class="pl-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Description</label>
                                <textarea rows="4" id="description" name="description" class="form-control" placeholder="description of product ..."></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-city">STANDARD</label>
                                        <input type="text" name="standard" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-city">MATERIAL</label>
                                        <input type="text" name="material" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-city">COATING</label>
                                        <input type="text" name="coating" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-city">CODE</label>
                                        <input type="text" name="code" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-city">UPDATE</label>
                                        <input type="date" name="update" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-8">   
                                    <div class="form-group">
                                        <label class="form-control-label">Image</label>
                                    </div>                                    
                                    <div class="row-image">
                                        <div id="myImg" class="column-image">

                                        </div>
                                    </div>      
                                    <hr class="my-4" />
                                    <input type="file" class="form-control" name="image[]" id="image"
                                        multiple="multiple"
                                        onclick="checkFile()"
                                        placeholder="image" accept=".png, .jpg, .jpeg" required>
                                </div>
                            </div>
                            <hr class="my-4" />
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="input-group mb-1">
                                            <input id="add_header" type="text" class="form-control" placeholder="Header (Do not have '.' )" aria-label="Header" aria-describedby="button-addon2">
                                            <div class="input-group-append">
                                                <button onclick="addHeader()" class="btn btn-outline-primary" type="button">Add header</button>
                                            </div>&nbsp;&nbsp; &nbsp; &nbsp;
                                            <button onclick="Add()" type="button" name="add" id="add" class="btn btn-success">Add More</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered" id="dynamicTable">

                                </table>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <button type="submit" name="submit" class="btn btn-info">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



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
        v-on:delete_message="delMessage">
    </chat-component>

    <script>

        var count = 0;

        $(function() {
            $(":file").change(function() {
                if (this.files && this.files[0]) {
                    count = this.files.length;
                    for (var i = 0; i < this.files.length; i++) {
                        var reader = new FileReader();
                        reader.onload = imageIsLoaded;
                        reader.readAsDataURL(this.files[i]);
                    }
                }
            });
        });

        var i = 0
        function imageIsLoaded(e) {
            var num = 100 / count;
            if(count > 4){
                var x = $('#myImg').append("<img id='file_image["+i+"]' src='"+e.target.result+"' style='width:25%;height:auto;padding:10px;border-radius:20px;'>");
            }else{
                var x = $('#myImg').append("<img id='file_image["+i+"]' src='"+e.target.result+"' style='width:"+num+"%;height:auto;padding:10px;border-radius:20px;'>");
            }

            i++;
        };

        function checkFile() {
            for (var j = 0; j < i; j++) {
                var x = document.getElementById("file_image["+j+"]");
                if(x){
                    x.remove(x.selectedIndex);
                }
            }
            i = 0;
        }
        
    </script>

    @endsection
