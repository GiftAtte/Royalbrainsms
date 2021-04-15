<template>
  <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Marks Update</h2>
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
<div class="card">
    <div class="card-header row">
        <div class="col-md-6">
<h4 v-show="!isSession" class=" text-danger"><router-link :to="`/import_scores`"  tag="a" exact >Import scores {{'(cvs)'}} </router-link>&nbsp;/Select Report Set  </h4>
    <h4 v-show="isSession" class="card-heading text-primary">Select Subject</h4>
    <button v-show="isSession" @click="resetSession" class="btn btn-success float-right" ><i class="fa fa-plus"></i>Load Session</button>

        </div>

<div class="col-md-6" >
<input type="file"  ref="file" @change="setFile" ><button  @click="importExcel" class="btn btn-success pull-right m-2">import(cvs)</button>
 </div>
</div>
<form  @submit.prevent="createScore" >


<div class="card-body content">
   <loading :active.sync="isLoading"

        color="green"
        :is-full-page="true">
        </loading>
<div class="row ">

                <div v-show="!isSession" class="form-group col-md-12" >
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

                    <div  class="form-group col-md-12 col-sm-12" v-show="isArm" >
                             <select
                    name="arm_id"
                    id="arm_id"
                    :class="{'is-invalid':form.errors.has('report_id')}"
                    class="form-control"
                    v-model="form.arm_id"
                     @change="loadSubjects"

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



           <div v-show="isSession" class="form-group col-md-12 ">
                             <select
                    name="subject_id"
                    id="report_id"
                    :class="{'is-invalid':form.errors.has('subject_id')}"
                    class="form-control"
                    v-model="form.subject_id"
                   @change="loadStudents"

                         >
                    <option value selected>Select Subject</option>
                    <option
                      v-for="subject in subjects"
                      :key="subject.id"
                      :value="subject.subjects?subject.subjects.id:subject.id"
                    >{{subject.subjects?subject.subjects.name:subject.id}}</option>
                  </select>
                        <has-error :form="form" field="subject_id"></has-error>
                    </div>





</div>


<div v-show="isSubject" class="card-body table-responsive col-md-12">
  <div class="table-responsive ">
<table class="table table-hover table-sm ">
<thead>

<tr>
<th>S/N</th><th>Name</th><th>CA1</th><th>CA2</th><th>CA3</th><th>Exams</th>
</tr>
</thead>
<tbody>
<tr v-for="(score,index) in Scores">
<td>{{index+1}}</td>
<td>{{score.name}}
 <input type="hidden" :id="`student_id${index}`" :value="score.student_id">
 <input type="hidden" :id="`arm_id${index}`" :value="score.arm_id">
 </td>
<td><input :id="`test1${index}`" :value="score.test1" type="number"  placeholder="Enter CA1 " max="100" min="0" step="0.01"></td>
<td><input :id="`test2${index}`" :value="score.test2" type="number"  placeholder="Enter CA2 " max="100" min="0" step="0.01"></td>
<td><input :id="`test3${index}`" :value="score.test3" type="number" placeholder="Enter CA3 " max="100" min="0" step="0.01"></td>
<td><input :id="`exams${index}`" :value="score.exams" type="number"   placeholder="Enter Exam (Mid-Term Test)" max="100" min="0" step="0.01"></td>
</tr>

</tbody>
</table>
</div>
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
                file:'',
                isSession:false,
                isSubject:false,
                reports:{},
                subjects:{},
                isArm:false,
                Scores:[],
                report_id:'',
                $id:'',
                $arms:{},
                report:{
                    type:'mid_term'
                },
                isLoading:false,
form:new Form({
     report_id:'',
           name:[],
     student_id:[],
     arm_id:[],
     subject_id:'',
           test1:[],
           test2:[],
           test3:[],
           exams:[],
           arm_id:'',
number_of_students:0
})
            }


        },
        mounted(){
            axios.get('api/load_report')
            .then(result => {
               console.log(result.data);
                this.reports=result.data;
            }).catch((err) => {

            });
           // this.loadSubjects();
        },
        methods:{
            resetSession(){
          // this.report_id=this.form.report_id
           this.isSession=false;
           this.form.reset();
           this.Scores='';
            },
           loadStudents(){
                this.$Progress.start();
         this.form.post(`api/load_students`)
          .then((result) => {
             this.Scores=result.data;

              console.log(this.Scores);
             this.$Progress.finish();
              this.isSubject=true

          }).catch((err) => {
              this.$Progress.fail();
          });
           },

        loadSubjects(){
                //this.isSession=false;
                if(this.$gate.isAdminOrSubjectTutor()){
                  this.isSession=true;
                   this.$Progress.start();
                    axios.get("api/load_list/"+this.form.report_id)
                    .then( res  => {
                      this.subjects = res.data;
                         this.report_id=this.form.report_id
                           console.log(this.report_id)
                            this.$Progress.finish();
                          }
                      );
                }


            },

           loadArms(){

    axios.get('/api/getArms/'+this.form.report_id)
    .then(res=>{
           this.arms=res.data
           this.isArm=true
       })

       },

setFile () {
                this.file=this.$refs.file.files[0];
                console.log(this.file);
          },


           importExcel(){
              // this.isLoading=true;
           const formData=new FormData();
           formData.append('file',this.file);
          axios.post('/api/importExcel',formData)
           .then(res=>{

               toast.fire({
                        type: 'success',
                        title: 'Assignment successfully uploaded'
                        })
                         console.log(res.data)
                    this.$Progress.finish();
               this.isLoading=false;
                    Fire.$emit('AfterCreate');

           })
           .catch(err=>{
               this.$Progress.fail();
              toast.fire({
                        type: 'failure',
                        title: 'there was error uploading the file'+err
                        })
                         console.log(err)
           })


            },


            createScore(){
            this.isLoading=true
            this.form.student_id=[];
            this.form.test1=[];
            this.form.test2=[];
             this.form.test3=[];
            this.form.exams=[];
            this.form.arm_id=[];
            this.form.number_of_students=0;
         for (let index = 0; index < this.Scores.length; index++) {

           var student_id=document.querySelector(`#student_id${index}`).value
           var test1=document.querySelector(`#test1${index}`).value
           var test2=document.querySelector(`#test2${index}`).value
           var exams=document.querySelector(`#exams${index}`).value
            var test3=document.querySelector(`#test3${index}`).value
           var arm_id=document.querySelector(`#arm_id${index}`).value
            this.form.student_id.push(student_id)
            this.form.test1.push(test1)
            this.form.test2.push(test2)
            this.form.test3.push(test3)
            this.form.exams.push(exams)
            this.form.arm_id.push(arm_id)

            this.form.number_of_students=++this.form.number_of_students;
         }
            console.log(this.form.arm_id)
            this.form.post('api/scores')

            .then(res=>{

              toast.fire({
                        type: 'success',
                        title: 'marks added successfully'
                        })
                       this.isSubject=false;
             this.isLoading=false

              }).catch(err=>{
                toast.fire({
                        type: 'danger',
                        title: 'there was error uploading the file'+err
                        })
                        this.isLoading=false
              });

            }
    },
    created() {

          // this.loadSubjects();

           Fire.$on('AfterCreate',() => {

              // this.loadSubjects();
              // $this.form.reset();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
