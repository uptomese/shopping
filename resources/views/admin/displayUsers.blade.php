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
                    <!-- <a href="#" class="btn btn-sm btn-neutral">New</a>
              <a href="#" class="btn btn-sm btn-neutral">Filters</a> -->
                </div>
            </div>
            <!-- Card stats -->
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Users <spen>({{$users->total}} list)</spen>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route('createUser') }}" class="btn btn-sm btn-primary">Create</a>
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
                                <th scope="col">Address</th>
                                <th scope="col">Phone number</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users->items as $user)
                            <tr>
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
                                    <a href="#"><span class="name mb-0 text-sm">{{$user['name']}}</span></a>
                                </td>
                                <td>
                                    {{$user['email']}}
                                </td>
                                <td>
                                    {{$user['address'] ?? ''}}
                                </td>
                                <td>
                                    {{$user['phone'] ?? ''}}
                                </td>
                                <td>
                                    <a href="/admin/update_user/{{$user['id']}}" @if($user['admin']!=1)
                                        class="btn btn-sm btn-outline-warning" @else
                                        class="btn btn-sm btn-outline-warning disabled" @endif> <i
                                            class="fa fa-edit"></i> แก้ไข</a>
                                    <a href="#" @if($user['admin']!=1) class="btn btn-sm btn-outline-danger" @else
                                        class="btn btn-sm btn-outline-danger disabled" @endif> <i
                                            class="fa fa-times"></i> บล็อค</a>
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
    </div>

    @endsection
