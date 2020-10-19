@extends('layouts/admin.admin_index')

@section('center')

<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adminDisplayOrders') }}">Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$user['name']}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-md-4">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Price Paid</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $total_price }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape  bg-gradient-green text-white rounded-circle shadow">
                                        <i class="ni ni-check-bold"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Orders Paid</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $count }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-green bg-gradient-orange text-white rounded-circle shadow">
                                        <i class="ni ni-box-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Order</h3>
                </div>
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Id.</th>
                                <th scope="col">Image</th>
                                <th scope="col">Products</th>
                                <th scope="col">Date</th>
                                <th scope="col">Delivery Status</th>
                                <th scope="col">At delivery</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($array_orders as $items)
                            <tr style="@if($items['order']['status_payment'] ?? '' == 1 )background-color: lightgreen; @endif">
                                <th scope="row">
                                    <a href="/admin/order/{{$items['order']['id']}}">
                                        {{$items['order']['id']}}
                                    </a>
                                </th>
                                <td>
                                    <div class="avatar-group">
                                        @foreach($items['items'] as $item)                                                          
                                        <a href="#" class="avatar avatar-lg" data-toggle="tooltip" data-original-title="{{$item['product_name']}} ({{$item['product_quantity']}}) (${{$item['product_price']}})">
                                            @if (isset($item['image']))
                                                @if(gettype($item['image'])=="array")
                                                    @foreach(array_slice($item['image'],0,1) as $image_array) 
                                                        <img alt="Image placeholder" src="{{ asset('storage') }}/product_images/{{$item['product_id']}}/{{$image_array}}" alt="" style="width:60px;height:60px;">
                                                    @endforeach                           
                                                @else
                                                <img alt="Image placeholder" src="{{ Storage::disk('local')->url('product_images/'.$item['image']) }}" alt="" style="width:60px;height:60px;">
                                                @endif
                                            @endif
                                        </a>       
                                        @endforeach 
                                    </div>
                                </td>
                                <td>
                                    <ul>
                                    @foreach($items['items'] as $item)  
                                        <li>{{$item['product_name']}} ({{$item['product_quantity']}}) (${{$item['product_price']}})</li>
                                    @endforeach 
                                    </ul>
                                </td>
                                <td>
                                    {{$items['order']['date']}}
                                </td>
                                <td>      
                                <!-- badge badge-dot mr-4                                   -->
                                    <span class="">
                                        @if($items['order']['status']=='wait')
                                        <i class="bg-warning"></i>
                                        @else
                                        <i class="bg-success"></i>
                                        @endif
                                        <span class="status">{{$items['order']['status']}}</span>
                                    </span>
                                </td>
                                <td>{{$items['order']['address']}}</td>
                                <td>${{$items['order']['price']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Card footer -->
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end mb-0">
                        @foreach ($orders->links as $key => $values)
                            <li class="{{$values['stly_classes']}}">
                                <a class="page-link" href="?page={{$values['page']}}">{{ $values['icon'] }}</a>
                            </li>
                        @endforeach                     
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-xl-4 order-xl-2">
            <div class="card card-profile">
                <img src="{{ asset('assets/img/theme/img-1-1000x600.jpg') }}" alt="Image placeholder"
                    class="card-img-top">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                            <a href="#">
                                <img src="{{ asset('storage') }}/user_images/{{$user['image']}}" class="rounded-circle" style="width:150px;height:140px;">

                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                    <div class="d-flex justify-content-between">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary  mr-4 ">Back</a>
                        @if($user['name']!="admin")
                        <a href="/admin/update_user/{{$user['id']}}" class="btn btn-sm btn-secondary float-right">Profile</a>
                        @endif
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="text-center">
                        <br>
                        <h5 class="h3">
                            {{$user['name']}}
                        </h5>
                        <div class="h5 font-weight-300">
                            <i class="ni location_pin mr-2"></i>{{$user['email']}}
                        </div>
                        <div class="h2 mt-4">
                            <i class="ni business_briefcase-24 mr-2"></i>
                            @if(isset($user['address'][0]) && $user['address'][0] == 'address_a')                            
                            {{$user['address'][1] ?? ''}}
                            @else
                            {{$user['address'][2] ?? ''}}
                            @endif
                        </div>
                        <div>
                            <i class="ni education_hat mr-2"></i>{{$user['phone'] ?? ''}}
                        </div>
                    </div>
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
