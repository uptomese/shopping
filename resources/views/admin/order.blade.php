@extends('layouts/admin.admin_index')

@section('center')

<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-8 col-8">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adminDisplayOrders') }}">Orders</a></li>
                        </ol>
                    </nav>
                </div>

                <form action="/admin/orders" method="GET" style="width:100%" class="col-lg-2 col-2 text-right">
                    <!-- <input type="hidden" id="categories_checked" name="categories_checked"> -->
                    <select name="year" class="form-control" onchange="this.form.submit()">
                        @foreach($all_year as $key => $item)
                        @php $year_for = "20".$key ; @endphp
                            <option value="{{$year_for}}" @if($select_year == $year_for) selected @endif>{{$year_for}}</option>
                        @endforeach
                    </select>
                </form>

                <form action="/admin/orders" method="GET" style="width:100%" class="col-lg-2 col-2 text-right">
                    <input type="hidden" name="year" value="{{$select_year}}">
                    <select name="month" class="form-control" onchange="this.form.submit()">
                        <!-- <option value="every_month">Every month</option> -->
                        <option value="01" @if($date_m_now == '01') selected @endif>January @if($count_list_order[1] > 0) ( {{$count_list_order[1]}} ) @endif</option>
                        <option value="02" @if($date_m_now == '02') selected @endif>February @if($count_list_order[2] > 0) ( {{$count_list_order[2]}} ) @endif</option>
                        <option value="03" @if($date_m_now == '03') selected @endif>March @if($count_list_order[3] > 0) ( {{$count_list_order[3]}} ) @endif</option>
                        <option value="04" @if($date_m_now == '04') selected @endif>April @if($count_list_order[4] > 0) ( {{$count_list_order[4]}} ) @endif</option>
                        <option value="05" @if($date_m_now == '05') selected @endif>May @if($count_list_order[5] > 0) ( {{$count_list_order[5]}} ) @endif</option>
                        <option value="06" @if($date_m_now == '06') selected @endif>June @if($count_list_order[6] > 0) ( {{$count_list_order[6]}} ) @endif</option>
                        <option value="07" @if($date_m_now == '07') selected @endif>July @if($count_list_order[7] > 0) ( {{$count_list_order[7]}} ) @endif</option>
                        <option value="08" @if($date_m_now == '08') selected @endif>August @if($count_list_order[8] > 0) ( {{$count_list_order[8]}} ) @endif</option>
                        <option value="09" @if($date_m_now == '09') selected @endif>September @if($count_list_order[9] > 0) ( {{$count_list_order[9]}} ) @endif</option>
                        <option value="10" @if($date_m_now == '10') selected @endif>October @if($count_list_order[10] > 0) ( {{$count_list_order[10]}} ) @endif</option>
                        <option value="11" @if($date_m_now == '11') selected @endif>November @if($count_list_order[11] > 0) ( {{$count_list_order[11]}} ) @endif</option>
                        <option value="12" @if($date_m_now == '12') selected @endif>December @if($count_list_order[12] > 0) ( {{$count_list_order[12]}} ) @endif</option>
                    </select>
                </form>
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
                                    <h5 class="card-title text-uppercase text-muted mb-0">จ่ายเงินแล้ว / ยังไม่ได้จ่ายเงิน</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$order_paid}} / {{$order_paid_yet}}</span>
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
                                    <h5 class="card-title text-uppercase text-muted mb-0">ส่งของแล้ว / รอส่งของ</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$order_sened}} / {{$order_wait}}</span>
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
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">ผู้ใช้ในระบบ <spen>({{$orders_users_in->total()}} list)</spen></h3>
                        </div>
                        <div class="col-4 text-right">
                            <h5 class="text-muted"><i class="fa fa-circle font-10 m-r-10 text-success"></i> Paid</h5>
                        </div>
                    </div>
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
                            @foreach($orders_users_in as $key => $item)
                            <tr @if($item['status_payment'] ?? '' == 1 ) style="background-color: lightgreen;" @endif>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="/admin/order/{{$item['id']}}" class="avatar rounded-circle mr-3">
                                            <img alt="Image user"
                                                    src="{{ asset('storage') }}/user_images/{{$orders_users_in_user[$key]['user']['image'] ?? ''}}"
                                                    style="width:40px;height:40px;">
                                        </a>
                                        <div class="media-body">
                                            <a href="/admin/order/{{$item['id']}}"><span
                                                    class="name mb-0 text-sm">{{$orders_users_in_user[$key]['user']['name'] ?? ''}}</span></a>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    {{$item['date']}}
                                </td>
                                <!-- badge badge-dot mr-4 -->
                                <td>
                                    {{$item['address']}}&nbsp;(
                                    <span class="">
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
                            {{ $orders_users_in->appends(array('year' => $select_year, 'month' => $date_m_now))->links() }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Dark table -->
        <div class="col-xl-6">
            <div class="card bg-default shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0" style="color: white;">ผู้ใช้นอกระบบ <spen>({{$orders_users_out->total()}} list)</spen></h3>
                        </div>
                        <div class="col-4 text-right">
                            <h5 class="text-muted"><i class="fa fa-circle font-10 m-r-10 text-blue"></i> Paid</h5>
                        </div>
                    </div>
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
                                <!-- badge badge-dot mr-4 -->
                                <td>
                                    {{$item['address']}}&nbsp;(
                                    <span class="">
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
                            {{ $orders_users_out->appends(array('year' => $select_year, 'month' => $date_m_now))->links() }}
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
