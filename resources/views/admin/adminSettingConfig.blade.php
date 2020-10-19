@extends('layouts/admin.admin_index')

@section('center')

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <!-- <h6 class="h2 text-white d-inline-block mb-0">Default</h6> -->
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                          <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                          <li class="breadcrumb-item"><a href="{{ route('settingConfig') }}">Config Setting</a></li>
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
        <div class="col-xl-12">
            <div class="container col-12">
                @include('../alert')
            </div>
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-body">
                        <form action="/admin/setting/config_update" method="POST" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <h6 class="heading-small text-muted mb-4">Payment credit card</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Payment url</label>
                                            <input type="hidden" name="payment_payment_url"
                                                value="{{ $config[3]['value'] }}">
                                            <input type="text" class="form-control" placeholder=""
                                                value="{{ $config[3]['value'] }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Web url</label>
                                            <input type="text" name="payment_url_myweb" class="form-control"
                                                placeholder="" value="{{ $config[4]['value'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Currency</label>
                                            <select name="payment_currencycode" class="form-control">
                                                <option value="USD" @if($config[5]['value']=='USD' ) selected @endif>USDollar(USD)</option>
                                                <option value="THB" @if($config[5]['value']=='THB' ) selected @endif>Thai baht(THB)</option>
                                                <option value="CNY" @if($config[5]['value']=='CNY' ) selected @endif>China yuan(CNY)</option>
                                                <option value="MYR" @if($config[5]['value']=='MYR' ) selected @endif>Malaysian Ringgit (MYR)</option>
                                                <option value="PHP" @if($config[5]['value']=='PHP' ) selected @endif>Philippine peso (PHP)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Cust IP</label>
                                            <input type="text" name="payment_custip" class="form-control" placeholder="" value="{{ $config[6]['value'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Cust name</label>
                                            <input type="text" name="payment_custname" class="form-control" placeholder="" value="{{ $config[7]['value'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Cust email</label>
                                            <input type="text" name="payment_custemail" class="form-control" placeholder="" value="{{ $config[8]['value'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Cust phone</label>
                                            <input type="text" name="payment_custphone" class="form-control" placeholder="" value="{{ $config[9]['value'] }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Page timeout</label>
                                            <input type="text" name="pagetimeout" class="form-control" placeholder="" value="{{ $config[10]['value'] }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4" />
                            <!-- Description -->
                            <h6 class="heading-small text-muted mb-4">Chat</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">First message for sale</label>
                                            <textarea name="first_messages" rows="4" class="form-control" placeholder="">{{ $config[2]['value'] }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Limit Messages</label>
                                            <select name="limit_messages" class="form-control">
                                                <option value="10" @if($config[1]['value']=='10' ) selected @endif>10</option>
                                                <option value="20" @if($config[1]['value']=='20' ) selected @endif>20</option>
                                                <option value="50" @if($config[1]['value']=='50' ) selected @endif>50</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Delete Old Messages</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name="delete_old_messages" class="form-control" placeholder="Date" value="{{ $config[0]['value'] }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Day</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="submit" class="btn btn-info">Update</button>
                        </form>
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
        v-on:delete_message="delMessage">
    </chat-component>

      @endsection
