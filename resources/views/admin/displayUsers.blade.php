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
                            <li class="breadcrumb-item"><a href="{{ route('getUsers') }}">Users</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{ route('createUser') }}" class="btn btn-sm btn-neutral">New</a>
                    <!-- <a href="#" class="btn btn-sm btn-neutral">Filters</a> -->
                </div>
            </div>
            <!-- Card stats -->
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="container col-12">
            @include('../alert')
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Users <spen>({{$users->total}} list)</spen></h3>
                        </div>
                        <div class="col-4 text-right">
                            <h5 class="text-muted"><i class="fa fa-circle font-10 m-r-10 text-info"></i> Sale</h5>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Id.</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone number</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users->items as $user)
                            <tr style="@if($user['sale']==1) background-color:aliceblue; @endif">
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="#" class="avatar rounded-circle mr-3">
                                            <img alt="Image placeholder"
                                                src="{{ asset('storage') }}/user_images/{{$user['image']}}"
                                                style="width:40px;height:40px;">
                                        </a>
                                        <div class="media-body">
                                            {{$user['id']}}
                                        </div>
                                    </div>
                                </th>
                                <td>
                                    <a href="{{ route('getOrders', ['id' => $user['id']]) }}"><span class="name mb-0 text-sm">{{$user['name']}}</span></a>
                                </td>
                                <td>
                                    {{$user['email']}}
                                </td>
                                <td>
                                    {{$user['phone'] ?? ''}}
                                </td>
                                <td>
                                @if($user['name'] != 'admin')
                                    <a href="/admin/update_user/{{$user['id']}}" class="btn btn-sm btn-outline-warning"><i class="fa fa-edit"></i> แก้ไข</a>
                                @endif
                                    <!-- <a href="#" class="btn btn-sm btn-outline-danger @if($user['admin']==1) disabled @endif"><i class="fa fa-times"></i> บล็อค</a> -->
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end mb-0">
                            @foreach ($users->links as $key => $values)
                            <li class="{{$values['stly_classes']}}">
                                <a class="page-link" href="?page={{$values['page']}}">{{ $values['icon'] }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
        </div>


        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Admin <spen>({{$admins->total()}} list)</spen></h3>
                        </div>
                        <div class="col-4 text-right">
                            <h5 class="text-muted"><i class="fa fa-circle font-10 m-r-10 text-info"></i> Sale</h5>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Id.</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone number</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $user)
                            <tr style="@if($user['sale']==1) background-color:aliceblue; @endif">
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="#" class="avatar rounded-circle mr-3">
                                            <img alt="Image placeholder"
                                                src="{{ asset('storage') }}/user_images/{{$user['image']}}"
                                                style="width:40px;height:40px;">
                                        </a>
                                        <div class="media-body">
                                            {{$user['id']}}
                                        </div>
                                    </div>
                                </th>
                                <td>
                                    <a href="{{ route('getOrders', ['id' => $user['id']]) }}"><span class="name mb-0 text-sm">{{$user['name']}}</span></a>
                                </td>
                                <td>
                                    {{$user['email']}}
                                </td>
                                <td>
                                    {{$user['phone'] ?? ''}}
                                </td>
                                <td>
                                @if($user['name'] != 'admin')
                                    <a href="/admin/update_user/{{$user['id']}}" class="btn btn-sm btn-outline-warning"><i class="fa fa-edit"></i> แก้ไข</a>
                                @endif
                                    <!-- <a href="#" class="btn btn-sm btn-outline-danger @if($user['admin']==1) disabled @endif"><i class="fa fa-times"></i> บล็อค</a> -->
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end mb-0">
                            {{$admins->links()}}
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
