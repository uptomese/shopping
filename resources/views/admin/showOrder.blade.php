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
                            <li class="breadcrumb-item"><a href="{{ route('adminDisplayOrders') }}">Order</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$id}}</li>
                        </ol>
                    </nav>
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
                                <th scope="col" class="sort" data-sort="name">Product name</th>
                                <th scope="col" class="sort" data-sort="budget">Product quantity</th>
                                <th scope="col" class="sort" data-sort="status">Product price</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($order_items as $item)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="#" class="avatar rounded-circle mr-3">
                                            <img alt="Image placeholder"
                                                src="{{ asset('storage') }}/product_images/{{$item['image'] ?? ''}}" style="width:40px;height:40px;">
                                        </a>
                                        <div class="media-body">
                                            <span class="name mb-0 text-sm">{{$item['product_name']}}</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    {{$item['product_quantity']}}
                                </td>
                                <td class="budget">
                                    ${{$item['product_price']}}
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Card footer -->
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end mb-0">

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
                                @if($user_order['image']=='no')
                                <img src="{{ asset('storage') }}/user_images/default.jpg" class="rounded-circle"
                                    style="width:150px;height:140px;">
                                @else
                                <img src="{{ asset('storage') }}/user_images/{{$user_order['image']}}"
                                    class="rounded-circle" style="width:150px;height:140px;">
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                    <div class="d-flex justify-content-between">
                    @if($user_order['image']!='no')
                        <a href="#" class="btn btn-sm btn-info  mr-4 ">Profile</a>
                        <a href="#" class="btn btn-sm btn-danger float-right">Block</a>
                    @endif
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="text-center">
                        <br>
                        <h5 class="h3">
                            {{$user_order['name']}}
                        </h5>
                        <div class="h5 font-weight-300">
                            <i class="ni location_pin mr-2"></i>{{$user_order['email']}}
                        </div>
                        <div class="h2 mt-4">
                            <i class="ni business_briefcase-24 mr-2"></i>{{$user_order['address'] ?? ''}}
                        </div>
                        <div>
                            <i class="ni education_hat mr-2"></i>{{$user_order['phone'] ?? ''}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection
