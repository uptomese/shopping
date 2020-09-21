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
                            <li class="breadcrumb-item"><a href="{{ route('adminDisplayCategories') }}">Categories</a></li>
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
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Categories <spen>({{$categories->total}} list)</spen></h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Id.</th>
                                <th scope="col">Name categories</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>                            
                            @foreach ($array_categories as $key => $categorie)
                            <tr>
                                <th scope="row">
                                    {{$categorie[0]['id']}}
                                </th>
                                <td>
                                    {{$categorie[0]['name']}} <b>({{$categorie['count']}})</b>
                                </td>
                                <td>
                                    <a href="{{ route('adminEditCategorieForm', ['id' => $categorie[0]['id']]) }}" class="btn btn-sm btn-outline-warning"> <i class="fa fa-edit"></i> แก้ไข</a>
                                    <a href="{{ route('adminDeleteCategorie', ['id' => $categorie[0]['id']]) }}" @if($categorie['count']!=0) class="btn btn-sm btn-outline-danger disabled" @else class="btn btn-sm btn-outline-danger" @endif> <i class="fa fa-trash"></i> ลบ</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end mb-0">
                            @foreach ( $categories->links as $key => $values)
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
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            @if($edit ?? '')
                            <h3 class="mb-0">Edit</h3>
                            @else
                            <h3 class="mb-0">Create</h3>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($edit ?? '')
                    <form action="{{ route('adminUpdateCategorieForm', ['id' => $edit[0]['id'] ]) }}" method="POST" enctype="multipart/form-data">
                    @else
                    <form action="/admin/createCategorieForm" method="POST" enctype="multipart/form-data">
                    @endif
                        {{csrf_field()}}
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            placeholder="Categorie Name" value="{{$edit[0]['name'] ?? ''}}" require>
                                    </div>
                                </div>
                            </div>
                            @if($edit ?? '')
                            <button type="submit" name="submit" class="btn btn-info">Update</button>
                            @else
                            <button type="submit" name="submit" class="btn btn-success">Create</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @endsection
