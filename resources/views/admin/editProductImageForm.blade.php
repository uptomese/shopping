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
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Edit Image</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 order-xl-1">
                    <div class="card">
                        <div class="card-body">
                            @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    <li>{{!! print_r($errors->all()) !!}}</li>
                                </ul>
                            </div>
                            @endif
                            <div class="pl-lg-4">
                                <img id="blah" src="{{ asset('storage') }}/product_images/{{$product[0]['image']}}" alt=""
                                    width="auto" height="200">
                            </div>
                            <form action="/admin/updateImage/{{$product[0]['id']}}" method="POST"
                                enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="description">Update Image</label>
                                                <input type="file" class="form-control" name="image" id="image"
                                                    onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                                    placeholder="image" value="{{$product[0]['image']}}"
                                                    accept=".png, .jpg, .jpeg" require>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-info">Edit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
