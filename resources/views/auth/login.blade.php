@extends('layouts.login')
 <style>
            html, body {
                background-color: #fff;
                color: white;
                font-family: 'Raleway', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }
</style>
@section('content')



<body class="hold-transition login-page bg-navy">
<div class="login-box">
    @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
     @endif
  <div class="login-logo">


    <a href="#" class="text-white"><b class="text-white">Portal</b>SignIn</a>

  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">

      <p class="login-box-msg text-primary">Sign in to start your session</p>
 </center>
     <form method="POST" action="{{ route('login') }}">
                        @csrf
        <div class="input-group mb-3">
          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Please Enter User Email">

                @error('email')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
                 @enderror
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
           <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Please Enter User Password">

             @error('password')
         <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
           </span>
         @enderror
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                {{ __('Remember Me') }}
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">
                 {{ __('Login') }}
            </button>
          </div>
          <!-- /.col -->
        </div>


      <p class="mb-1">
        @if (Route::has('password.request'))
         <a class="btn btn-link" href="{{ route('password.request') }}">
         {{ __('Forgot Your Password?') }}
         @endif

      </p>
      </form>



    </div>
    <!-- /.login-card-body -->
  </div>
</div>




























@endsection
