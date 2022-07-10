<section class="section-contact">
        <div class="row">
   <div class="contact__box">
  <div class="contact__form">
       <h3 class="u-center-text header-secondary u-margin-bottom-medium ">
            Contact Us
          </h3>
<form action="#" class="form">
  <div class="form__group">
  <input type="text" id="name" name="name" class="form__input" placeholder="Full Name" required>
  <label class="form__label" for="name">Your Name</label>
  </div>
  <div class="form__group">
  <input type="text" id="phone" name="phone" class="form__input" placeholder="Phone Number" required>
  <label class="form__label" for="phone" class="form_input">Phone Number</label>
  </div>
  <div class="form__group">
  <input type="email" id="email" name="email" class="form__input" placeholder="Email Address" required>
  <label class="form__label" for="email">Email Address</label>
  </div>
  <div class="form__group">
  <textarea name="message" id="message" required rows="3" class="form__input" placeholder="Message"></textarea>
  <label class="form__label" for="message">Message</label>
  </div>
  <div class="form__group">
   <div class="form__radio--group">
  <input type="checkbox" id="terms" name="terms" class="form__radio--button">
   <!-- <span class="form__radio--button"></span> -->
  <label class="form__radio--label" for="terms">Terms</label>
  </div>
  <button type="button" class="btn  btn-animated form__radio--submit" v-on:click.native="changeName()">

    submit
     &rarr;
  </button>
</div>

</form>
</div>
</div>
        <div class="contact__text">
        <h3 class="contact__text--header">
          Contact Address
        </h3>
        <hr/>
        <ul class="contact__text--list">
          <li>
            {{ $school->contact_address }}
          </li>
          <li>
            &nbsp;{{ $school->state }}, &nbsp; {{ $school->country }}
          </li>

       <li>
        +234 &nbsp;{{$school->phone}}
       <li>{{ $school->email }}</li>
        </ul>
        </div>
        </div>
        </div>


      </section>
