@extends('layouts/login.login_index')

@section('center')
<div class="main-content">
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
                xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </div>
    <div class="container mt--8 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3 class="mb-0">Welcome</h3></div>
                        <div class="card-body">
                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif
                            <div class="row ">
                                <div class=" col-6">
                                    <p>Name : {{ Auth::user()->name }}</p>
                                    <p>Email : {{ Auth::user()->email }}</p>
                                </div>
                                <div class=" col-6">
                                    @if(isset(Auth::user()->address[0]))
                                        <p>Address : @if(Auth::user()->address[0] == 'address_a') {{ Auth::user()->address[1] }} @else {{ Auth::user()->address[2] }} @endif</p>
                                    @endif                                    
                                    <p>Phone Number : {{ Auth::user()->phone }}</p>
                                </div>
                            </div>
                            <hr>
                            <a href="{{ route('allProducts')}}" class="btn btn-sm btn-outline-info"> <i class="fa fa-image"></i> Main Website</a>
                            @if($userData->isAdmin())
                            <a href="{{ route('adminDisplayProducts')}}" class="btn btn-sm btn-outline-success"> <i class="fa fa-image"></i> Admin Dashboard</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                    @if(isset($array_orders) && isset($orders))
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">My Orders</spen></h3>
                                </div>
                                <div class="col-4 text-right">
                                    <h5 class="text-muted"><i class="fa fa-circle font-10 m-r-10 text-success"></i> Paid</h5>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <!-- Projects table -->
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
                                <tbody>
                                    @foreach($array_orders as $items)
                                    <tr style="@if($items['order']['status_payment'] ?? '' == 1 )background-color: lightgreen; @endif">
                                        <th scope="row">
                                            {{$items['order']['id']}}
                                        </th>
                                        <td>
                                            <div class="avatar-group">
                                                @foreach($items['items'] as $item)                                                          
                                                <a class="avatar avatar-lg" data-toggle="tooltip" data-original-title="{{$item['product_name']}} ({{$item['product_quantity']}}) (${{$item['product_price']}})">
                                                    @if(isset($item['image']))
                                                        @if(gettype($item['image'])=="array")
                                                            @foreach(array_slice($item['image'],0,1) as $image_array)
                                                                <img alt="Image" src="{{ asset('storage') }}/product_images/{{$item['product_id']}}/{{$image_array}}" alt="" style="width:60px;height:60px;">
                                                            @endforeach                   
                                                        @else
                                                            <img alt="Image" src="{{ Storage::disk('local')->url('product_images/'.$item['image']) }}" alt="" style="width:60px;height:60px;">
                                                        @endif
                                                    @else
                                                    <img alt="Image" src="{{ Storage::disk('local')->url('product_images/product-default.jpg') }}" alt="" style="width:60px;height:60px;">
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
                    @endif
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
