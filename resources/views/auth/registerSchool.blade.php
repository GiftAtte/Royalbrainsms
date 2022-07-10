@extends('layouts.app')
@section('content')

    <div class="header" id="app">
      <div class="logo-box">
        <h1 class="logo">{{ $school->short_name?$school->short_name:'' }}</h1>
        <h3 class="sub-logo">Our School Admission Portal</h3>
      </div>
      <div class="address-box">

             <button class="btn  btn-animated  address__box--text">
              LOGIN
               &rarr;
           </button>

        <!-- <a href="https://portal.royalbrainsms.com/" target="_blank" class="btn-text">PORTAL</a> -->
      </div>
      <div class="header-primary-box">
        <h1 class="header-primary">
          <span class="header-primary-main">Welcome To</span>
          <span class="header-primary-sub">Our Admission Portal</span>
        </h1>
            <span class="header-primary-sub">Please Fill the form Bellow</span>

           </div>

        </div>

    </div>
    <main>
      @include('auth.form')
      @include('auth.contact')
    </main>
    <footer class="footer">
       <span class="header-primary-sub">{{ $school->name?$school->name:'' }}</span>
       &copy; ThinkschoolApp 2022
    </footer>


@endsection

@section('script')


</script>
@endsection
