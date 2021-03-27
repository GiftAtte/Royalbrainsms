<template>
  <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Activate Results</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Score Card</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
   <div class="content col-12">
<div class="card card-navy card-outline ">
<form  @submit.prevent="createActivation" >
<div class="card-header">
<div class="ribbon-wrapper">
    <div class="ribbon bg-primary">
      Admin
      </div>
    </div>

 <rotate-square2 v-show="isloading"></rotate-square2>
</div>

<div class="card-body">
<div class="form-group col-12">
        <input class="form-control" type="number"
        :class="{'is-invalid':form.errors.has('number_of_keys')}"
        v-model="form.number_of_keys"
        placeholder="Enter number of keys to be generated"
        >
         <has-error :form="form" field="number_of_keys"></has-error>
      </div>
<div  class="form-group col-12" >
                             <select
                    name="report_id"
                    id="report_id"
                    :class="{'is-invalid':form.errors.has('report_id')}"
                    class="form-control"
                    v-model="form.report_id"
                   @change="loadActivation"

                         >
                    <option value selected>Select Report class set</option>
                    <option
                      v-for="report in reports"
                      :key="report.id"
                      :value="report.id"
                    >{{report.title}}</option>
                  </select>
                        <has-error :form="form" field="subject"></has-error>
                    </div>



<div v-show="isResults" class="card-body">

 <export-excel
       class="btn btn-primary"
       :before-finish="restDownload"
       :data="Activation_keys"
        :fields="fields"
        type="csv"
        name="Resulst Activation Keys.csv">
       Download result activation keys
       <i class="fa fa-download"></i>
     </export-excel>
<div class="card-footer"></div>
</div>
</div>


</form>

    </div></div></div>
</template>

<script>

 import {RotateSquare2} from 'vue-loading-spinner'
    export default {
        components: {
       RotateSquare2
    },

        data(){

            return{

                Activation_keys:[],
                reports:{},
               isResults:false,
               isloading:false,
               fields:{
                 'ACADEMIC SESSION':"report.title",
                 'SCHOOL':"school.short_name",
                 "ACTIVATION KEYS" :"activation_key"
               },

form:new Form({
     report_id:'',
     number_of_keys:''
})
            }


        },
        mounted(){
            axios.get('/api/load_report')
            .then(result => {
               console.log(result.data);
                this.reports=result.data;
            }).catch((err) => {

            });
           // this.loadSubjects();
        },
        methods:{


           loadActivation(){
                this.$Progress.start();
                this.isloading=true;
         this.form.post('/api/generate_activation')
          .then((result) => {
             this.Activation_keys=result.data.activation_key;
            console.log(this.Activation_keys)

             this.isResults=true;
             this.isloading=false;

           this.$Progress.finish()

          }).catch((err) => {
              this.$Progress.fail();
          });
           },

         restDownload(){
             this.isResults=false;
         }

    },
    created() {

          // this.loadSubjects();

        //    Fire.$on('AfterCreate',() => {

        //       // this.loadSubjects();
        //       // $this.form.reset();
        //    });
        // //    setInterval(() => this.loadUsers(), 3000);

        }
    }

</script>
