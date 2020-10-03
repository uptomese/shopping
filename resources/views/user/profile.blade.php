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
        <!-- Page content -->
        <div class="container-fluid mt--6">     
            <div class="row">
                <div class="col-12">
                    <div class="container">
                        @include('../alert')
                    </div>
                </div>
                <div class="col-xl-4 order-xl-2">
                    <div class="card card-profile">
                        <img src="{{ asset('assets/img/theme/img-1-1000x600.jpg') }}" alt="Image placeholder" class="card-img-top">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a href="#">
                                    @if(!$userData->image)
                                    <img id="blah" src="{{ asset('storage') }}/user_images/default.jpg" class="rounded-circle" style="width:150px;height:140px;">
                                    @else
                                    <img id="blah" src="{{ asset('storage') }}/user_images/{{$user['image']}}" class="rounded-circle" style="width:150px;height:140px;">
                                    @endif 
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                            <form action="/user/update_image_profile" method="POST" enctype="multipart/form-data">
                            <div class="d-flex justify-content-between">
                                {{csrf_field()}}
                                    <a href="#" class="btn btn-sm btn-info  mr-4" onclick="document.getElementById('image_file').click()">File</a>
                                    <input
                                        id="image_file"
                                        name="image"
                                        type="file"
                                        class="form-control"
                                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                        accept=".jpg, .jpeg, .png"
                                        hidden
                                        />
                                    <button type="submit" name="submit" class="btn btn-sm btn-info float-right">Upload</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col">
                                    <div class="card-profile-stats d-flex justify-content-center">
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
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
                <div class="col-xl-8 order-xl-1">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">Edit profile </h3>
                                </div>
                                <!-- <div class="col-4 text-right">
                                    <a href="#!" class="btn btn-sm btn-primary">Settings</a>
                                </div> -->
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="/user/update_profile" method="POST" enctype="multipart/form-data">
                            {{csrf_field()}}
                                <h6 class="heading-small text-muted mb-4">User information</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-username">Name</label>
                                                <input type="text" id="input-username" class="form-control" name="name"
                                                    placeholder="Name" value="{{$user['name']}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">Email</label>
                                                <input type="email" id="input-email" class="form-control" name="email"
                                                    placeholder="Email" value="{{$user['email']}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-last-name">Phone number</label>
                                                <input type="text" id="input-last-name" class="form-control" name="phone"
                                                    placeholder="Phone" value="{{$user['phone'] ?? ''}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <!-- Address -->
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="heading-small text-muted mb-4">Address information</h6>
                                    </div>
                                    <div class="col text-right">
                                        <h6 class="heading-small text-muted mb-4"><a href="#!" class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#Modal_add" data-whatever="@add">add</a></h6>                                    
                                    </div>
                                </div>                         
                                @if(!isset($userData->address[0]) && count($address) == 0)
                                    <h3 style="color:red;">Press the button to create an address.</h3>
                                @else
                                <table class="table table-borderless" style="width: 100%;">
                                    <tr>
                                        <td>
                                        @if(isset($userData->address[0]))
                                            <input class="form-check-input" type="radio" name="type_address" value="address_a" @if(isset($user['address'][0]) && $user['address'][0] == 'address_a') checked @endif required>                                                    
                                            <input                                                                                        
                                                value="{{$user['address'][1] ?? ''}}"
                                                type="hidden"
                                                name="address"
                                                >               
                                            <label class="form-control-label" for="input-address"> @php if($userData->address[0] ?? '' == 'address_a'){ echo '(default)';} @endphp {{$user['address'][1] ?? ''}}</label>                                                    
                                        @endif
                                        </td>
                                        <td class="col text-right"></td>
                                    </tr>
                                    @if(count($address)>0)
                                    @foreach ($address as $key => $item)    
                                    <tr>
                                        <td>
                                            <input name="type_address" class="form-check-input" type="radio"  value="address_b_{{$item['address']}}" @if(isset($user['address'][0]) && $user['address'][0] == 'address_b' && $item['address'] == $user['address'][2] ?? '') checked @endif required>
                                            <label class="form-control-label" for="input-address"> @php if(isset($user['address'][0]) && $user['address'][0] == 'address_b' && $item['address'] == $user['address'][2] ?? ''){ echo '(default)';} @endphp {{$item['title']}} : {{$item['address']}}</label>
                                        <td>
                                        <td class="col text-right">
                                            <a href="{{ route('editAddress',['id' => $item['id']]) }}" class="btn btn-sm btn-outline-warning"> <i class="fa fa-edit"></i> แก้ไข</a>&nbsp;
                                            <a href="{{ route('deleteAddress',['id' => $item['id']]) }}" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger"> <i class="fa fa-trash"></i> ลบ</a>
                                        </td>
                                    </tr>
                                    @endforeach                                                 
                                    @endif
                                </table>
                                @endif                                                                
                                <hr class="my-4" />
                                <button type="submit" name="submit" class="btn btn-round btn-primary">Update profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form action="/user/create_address/{{$userData->id}}" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ni ni-fat-remove"></i></button>
            </div>
            <div class="modal-body">                
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                        <input name="address_title" type="text" class="form-control" id="recipient-name" placeholder="e.g. Home, At work" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-10">
                        <input id="autocomplete2" name="address"  class="form-control latitude" placeholder="Address" type="text" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
        </div>
    </div>
</div>

@endsection
