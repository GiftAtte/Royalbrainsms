
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>TSA</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">


  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed">
<div class="wrapper" id="app">
<nav class="main-header navbar navbar-expand navbar-white navbar-navy bg-navy">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <menutogglebtn ></menutogglebtn>
      </li>

    </ul>
    <!-- Search Form -->
    <div class="input-group input-group-sm col-md-8 mx-5">
      <input class="form-control form-control-navbar" @keyup="searchit" v-model="search" type="search" placeholder="Search" aria-label="Search">
      <div class="input-group-append">
        <button class="btn btn-navbar" @click="searchit">
          <i class="fa fa-search"></i>
        </button>
      </div>
    </div>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
       <li><a href="#" class="d-block text-white">
      </a></li>
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <div class="image">
          <img src="img/profile/{{ Auth::user()->photo}}" height="30" width="30" class="img-circle elevation-2" alt="User Image">
          </div>

        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
             {{ __('Logout') }}
         </a>

         <form  id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
             @csrf
         </form>
        </div>



      </li>
    </ul>
  </nav>

   {{-- <header-view></header-view> --}}

  <!-- Main Sidebar Container -->
   {{-- @include('layouts.sidebar') --}}
<side-menu></side-menu>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->


      <div class="main-content__body">

        <router-view></router-view>

        <vue-progress-bar>
        </vue-progress-bar>

        <!-- /.row -->

      </div><!-- /.container-fluid -->

    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->
</div>
  @include('layouts.footer')
  <!-- Main Footer -->

<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@auth
<script>

    window.user = @json(auth()->user())

</script>
@endauth
<!-- jQuery -->

<!-- AdminLTE App -->
<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
