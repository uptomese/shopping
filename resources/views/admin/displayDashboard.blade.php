@extends('layouts/admin.admin_index')

@section('center')

<!-- Header -->
<div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-10 col-10">
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="#">Dashboards</a></li>
                  <!-- <li class="breadcrumb-item active" aria-current="page">Default</li> -->
                </ol>
              </nav>
            </div>
            <form action="{{ route('adminDashboardSelectYear') }}" method="GET" style="width:100%" class="col-lg-2 col-2 text-right">
                <!-- <input type="hidden" id="categories_checked" name="categories_checked"> -->
                <select name="year" class="form-control" onchange="this.form.submit()">
                    @foreach($all_year as $key => $item)
                    @php $year_for = "20".$key ; @endphp
                        <option value="{{$year_for}}" @if($select_year == $year_for) selected @endif>{{$year_for}}</option>
                    @endforeach
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
                      <h5 class="card-title text-uppercase text-muted mb-0">Total traffic</h5>
                      <span class="h2 font-weight-bold mb-0">{{$total}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">All Orders</h5>
                      <span class="h2 font-weight-bold mb-0">{{$all_orders}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="ni ni-chart-pie-35"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Sales</h5>
                      <span class="h2 font-weight-bold mb-0">924</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                        <i class="ni ni-money-coins"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Performance</h5>
                      <span class="h2 font-weight-bold mb-0">49,65%</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                        <i class="ni ni-chart-bar-32"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                  </p>
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-8">
        <!-- bg-default -->
          <div class="card">
          <!-- bg-transparent -->
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
                  <!-- h3 text-white mb-0 -->
                  <h5 class="h3 mb-0">Sales value</h5>
                  @php 
                  $value_total_month = json_encode($total_month); 
                  $value_total_order_count = json_encode($total_order_count); 
                  @endphp
                </div>
                <div class="col">
                  <ul class="nav nav-pills justify-content-end">
                    <li id="fmp-button" class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales-dark" data-update='{"data":{"datasets":[{"data": @php echo $value_total_month; @endphp }]}}' data-prefix="$" data-suffix=" THB">
                      <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                        <span class="d-none d-md-block">Price</span>
                        <span class="d-md-none">M</span>
                      </a>
                    </li>
                    <li class="nav-item" data-toggle="chart" data-target="#chart-sales-dark" data-update='{"data":{"datasets": [{"data": @php echo $value_total_order_count; @endphp }]}}' data-prefix="" data-suffix=" list">
                      <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                        <span class="d-none d-md-block">Orders</span>
                        <span class="d-md-none">W</span>
                      </a>
                    </li>            
                  </ul>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="chart-sales-dark" class="chart-canvas"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                  <h5 class="h3 mb-0">Top 3 categories</h5>                  
                </div>
                <div class="col">
                  <ul class="nav nav-pills justify-content-end">
                    <li id="fmp-button2" class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-bars" 
                    data-update='{
                      "data": {
                        "datasets": [{"data": @php echo json_encode($categorie_quantity[1]); @endphp }],
                        "labels": @php echo json_encode($categorie_quantity[0]); @endphp 
                        }
                      }' 
                    data-prefix="" data-suffix="">
                      <!-- <a href="#"  class="nav-link py-2 px-3" data-toggle="tab"> -->
                      <a href="{{ route('adminDisplayDashboards') }}" type="button" class="nav-link py-2 px-3 btn-outline-primary">
                        <span class="d-none d-md-block">Refresh</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="chart">
                  <canvas id="chart-bars" class="chart-canvas"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script>
        window.onload = (event) => {
          document.getElementById("fmp-button").click();
          document.getElementById("fmp-button2").click();
        };
      </script>
      <div class="row">
        <div class="col-xl-8">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Ranking of users with the highest purchases</h3>
                </div>
                <!-- <div class="col text-right">
                  <a href="#!" class="btn btn-sm btn-primary">See all</a>
                </div> -->
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Price</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($arr_top_users as $item)
                  <tr>
                    <th scope="row">
                      {{$item['user_name']}}
                    </th>
                    <td>
                      {{$item['user_email']}}
                    </td>
                    <td>
                      ${{$item['orders_price']}}
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Best selling products</h3>
                </div>
                <!-- <div class="col text-right">
                  <a href="#!" class="btn btn-sm btn-primary">See all</a>
                </div> -->
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Products</th>
                    <th scope="col">Quantity</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($arr_top_product as $item)
                  <tr>
                    <th scope="row">
                      {{$item['products_name']}}
                    </th>
                    <td>
                      {{$item['product_quantity']}}
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <span class="mr-2">{{$item['percen']}}%</span>
                        <div>
                          <div class="progress">
                            <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{$item['percen']}}%;"></div>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
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
