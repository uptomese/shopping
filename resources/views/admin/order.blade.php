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
                            <li class="breadcrumb-item"><a href="{{ route('adminDisplayOrders') }}">Orders</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0"><b>วันนี้</b> จ่ายเงินแล้ว / ออเดอร์</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$order_paid_toDay}} / {{$order_toDay}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                        <i class="ni ni-cart"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <!-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                <span class="text-nowrap">Since last month</span> -->
                            </p>
                        </div>
                    </div>
                </div>                
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">จ่ายเงินแล้ว / ส่งของแล้ว</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$order_paid}} / {{$order_sened}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                        <i class="ni ni-check-bold"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <!-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                <span class="text-nowrap">Since last month</span> -->
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">ยังไม่ได้จ่ายเงิน / รอส่งของ</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$order_paid_yet}} / {{$order_wait}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                        <i class="ni ni-fat-remove"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <!-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                <span class="text-nowrap">Since last month</span> -->
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">ออเดอร์ทั้งหมด</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$order_total}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="ni ni-app"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <!-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                <span class="text-nowrap">Since last month</span> -->
                            </p>
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
        <div class="col-xl-6">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">ผู้ใช้ในระบบ <spen>({{$orders_users_in->total}} list)</spen>
                </div>
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Order.</th>
                                <th scope="col" class="sort" data-sort="budget">Date</th>
                                <th scope="col" class="sort" data-sort="status"> At / Status ,Delivery</th>
                                <th scope="col" class="sort" data-sort="status">Quantity</th>
                                <th scope="col" class="sort" data-sort="price">Price</th>
                                <!-- <th scope="col" class="sort" data-sort="name">Name</th> -->
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($orders_users_in->items as $item)
                            <tr @if($item['status_payment'] ?? '' == 1 ) style="background-color: lightgreen;" @endif>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="/admin/order/{{$item['id']}}" class="avatar rounded-circle mr-3">
                                            <img alt="Image user"
                                                    src="{{ asset('storage') }}/user_images/{{$item['image'] ?? ''}}"
                                                    style="width:40px;height:40px;">
                                        </a>
                                        <div class="media-body">
                                            <a href="/admin/order/{{$item['id']}}"><span
                                                    class="name mb-0 text-sm">{{$item['name'] ?? ''}}</span></a>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    {{$item['date']}}
                                </td>
                                <td>
                                    {{$item['address']}}&nbsp;(
                                    <span class="badge badge-dot mr-4">
                                        @if($item['status']=='wait')
                                        <i class="bg-warning"></i>
                                        @else
                                        <i class="bg-success"></i>
                                        @endif
                                        <span class="status">{{$item['status']}} )</span>
                                    </span>                                    
                                </td>
                                <td class="budget">
                                    {{$item['quantity']}}
                                </td>
                                <td class="budget">
                                    ${{$item['price']}}
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            @if($item['status']!='success')
                                            <a class="dropdown-item"
                                                href="/admin/update_order_success/{{$item['id']}}">Success</a>
                                            @else
                                            <a class="dropdown-item"
                                                href="/admin/update_order_wait/{{$item['id']}}">Wait</a>
                                            @endif
                                            <a class="dropdown-item" href="/admin/order/{{$item['id']}}">View</a>
                                            <!-- <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a> -->
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
                            @foreach ($orders_users_in->links as $key => $values)
                            <li class="{{$values['stly_classes']}}">
                                <a class="page-link" href="?page={{$values['page']}}">{{ $values['icon'] }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Dark table -->
        <div class="col-xl-6">
            <div class="card bg-default shadow">
                <div class="card-header bg-transparent border-0">
                    <h3 class="mb-0" style="color: white;">ผู้ใช้นอกระบบ <spen>({{$orders_users_out->total()}} list)</spen>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Order.</th>
                                <th scope="col" class="sort" data-sort="budget">Date</th>
                                <th scope="col" class="sort" data-sort="status">At / Status ,Delivery</th>
                                <th scope="col" class="sort" data-sort="status">Quantity</th>
                                <th scope="col" class="sort" data-sort="price">Price</th>
                                <!-- <th scope="col" class="sort" data-sort="name">Name</th> -->
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($orders_users_out as $item)
                            <tr @if($item['status_payment'] ?? '' == 1 ) style="background-color: darkblue;" @endif>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="/admin/order/{{$item['id']}}" class="avatar rounded-circle mr-3">
                                            <img alt="Image placeholder"
                                                src="{{ asset('storage') }}/user_images/default.jpg"
                                                style="width:40px;height:40px;">
                                        </a>
                                        <div class="media-body">
                                            <a href="/admin/order/{{$item['id']}}"><span
                                                    class="name mb-0 text-sm">{{$item['full_name']}}</span></a>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    {{$item['date']}}
                                </td>
                                <td>
                                    {{$item['address']}}&nbsp;(
                                    <span class="badge badge-dot mr-4">
                                        @if($item['status']=='wait')
                                        <i class="bg-warning"></i>
                                        @else
                                        <i class="bg-success"></i>
                                        @endif
                                        <span class="status">{{$item['status']}} )</span>
                                    </span>  
                                </td>
                                <td class="budget">
                                    {{$item['quantity']}}
                                </td>
                                <td class="budget">
                                    ${{$item['price']}}
                                </td>

                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            @if($item['status']!='success')
                                            <a class="dropdown-item"
                                                href="/admin/update_order_success/{{$item['id']}}">Success</a>
                                            @else
                                            <a class="dropdown-item"
                                                href="/admin/update_order_wait/{{$item['id']}}">Wait</a>
                                            @endif
                                            <a class="dropdown-item" href="/admin/order/{{$item['id']}}">View</a>
                                            <!-- <a class="dropdown-item" href="#">Something else here</a> -->
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
                            {{$orders_users_out->links()}}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @endsection
