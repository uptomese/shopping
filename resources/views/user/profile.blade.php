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
                <div class="col-xl-4 order-xl-2">
                    <div class="card card-profile">
                        <img src="../assets/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">
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
                                    <i class="ni business_briefcase-24 mr-2"></i>{{$user['address'] ?? ''}}
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
                                <h6 class="heading-small text-muted mb-4">Contact information</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-address">Address</label>
                                                <input id="input-address" class="form-control"
                                                    placeholder="Home Address"
                                                    value="{{$user['address'] ?? ''}}"
                                                    type="text"
                                                    name="address"
                                                    >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-city">City</label>
                                                <input type="text" id="input-city" class="form-control"
                                                    placeholder="City" value="New York">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-country">Country</label>
                                                <input type="text" id="input-country" class="form-control"
                                                    placeholder="Country" value="United States">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-country">Postal
                                                    code</label>
                                                <input type="number" id="input-postal-code" class="form-control"
                                                    placeholder="Postal code">
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

@endsection
