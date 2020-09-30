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
                            <li class="breadcrumb-item"><a href="{{ route('getUsers') }}">Users</a></li>
                            @if($user ?? '')
                            <li class="breadcrumb-item active" aria-current="page">{{$user['name']}}</li>
                            @else
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                            @endif
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
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                            @if($user ?? '')
                                <h3 class="mb-0">Edit User</h3>
                            @else
                                <h3 class="mb-0">Create User</h3>
                            @endif
                            </div>
                            <div class="col-4 text-right">
                                <!-- <a href="#!" class="btn btn-sm btn-primary">Settings</a> -->
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($user ?? '')
                        <form action="/admin/updated_user/{{$user['id'] ?? ''}}" method="POST" enctype="multipart/form-data">
                        @else
                        <form action="/admin/created_user" method="POST" enctype="multipart/form-data">
                        @endif
                            {{csrf_field()}}
                            <h6 class="heading-small text-muted mb-4">User information</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Name</label>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{$user['name'] ?? ''}}" required autocomplete="name" autofocus>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Email</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{$user['email'] ?? ''}}" @if($user ?? '') disabled @endif autocomplete="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Phone number</label>
                                            <input id="phone" type="text" class="form-control @error('email') is-invalid @enderror" name="phone"  placeholder="Phone Number" value="{{$user['phone'] ?? ''}}" required autocomplete="phone">
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <div class=" w-100">                                
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="checkbox" id="customRadio2" name="sell_status" class="custom-control-input" value="1" @if(isset($user) && $user['sell'] == 1) checked @endif>
                                        <label class="custom-control-label" for="customRadio2">is Sell</label>
                                    </div>
                                    <hr class="my-4" />                                
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="customRadio1" name="status" class="custom-control-input" value="1" @if(isset($user) && $user['admin'] == 1) checked @endif required>
                                        <label class="custom-control-label" for="customRadio1">is Admin</label>
                                    </div>                                
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio3" name="status" class="custom-control-input" value="0" @if(isset($user) && $user['admin'] == 0) checked @endif required>
                                        <label class="custom-control-label" for="customRadio3">is User</label>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                @if($user ?? '')
                                @else
                                    <div class="row">                                                      
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-first-name">Password</label>
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="new-password">
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-last-name">Confirm Password</label>
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if($user ?? '')
                            @else
                            <hr class="my-4" />
                            @endif
                            <!-- Address -->
                            <h6 class="heading-small text-muted mb-4">Contact information</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-address">Address</label>
                                            <!-- id="address" -->
                                            <input 
                                            id="autocomplete"
                                            type="text" 
                                            class="form-control @error('email') is-invalid @enderror" 
                                            name="address" 
                                            placeholder="Address" 
                                            value="{{$user['address'][1] ?? ''}}" 
                                            required 
                                            autocomplete="address"
                                            >
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4" />
                            @if($user ?? '')
                                <button type="submit" name="submit" class="btn btn-round btn-primary">Update</button>
                            @else
                                <button type="submit" name="submit" class="btn btn-round btn-primary">Create</button>
                            @endif
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
    v-on:delete_message="delMessage"
></chat-component>

@endsection
