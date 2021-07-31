<template>
  <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Progress Update</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Promotion</li>
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
      Progress
      </div>
    </div>


</div>

<div class="card-body">

<div  class="form-group col-12" >
<rotate-square2 v-show="isloading"   id="loader"></rotate-square2>
                             <select
                    name="report_id"
                    id="report_id"
                    :class="{'is-invalid':form.errors.has('report_id')}"
                    class="form-control"
                    v-model="form.report_id"
                   @change="loadArms"

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
                    <div  class="form-group col-12"  >
                             <select
                    name="arm_id"
                    id="arm_id"
                    :class="{'is-invalid':form.errors.has('report_id')}"
                    class="form-control"
                    v-model="form.arm_id"
                    @change="loadActivation"

                         >
                    <option value selected>Select Class/Level Arm</option>
                    <option
                      v-for=" arm in arms"
                      :key="arm.arms.id"
                      :value="arm.arms.id"

                    >{{arm.arms.name}}</option>
                  </select>
                        <has-error :form="form" field="subject"></has-error>
                    </div>

<div v-show="isResults" class="card-body">
<table class="table table-hover table-sm table-bordered">
<thead>
<tr>

<th>S/N</th><th>Name</th><th>Cummulative Average Scores</th><th>Progress Status</th>

</tr>
</thead>
<tbody>
<tr v-for="(activation,index) in Activation_status" :key="index">
<td>{{index+1}}
<td>{{activation.name}}
 <input type="hidden" :id="`student_id${index}`" :value="activation.student_id">
 </td>
 <td>{{activation.average_score?activation.average_score:''}}</td>
<td><input type="text" :value="activation.progress_status?activation.progress_status:''" :id="`comment${index}`" placeholder="progress status" class="form-control"></td>


</tr>

</tbody>
</table>
<div class="card-footer"><button class="btn btn-success">submit</button></div>
</div>
</div>


</form>

    </div></div></div>
</template>

<script>

import { VueCsvImport } from 'vue-csv-import';
  import Loading from 'vue-loading-overlay';


    export default {
components: { VueCsvImport,Loading },

        data(){

            return{

                Activation_status:[],
                reports:{},
               isResults:false,
                isSelectAll:false,
                arms:{},
                selected: [],
                allSelected: false,
                studentIds: [],
                isChecked:false,
                isStudentId:false,
                   isLoading:false,
form:new Form({
     report_id:'',
     arm_id:'',
     student_id:[],
     progress_status:[],
     number_of_students:0
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

 afterImport(){
                    toast.fire({
                        type: 'success',
                        title: 'Scores Imported successfully'
                        })
                      this.resetLoading()
                        this.$router.push('/load_activation')
           },
           errImport(err){

                    toast.fire({
                        type: 'error',
                        title: err
                        })

                         this.resetLoading();

           },
            setLoading(){
             this.isLoading=true
           },
           resetLoading(){
             this.isLoading=false
           },




           loadActivation(){
                this.$Progress.start();
          axios.get(`/api/load_results/${this.form.report_id}/${this.form.arm_id}`)
          .then((result) => {
             this.Activation_status=result.data;

             this.isResults=true;
             this.$Progress.finish();


          }).catch((err) => {
              this.$Progress.fail();
          });
           },

            createActivation(){


            this.form.student_id=[];
            this.form.activation_status=[];

            this.form.number_of_students=0;
         for (let index = 0; index < this.Activation_status.length; index++) {

           var student_id=document.querySelector(`#student_id${index}`).value
               var comment=document.querySelector(`#comment${index}`).value
               this.form.progress_status.push(comment)
               this.form.student_id.push({'student_id':student_id,
                                            'progress_status':comment})




         }
        this.$Progress.start();
          if(this.$gate.isAdminOrTutor()){

            this.form.post(`api/update_results`)

            .then(res=>{
             console.log(res.data)

             swal.fire(
                        'success',
                        'Progress status updated successfully',
                        'success'
                        )
             this.$Progress.finish();
             this.loadActivation()
             this.isResults=false
              }).catch(err=>{
                toast.fire({
                        type: 'danger',
                        title: 'there was error uploading the file'+err
                        })
              });


    }},
     loadArms(){
       axios.get('/api/getArms/'+this.form.report_id).then(res=>{
           this.arms=res.data
           console.log(this.arms)
       })},


    },
    created() {

          // this.loadArms();

        //    Fire.$on('AfterCreate',() => {

        //       // this.loadSubjects();
        //       // $this.form.reset();
        //    });
        // //    setInterval(() => this.loadUsers(), 3000);

        }
    }

</script>
