@extends('layouts/admin.admin_index')

@section('center')
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">

                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('adminDisplayProducts') }}">Products</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $product[0]['product_name'] }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Product</h6>
                            <h5 class="h3 mb-0">Edit</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            <li>{{!! print_r($errors->all()) !!}}</li>
                        </ul>
                    </div>
                    @endif
                    <form action="/admin/updateProduct/{{$product[0]['product_id']}}" method="POST">
                        {{csrf_field()}}
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            placeholder="Product Name" value="{{$product[0]['product_name']}}" require>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Description</label>
                                        <input type="text" class="form-control" name="description" id="description"
                                            placeholder="description" value="{{$product[0]['description']}}" require>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Categorie</label>
                                        <select name="categorie_id" class="form-control">
                                            @foreach($categories as $item)
                                            <option value="{{ $item->id }}" @if ($product[0]['categorie_id'] == $item->id)
                                                selected @endif >{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Price</label>
                                        <input type="text" class="form-control" name="price" id="price"
                                            placeholder="price" value="{{$product[0]['price']}}" require>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    @if(isset($product[0]['standard']))
                                    <div class="form-group">
                                        <label for="name">STANDARD</label>
                                        <input type="text" class="form-control" name="standard"
                                            placeholder="Product Name" value="{{$product[0]['standard' ?? '']}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">MATERIAL</label>
                                        <input type="text" class="form-control" name="material"
                                            placeholder="Product Name" value="{{$product[0]['material'] ?? ''}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">COATING</label>
                                        <input type="text" class="form-control" name="coating"
                                            placeholder="Product Name" value="{{$product[0]['coating'] ?? ''}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">CODE</label>
                                        <input type="text" class="form-control" name="code"
                                            placeholder="Product Name" value="{{$product[0]['code'] ?? ''}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">UPDATE</label>
                                        <input type="date" class="form-control" name="update"
                                            placeholder="Product Name" value="{{$product[1] ?? ''}}">
                                    </div>
                                    @endif
                                </div>                                                        
                            </div>
                            @if($product[0]['details'] ?? '')
                            <hr class="my-4" />  
                            <div class="row">
                                <div class="col-lg-12">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                        @foreach ($product[0]['details'][0] as $key => $items)
                                            <th scope="col"><strong>{{ $key }}</strong></th>
                                        @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($product[0]['details'] as $key => $items)
                                        <tr>
                                        @foreach ($items as $index => $item)
                                            <td>                                               
                                                <input type="text" class="form-control" name="addmore[{{$key+1}}][{{$index}}]" placeholder="Enter {{$index}}" value="{{$item}}">                                             
                                            </td>
                                        @endforeach 
                                        </tr>
                                    @endforeach                         
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            @endif
                            <hr class="my-4" /> 
                            <button type="submit" name="submit" class="btn btn-info">Edit</button>
                        </div>
                    </form>
                </div>             
            </div>
        </div>
        <div class="col-xl-4">
          <div class="card">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-muted ls-1 mb-1">Product</h6>
                  <h5 class="h3 mb-0">Reviews ({{$reviews->total}} list)</h5>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">User name</th>
                    <th scope="col">Text</th>
                    <th scope="col">Ratting</th>
                    <th scope="col">Date</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($reviews->items as $item)
                  <tr>

                    <th scope="row">
                        <div class="media align-items-center">
                            <a href="#" class="avatar rounded-circle mr-3">
                                <img alt="Image placeholder"
                                    src="{{ asset('storage') }}/user_images/{{$item['image']}}"
                                    style="width:40px;height:40px;">
                            </a>
                            <div class="media-body">
                                {{$item['name']}}
                            </div>
                        </div>
                    </th>
                    <td>{{$item['text']}}</td>
                    <td>
                        <div class="rating-stars">
                            @for ($i = 0; $i < $item['ratting']; $i++)
                            <i class="fa fa-star"></i>
                            @endfor
                            @php $star_empty = 5 - $item['ratting'] @endphp
                            @for ($i = 0; $i < $star_empty; $i++)
                                <i class="fa fa-star-o"></i>
                            @endfor
                        </div>
                    </td>
                    <td>{{$item['created_at']}}</td>
                  </tr>  
                @endforeach           
                </tbody>
              </table>
            </div>
            <div class="card-footer py-4">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end mb-0">
                            @foreach ($reviews->links as $key => $values)
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
