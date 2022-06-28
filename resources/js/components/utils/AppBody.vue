<template>
 <div>
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ pageTitle }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{{ pageSubTitle }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>


          
        <div class="content " v-if="$gate.isAdminOrTutorOrStudent()">
         
           
         <slot></slot>
          </div>
         
        <div v-if="!$gate.isAdminOrTutorOrStudent()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->


    </div>

</template>

<script>
    export default {
      props:['pageTitle','pageSubTitle']

    }
</script>
