<section class="section-about" id="section-about">
    <form action="{{ url('registerNewUSer') }}" method="POST">
        {{ csrf_field() }}
        <div class="u-center-text">
          <h2 class="header-secondary u-margin-bottom-medium">
          Admission Form
          </h2>
        </div>

         <div class="row">
             @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
       @endif
      <div class="row ">
        <div class="col-1-of-2">
         <div class="form__group">
  <input type="text" id="surname" name="surname" class="form__input" placeholder="Surname" required>
  <label class="form__label" for="surname">Surname</label>
        </div>
        <div class="form__group">
          <input type="text" id="first_name" name="first_name" class="form__input" placeholder="First Name" required>
          <label class="form__label" for="first_name">First Name</label>
        </div>
        <div class="form__group">
         <input type="text" id="phone" name="phone" class="form__input" placeholder="Phone Number" required>
          <label class="form__label" for="phone">Phone Number</label>
        </div>

</div>
<div class="col-1-of-2">
         <div class="form__group">
  <input type="text" id="email" name="email" class="form__input" placeholder="Email" required>
  <label class="form__label" for="email">Email</label>
        </div>
        <div class="form__group">
          <input type="text" id="password" name="password" class="form__input" placeholder="Password" required>
          <label class="form__label" for="password">Password</label>
        </div>
        <div class="form__group">
         <input type="text" id="password_confirmation" name="password_confirmation" class="form__input" placeholder="Confirm Password" required>
          <label class="form__label" for="password_confirmation">Confirm Password </label>
        </div>
              <input type="hidden" name="school_id" value="{{ $school->id }}">
       </div>

      </div>
      <div class="row">
         <button type="submit" class="btn btin-primary" >

    REGISTER

  </button>
       </div>
       </form>
      </section>
