<template>
     <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Import Students</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Import Students History</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>


<div class="card card-navy card-outline">
<div class="card-header">
<div class="ribbon-wrapper">
    <div class="ribbon bg-primary">
      Import
      </div>
    </div>
<h5 class="header text-danger text-uppercase">Select cvs file and click upload</h5>
</div>
<div class="card-body">
        <loading :active.sync="isLoading"
        :can-cancel="false"
        :on-cancel="onCancel"
        color="green"
        :is-full-page="fullPage">
        </loading>

    </div>
 </div>

    </div>

</template>





<script >
import {RotateSquare2} from 'vue-loading-spinner'
  import { VueCsvImport } from 'vue-csv-import';
  import Loading from 'vue-loading-overlay';

    export default{
        components: { VueCsvImport,RotateSquare2,Loading },
        data(){
          return {
                isLoading:false,
                fullPage: true
          }
        },
         methods:{
           afterImport(){

                   this.isLoading=false;
                    toast.fire({
                        type: 'success',
                        title: 'Student Created in successfully'
                        })

                        this.$router.push('/import_history')
           },
           errImport(){
                    this.isLoading=false;
                    toast.fire({
                        type: 'error',
                        title: 'There was a problem importing your file'
                        })
                           this.resetLoading();
                        this.$router.push('/import_history')

           },
           setLoading(){
             this.isLoading=true
           },
           resetLoading(){
             this.isLoading=false
           },


             handleUpload() {
                 const file = this.$refs.file;
                 const formData = new FormData();
                 formData.append('file', file);
                 axios.post('/api/import_students_history', formData)
                       .then(res => {
                        console.log(res.data)
                       });}

        },




        created(){
            this.resetLoading();

        }
        }

</script>

