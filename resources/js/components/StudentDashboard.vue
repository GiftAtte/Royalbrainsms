<template>

<section class="section-features ">
    <div class="col-md-12 row">

                <marquee behavior="scroll" direction="left" class="message-box">
<h4 class="text-bold"> INFO.....!!! &nbsp;&nbsp;&nbsp;
Please click on the boxes below to access Information as described on it. You can also access other links  through the sidebar by your left.
</h4>

 </marquee>
 <div class="col-md-12 text-bold"><hr/></div>

    </div>


        <div class=" row">
          <div class="col-md-3 col-sm-12">
            <div class="feature-box container" @click="navigate('/reports')">
              <i class="feature-box__icon warning fa fa-book"></i>
              <h3 class="header-tertiary" style="color:orange">
                REPORT CARDS
              </h3>
              <p>
                Exams And Records

              </p>
            </div>
          </div>

          <div class="col-md-3 col-sm-12" @click="navigate('/exams')">
            <div class="feature-box container">
              <i class="feature-box__icon  primary fa fa-book-open"></i>
              <h3 class="text-primary u-margin-bottom-small">
                CBT
              </h3>
              <p>
               Online Exam/Test

              </p>
            </div>
          </div>

          <div class="col-md-3 col-sm-12">
            <div class="feature-box container" @click="navigate('/feegroup')">

              <i class=" feature-box__icon success fas fa-money-bill-alt"></i>
              <h3 class="text-success u-margin-bottom-small">
                SCHOOL FEES
              </h3>
              <p>
               Fees Information

              </p>
            </div>
          </div>

          <div class="col-md-3 col-sm-12">
            <div class="feature-box container " @click="navigate('/assignments')">

              <i class=" feature-box__icon danger fa fa-edit"></i>
              <h3 class="text-danger u-margin-bottom-small">
               ASSIGNMENT
              </h3>
              <p>
                Assigment/Notes

              </p>
            </div>
          </div>
        </div>
      </section>
</template>

<script>

export default{
    methods:{
        navigate(route) {
            this.$router.push(route)

       }
    }
}
</script>
