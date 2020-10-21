<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <title>Abpon | Admin Panel</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <script src="https://unpkg.com/vue"></script>
    <script src="https://unpkg.com/marked@0.3.6"></script>
    <script src="https://unpkg.com/lodash@4.16.0"></script>
    <!-- <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- <link rel="stylesheet" href="{{ mix('css/app.css') }}" /> -->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="{{URL::asset('css/box2.css')}} ">
    <link rel="stylesheet" href="{{URL::asset('css/chatbtn.css')}} ">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/img/brand/favicon.png') }}" type="image/png">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
    <!-- Argon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/argon.css?v=1.2.0') }}" type="text/css">

    <!-- Loading -->
    <link type="text/css" rel="stylesheet" href="{{asset('css/loading.css')}}"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree&family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Bai Jamjuree', sans-serif;
            font-family: 'Kanit', sans-serif;
        }
    </style>

</head>

<body>
<div id="app">
<div id="loading" class="loading"></div>
    <!-- Sidenav -->
    <nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
        <div class="scrollbar-inner">
            <!-- Brand -->
            <div class="sidenav-header  align-items-center">
                <a class="navbar-brand" href="{{ route('allProducts') }}">
                    <img src="{{ asset('assets/img/brand/logo.png') }}" class="navbar-brand-img" alt="...">
                </a>
            </div>
            <div class="navbar-inner">
                <!-- Collapse -->
                <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                    <!-- Nav items -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::url() == route('adminDisplayDashboards') ? 'active' : '' }}" href="{{ route('adminDisplayDashboards') }}">
                                <i class="ni ni-chart-bar-32 text-pink"></i>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::url() == route('adminDisplayProducts') ? 'active' : '' }}" href="{{ route('adminDisplayProducts') }}">
                                <i class="ni ni-box-2 text-primary"></i>
                                <span class="nav-link-text">Products</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::url() == route('adminDisplayCategories') ? 'active' : '' }}" href="{{ route('adminDisplayCategories') }}">
                                <i class="ni ni-bullet-list-67 text-orange"></i>
                                <span class="nav-link-text">Categories</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::url() == route('adminDisplayOrders') ? 'active' : '' }}" href="{{ route('adminDisplayOrders') }}">
                                <i class="ni ni-cart text-info"></i>
                                <span class="nav-link-text">Orders</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::url() == route('getUsers') ? 'active' : '' }}" href="{{ route('getUsers') }}">
                                <i class="ni ni-single-02 text-yellow"></i>
                                <span class="nav-link-text">Users</span>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="tables.html">
                                <i class="ni ni-bullet-list-67 text-default"></i>
                                <span class="nav-link-text">Tables</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.html">
                                <i class="ni ni-key-25 text-info"></i>
                                <span class="nav-link-text">Login</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.html">
                                <i class="ni ni-circle-08 text-pink"></i>
                                <span class="nav-link-text">Register</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="upgrade.html">
                                <i class="ni ni-send text-dark"></i>
                                <span class="nav-link-text">Upgrade</span>
                            </a>
                        </li> -->
                    </ul>
                    <!-- Divider -->
    
                    <hr class="my-3">
                    <!-- Heading -->
                    <!-- <h6 class="navbar-heading p-0 text-muted">
                        <span class="docs-normal">Only admin</span>
                    </h6> -->
                    <!-- Navigation -->
                    <ul class="navbar-nav mb-md-3">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::url() == route('settingConfig') ? 'active' : '' }}" href="{{ route('settingConfig') }}">
                                <i class="ni ni-settings-gear-65"></i>
                                <span class="nav-link-text">Config setting</span>
                            </a>
                        </li>
                    </ul>


                </div>
            </div>
        </div>
    </nav>
    <!-- Main content -->
    <div class="main-content" id="panel">
        <!-- Topnav -->
        <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Search form -->
                    <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                        <!-- <div class="form-group mb-0">
                            <div class="input-group input-group-alternative input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input class="form-control" placeholder="Search" type="text">
                            </div>
                        </div> -->
                        <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main"
                            aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </form>
                    <!-- Navbar links -->
                    <ul class="navbar-nav align-items-center  ml-md-auto ">
                        <li class="nav-item d-xl-none">
                            <!-- Sidenav toggler -->
                            <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin"
                                data-target="#sidenav-main">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item d-sm-none">
                            <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                                <i class="ni ni-zoom-split-in"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
                        <li class="nav-item dropdown">
                            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <div class="media align-items-center">
                                    <span class="avatar avatar-sm rounded-circle">
                                        <img alt="Image placeholder"
                                            src="{{ asset('storage') }}/user_images/{{$userData->image}}"
                                            style="width:40px;height:35px;">
                                    </span>
                                    <div class="media-body  ml-2  d-none d-lg-block">
                                        <span class="mb-0 text-sm  font-weight-bold">{{ $userData->name }}</span>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu  dropdown-menu-right ">
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome!</h6>
                                </div>
                                <a href="/home" class="dropdown-item">
                                    <i class="ni ni-cart"></i>
                                    <span>Order</span>
                                </a>
                                <a href="{{ route('getProfile') }}" class="dropdown-item">
                                    <i class="ni ni-single-02"></i>
                                    <span>My profile</span>
                                </a>
                                @if($userData->isAdmin())
                                <a href="{{ route('adminDisplayProducts') }}" class="dropdown-item">
                                    <i class="ni ni-settings-gear-65"></i>
                                    <span>Admin panel</span>
                                </a>
                                @endif
                                <!-- <a href="#!" class="dropdown-item">
                                    <i class="ni ni-calendar-grid-58"></i>
                                    <span>Activity</span>
                                </a>
                                <a href="#!" class="dropdown-item">
                                    <i class="ni ni-support-16"></i>
                                    <span>Support</span>
                                </a> -->
                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="ni ni-user-run"></i>
                                    <span>Logout</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Header -->
