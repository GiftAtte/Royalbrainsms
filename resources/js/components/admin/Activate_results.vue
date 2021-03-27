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


</div>

<div class="card-body">

<div  class="form-group col-12" >
<rotate-square2 v-show="isloading"   id="loader"></rotate-square2>
  <vue-csv-import
    v-model="csv"
    url="/api/import_progress"
    :map-fields="['report_id','student_id','progress_status']"
     :callback="afterImport"
     :catch="errImport"

    >

    <template slot="hasHeaders" slot-scope="{headers, toggle}">

        <template slot="hasHeaders" slot-scope="{headers, toggle}">
        <label>
            <input type="checkbox" checked id="hasHeaders" :value="headers" @change="toggle">
            Headers?
        </label>
    </template>


    </template>

    <template slot="error">
        File type is invalid
    </template>

    <template slot="thead">
        <tr>
            <th>Text Fields</th>
            <th>Select Columns To Match

 </th>
<rotate-square2 v-show="isloading"   id="loader"></rotate-square2>
        </tr>
    </template>

    <template slot="next" slot-scope="{load}">
        <button class="btn btn-primary" @click.prevent="load">load progress!</button>
    </template>

    <template slot="submit" slot-scope="{submit}">
        <button class="btn btn-success" @click.prevent="submit"><i class="fa fa-user-plus" @click="setLoading"> Submit</i></button>
    </template>
</vue-csv-import>
    <router-link to="/deactivat_results" class="btn btn-sm btn-primary mb-2 float-right">Deactivate Bulk</router-link>
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
<th>Select All&nbsp;<input type="checkbox" @click="selectAll" v-model="allSelected" :checked="isSelectAll"></th>
<th>Name</th><th>Average Scores</th><th>Comment</th>
<th>
 Status
</th>
</tr>
</thead>
<tbody>
<tr v-for="(activation,index) in Activation_status">

<td><div class="icheck-primary">
<input :id="`student${index}`" type="checkbox"  @click="select(activation.student_id)" :checked="isChecked">
<label :for="`student${index}`">
</label>
</div>  </td>
<td>{{activation.name}}
 <input type="hidden" :id="`student_id${index}`" :value="activation.student_id">
 </td>
 <td>{{activation.average_score?activation.average_score:''}}</td>
<td><input type="text" :value="activation.comment?activation.comment:''" :id="`comment${index}`" placeholder="progress status" class="form-control"></td>
<td>
<p :class="activation.activation_status?'text-success':'text-danger'">
{{activation.activation_status?'Activated':'Deactivated'}}
</p>
</td>

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
     activation_status:[],
     comment:[],
     number_of_students:''
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
          axios.get(`/api/load_activation/${this.form.report_id}/${this.form.arm_id}`)
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
            this.form.student_id.push(student_id)

             var comment=document.querySelector(`#comment${index}`).value
            this.form.comment.push(comment)

          if(document.querySelector(`#student${index}`).checked){
           this.form.activation_status.push(true);
          }else{
           this.form.activation_status.push(false);
          }


            this.form.number_of_students=++this.form.number_of_students;
         }
        this.$Progress.start();
          if(this.$gate.isAdmin()){

            this.form.post(`api/activate_result`)

            .then(res=>{
             console.log(res.data)

              toast.fire({
                        type: 'success',
                        title: 'Result activation updated successfully'
                        })
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

         selectAll() {
            this.studentIds = [];
                 if(this.isChecked){

                    this.isChecked=false

                    return this.checkStudentId()
                 }
               const students=this.Activation_status
               this.isChecked=true
            if (!this.allSelected) {
                for (let index = 0; index <students.length; index++) {

                    this.studentIds.push(students[index].student_id);
                    this.checkStudentId()
                    this.allSelected=true
                }
                console.log(this.studentIds)
                this.checkStudentId()
            }
        },
        select(id) {
            if(this.allSelected){

            const index = this.studentIds.indexOf(id);
          if (index > -1) {
           this. studentIds.splice(index, 1);
           this.checkStudentId()
}
else{
                this.studentIds.push(id);
                this.checkStudentId()

            }


            }else{
                const index = this.studentIds.indexOf(id);
                if(index > -1){
                 this. studentIds.splice(index, 1);
                 this.checkStudentId()
                }
                else{
                   this.studentIds.push(id);
                   this.checkStudentId()
                }

            }

               this.checkStudentId()
         console.log(this.studentIds)
        },
        checkStudentId(){
            let studentLength=this.studentIds.length
            if(this.studentIds.length>0){
                      this.isStudentId=true

            }
            else{
                this.isStudentId=false

            }

             if(this.studentIds.length===this.Activation_status.length){
                          this.isSelectAll=true;
                     }else{
                         this.isSelectAll=false;
                     }
        }



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
