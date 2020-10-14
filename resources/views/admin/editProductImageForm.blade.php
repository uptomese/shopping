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
                            <li class="breadcrumb-item active" aria-current="page">{{ $product[0]['name'] }}</li>
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
                            <h5 class="h3 mb-0">Edit Image</h5>
                        </div>
                        <div class="col text-right">
                            @if(gettype($product[0]['image'])=="array")
                            <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('image_file').click();">New</a>
                            <form action="/admin/addImage/{{$product[0]['id']}}" method="POST" enctype="multipart/form-data">
                            {{csrf_field()}}
                                <input name="image" id="image_file" type="file" accept=".jpg, .jpeg, .png" style="display:none;" onchange="form.submit()"/>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(gettype($product[0]['image'])=="array")
                    <table class="table">
                        <thead>
                            <tr>
                                <th>image</th>
                                <th>edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product[0]['image'] as $key => $item)
                            <tr>
                                <td style="text-align:center;">
                                    <img id="blah[{{$key}}]" src="{{ asset('storage') }}/product_images/{{$product[0]['id']}}/{{$item}}" alt="" width="auto" height="200">
                                </td>
                                <td>
                                    <div class="pl-lg-4">
                                        <form action="/admin/updateImage/{{$product[0]['id']}}" method="POST" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="description">{{$product[0]['image'][$key]}}</label>
                                                        <input type="hidden" name="type_image" value="image_b">
                                                        <input type="hidden" name="index" value="{{$key}}">
                                                        <input type="hidden" name="file_name" value="{{$product[0]['image'][$key]}}">
                                                        <input type="file" class="form-control" name="image" id="image"
                                                            onchange="document.getElementById('blah[{{$key}}]').src = window.URL.createObjectURL(this.files[0])"
                                                            placeholder="image" value="{{$key}}"
                                                            accept=".png, .jpg, .jpeg" require>
                                                    </div>
                                                    <!-- placeholder="image" value="{{$product[0]['image'][$key]}}" -->
                                                </div>
                                            <button type="submit" name="submit" class="btn btn-info">Save</button>
                                            </div>
                                        </form>
                                        <br>
                                        <div class="row">
                                            <a href="../deleteImage/{{$product[0]['id']}}/{{$key}}"><button type="btn" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delte</button></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="pl-lg-4">
                        <img id="blah" src="{{ asset('storage') }}/product_images/{{$product[0]['image']}}" alt=""
                            width="auto" height="200">
                    </div>
                    <form action="/admin/updateImage/{{$product[0]['id']}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="description">Update Image</label>
                                        <input type="hidden" name="type_image" value="image_a">
                                        <input type="file" class="form-control" name="image" id="image"
                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                            placeholder="image" value="image_a" accept=".png, .jpg, .jpeg" require>
                                    </div>
                                    <!-- placeholder="image" value="{{$product[0]['image']}}" -->
                                </div>
                            </div>
                            <button type="submit" name="submit" class="btn btn-info">Edit</button>
                        </div>
                    </form>
                    @endif
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

@endsection
