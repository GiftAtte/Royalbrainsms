<template>
  <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Subject Performance Tracker</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Performance Tracker</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="content col-12">
<div class="card">
    <div class="card-header row">
        <div class="col-md-6">

   

        </div>


</div>
<form  @submit.prevent="createScore" >


<div class="card-body content">
   <loading :active.sync="isLoading"

        color="green"
        :is-full-page="true">
        </loading>
<div class="row ">

                <div  class="form-group col-md-12" >
                             <select
                    name="report_id"
                    id="report_id"
                    :class="{'is-invalid':form.errors.has('report_id')}"
                    class="form-control"
                    v-model="form.report_id"
                    @change="loadSubjects"

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
        <!-- scondary Section -->
<table  class="table table-hover table-sm ">
<thead>

<tr>
<th>S/N</th><th>Name</th><th>Terminal Total</th><th>Grand Total</th><th>Cummulative Average</th> <th>Overal Position</th>
</tr>
</thead>
<tbody>
<tr v-for="(score,index) in Scores" :key="index">
<td>{{index+1}}</td>
<td>{{score.name}}
 </td>
<td>{{ score.total?score.total:'' }}</td>
<td>{{ score.grand_total?score.grand_total:'' }}</td>
<td>{{ score.average?score.average :'' }}</td>
<td>{{ score.position?score.position :'' }}</td>
</tr>

</tbody>
</table>

</div>
<div class="card-footer">

    </div>
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
                report:'',
                isSession:false,
                isSubject:false,
                reports:{},
                subjects:{},
                isArm:false,
                Scores:[],
                report_id:'',
                id:'',
                note:'',
                arms:{},
                report:{
                    type:'mid_term'
                },
                isLoading:false,
form:new Form({
     report_id:'',
     subject_id:'',

})
            }


        },
        mounted(){
            axios.get('api/load_report')
            .then(result => {
             //  console.log(result.data);
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
               this.checkReport(this.form.report_id);
         this.form.post(`api/load_performance`)
          .then((result) => {
             this.Scores=result.data;

               console.log(this.report)
             this.$Progress.finish();
              this.isSubject=true

          }).catch((err) => {
              this.$Progress.fail();
          });
           },

           deleteScores(){
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.form.post('api/deleteScores').
                                then((res)=>{
                                        swal.fire(
                                        'Deleted!',
                                        res.data.Message,
                                        'success'
                                        )
                                    this.loadStudents();
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })
            },


          checkReport(id){

               axios.get("/api/checkreport/"+id).then( response  => {

                      this.report= response.data.type


               // console.log(this.report_id)
                      });

        },
        loadSubjects(){
                //this.isSession=false;
                if(this.$gate.isAdminOrSubjectTutor()){
                  this.isSession=true;
                   this.$Progress.start();
                    axios.get("api/load_list/"+this.form.report_id+'/'+1)
                    .then( res  => {
                      this.subjects = res.data;
                         this.report_id=this.form.report_id
                          // console.log(this.report_id)
                            this.$Progress.finish();
                          }
                      );
                }


            },



setFile () {
                this.file=this.$refs.file.files[0];
                console.log(this.file);
          },


           importExcel(){
               this.isLoading=true;
           const formData=new FormData();
           formData.append('file',this.file);
          axios.post('/api/importExcel',formData)
           .then(res=>{

               swal.fire(
                        'success',
                        'Uploaded Successfully.',
                        'success'
                        )
                         console.log(res.data)
                    this.$Progress.finish();
               this.isLoading=false;
                    Fire.$emit('AfterCreate');

           })
           .catch(err=>{
               this.$Progress.fail();
              swal.fire(
                        'error',
                        'Errors uploadind.'+err,
                        'error'
                        )
                         this.isLoading=false;
           })


            },


            createScore(){
            this.isLoading=true
            this.form.student_id=[];
            this.form.test1=[];
            this.form.test2=[];
             this.form.test3=[];
            this.form.exams=[];
             this.form.note=[];
            this.form.midterm=[];
            this.form.number_of_students=0;
            var check=false
            if(this.Scores[0].total!=null){check=true}
         for (let index = 0; index < this.Scores.length; index++) {

           var student_id=document.querySelector(`#student_id${index}`).value
           var test1=document.querySelector(`#test1${index}`).value
           var test2=document.querySelector(`#test2${index}`).value
           var exams=document.querySelector(`#exams${index}`).value

            this.form.student_id.push(student_id)
            this.form.test1.push(test1)

            this.form.test2.push(test2)
               if(this.report!='primary-midterm' && this.report!='primary-terminal'){
              var test3=document.querySelector(`#test3${index}`).value
              if(check){
              var midterm=document.querySelector(`#midterm${index}`).value
              this.form.midterm.push(midterm)
              console.log(check)
              }
              var note=document.querySelector(`#note${index}`).value

            this.form.test3.push(test3)
             this.form.note.push(note)
               }
            this.form.exams.push(exams)



            this.form.number_of_students=++this.form.number_of_students;
         }
            console.log('Running Herae')
            if(this.report==='primary-midterm'||this.report==='primary-terminal'){
              //   console.log('Running Herae')
              this.form.post('/api/primary_scores')

            .then(res=>{

              toast.fire({
                        type: 'success',
                        title: 'marks added successfully'
                        })
                       this.isSubject=false;
                   this.isLoading=false
                  // this.Scores=[]

              }).catch(err=>{
                toast.fire({
                        type: 'danger',
                        title: 'there was error uploading the file'+err
                        })
                        this.isLoading=false
              });



            }else{
            this.form.post('/api/scores')

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

            }}
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
