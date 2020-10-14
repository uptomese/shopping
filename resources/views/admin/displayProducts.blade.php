@extends('layouts/admin.admin_index')

@section('center')

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-4 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adminDisplayProducts') }}">Products</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-8 col-5 text-right">
                    <!-- <select name="categorie_id" class="form-control">
                        <option value="11111">1111</option>
                        <option value="11111">1111</option>
                        <option value="11111">1111</option>
                    </select> -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="container col-12">
            @include('../alert')
        </div>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Products <spen>({{$products->total}} list)</spen></h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route('adminCreateProductForm') }}" class="btn btn-sm btn-primary">New</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Id.</th>
                                <th scope="col">Name product</th>
                                <th scope="col">Description</th>
                                <th scope="col">Categorie</th>
                                <th scope="col">price</th>
                                <th scope="col">stock</th>
                                <th scope="col">image</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products->items as $key => $item)
                            <tr>
                                <td>
                                    <p>{{$item['product_id']}}</p>
                                </td>
                                <th scope="row">
                                    <p>{{$item['product_name']}}</p>
                                </th>
                                <td>
                                    <p>{{$item['description']}}</p>
                                </td>
                                <td>
                                    <p>{{$item['categorie_name']}}</p>
                                </td>
                                <td>
                                    <p>${{$item['price']}}</p>
                                </td>
                                <td>
                                    <p>{{$item['stock']}}</p>
                                </td>
                                <td scope="row">
                                    @if(gettype($item['image'])=="array")
                                    <div class="avatar-group">
                                        @foreach($item['image'] as $key => $array_image)                            
                                        <a href="#" class="avatar avatar-sm rounded-circle media align-items-center" data-toggle="tooltip" data-original-title="{{$array_image}}">
                                            <img alt="Image placeholder" src="{{ asset('storage') }}/product_images/{{$item['product_id']}}/{{$item['image'][$key]}}" style="width:50px;height:50px;">
                                        </a>
                                        @endforeach
                                    </div>
                                    @else
                                    <img class="avatar avatar-sm rounded-circle media align-items-center" alt="Image placeholder" src="{{ asset('storage') }}/product_images/{{$item['image']}}" style="width:50px;height:50px;">
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('adminEditProductImageForm', ['id' => $item['product_id']]) }}" class="btn btn-sm btn-outline-success"> <i class="fa fa-image"></i> รูป</a>
                                    <a href="{{ route('adminEditProductForm', ['id' => $item['product_id']]) }}" class="btn btn-sm btn-outline-warning"> <i class="fa fa-edit"></i> แก้ไข</a>
                                    <a href="{{ route('adminDeleteProduct', ['id' => $item['product_id']]) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"> <i class="fa fa-trash"></i> ลบ</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end mb-0">
                            @foreach ($products->links as $key => $values)
                            <li class="{{$values['stly_classes']}}">
                                <a class="page-link" href="?page={{$values['page']}}">{{ $values['icon'] }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </nav>
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
    v-on:delete_message="delMessage"
></chat-component>

@endsection
