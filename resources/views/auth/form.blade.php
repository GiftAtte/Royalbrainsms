<section class="section-about" id="section-about">
        <div class="u-center-text">
          <h2 class="header-secondary u-margin-bottom-medium">
          Admission Form
          </h2>
        </div>

         <div class="row">
      <div class="row ">
        <div class="col-1-of-2">
         <div class="form__group">
  <input type="text" id="surname" name="surname" class="form__input" placeholder="Surname" required>
  <label class="form__label" for="surname">Surname</label>
        </div>
        <div class="form__group">
          <input type="text" id="name" name="name" class="form__input" placeholder="First Name" required>
          <label class="form__label" for="name">First Name</label>
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
          <input type="text" id="name" name="name" class="form__input" placeholder="Password" required>
          <label class="form__label" for="name">Password</label>
        </div>
        <div class="form__group">
         <input type="text" id="password_confirm" name="password_confirm" class="form__input" placeholder="Confirm Password" required>
          <label class="form__label" for="password_confirm">Confirm Password </label>
        </div>

       </div>

      </div>
      <div class="row">
         <button type="button" class="btn btin-primary" v-on:click.native="changeName()">

    REGISTER

  </button>
       </div>
      </section>
