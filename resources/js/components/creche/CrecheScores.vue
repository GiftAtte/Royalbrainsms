<template>
  <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Form Tutor Comment</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Comments</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="content col-md-12">
<div class="card">
    <div class="card-header row">
        <div class="container">
<h4 v-show="!isSession" class=" text-danger">Select Report Set  </h4>

    <button v-show="isSession" @click="resetSession" class="btn btn-success float-right" ><i class="fa fa-plus"></i>Load Session</button>

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
                     @change="loadStudents"

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




<div class="col-md-12 row">
    <div class="form-group col-md-6" >
                     <select
                    name="sudomain_id"
                    id="subdomain_id"
                    :class="{'is-invalid':form.errors.has('subdomain_id')}"
                    class="form-control"
                    v-model="form.domain_id"
                   @change="loadSubdomain"

                  >
                    <option value selected>Select subject to Add to list</option>
                    <option
                      v-for="domain in domains"
                      :key="domain.id"
                      :value="domain.id"
                    >{{domain.name}}
                    </option>
                  </select>
                 </div>
                <div class="form-group col-md-6" >
                             <select
                    name="sudomain_id"
                    id="subdomain_id"
                    :class="{'is-invalid':form.errors.has('subdomain_id')}"
                    class="form-control"
                    v-model="form.subdomain_id"
                       @change="loadRating"

                  >
                    <option value selected>Select subdomain</option>
                    <option
                      v-for="subdomain in Subdomains"
                      :key="subdomain.id"
                      :value="subdomain.id"
                    >{{subdomain.name}}
                    </option>
                  </select>
                        <has-error :form="form" field="subject"></has-error>
                    </div>


</div>



</div>


<div v-show="isSubject"   class="card-body table-responsive col-md-12 py-3 table-striped">
  <div class="table-responsive ">
        <!-- scondary Section -->
<table  class="table table-hover table-sm ">
<thead>

<tr>
<th>S/N</th><th>Students Name</th><th>Add Comments</th>
</tr>
</thead>
<tbody>
<tr v-for="(score,index) in Scores" :key="index">
<td>{{index+1}}</td>
<td>{{score.name}}
 <input type="hidden" :id="`student_id${index}`" :value="score.student_id">
 <!-- <input type="hidden" :id="`arm_id${index}`" :value="score.arm_id"> -->
 </td>
<td>

     <select
                    name="rate_id"
                    :id="`rate_id${index}`"

                    class="form-control"

                         >
                    <option value selected>Select comment</option>
                    <option
                      v-for="comment in comments"
                      :key="comment.id"
                      :value="comment.id"
                    >{{comment.rate}}</option>
                  </select>
</td>

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
                comments:'',
                report:'',
                isSession:false,
                isSubject:false,
                reports:{},
                Subdomains:{},
                domains:{},
                isArm:false,
                Scores:'',
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
           name:[],
           domain_id:'',
           subdomain_id:'',
     student_id:[],
     rate_id:[],
           arm_id:'',
number_of_students:0
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

         this.form.post(`/api/load_comments`)
          .then((result) => {
             this.Scores=result.data;

               console.log(this.Scores)
             this.$Progress.finish();
              this.isSubject=true

          }).catch((err) => {
              this.$Progress.fail();
          });
           },



 loadSubdomain(){

                if(this.$gate.isAdminOrTutor()){
                    axios.get("/api/subdomains/"+this.form.domain_id)
                    .then(res  => {
                        this.Subdomains = res.data
                        //this.domainName=res.data[0].domains.name
                        });
                }



        },

loadDomain(){

                if(this.$gate.isAdminOrTutor()){
                    axios.get("api/domains").then(res  => this.domains = res.data);
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
               this.isLoading=true;
           const formData=new FormData();
           formData.append('file',this.file);
          axios.post('/api/importExcel',formData)
           .then(res=>{

               toast.fire({
                        type: 'success',
                        title: 'Scores successfully uploaded'
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
            this.form.rate_id=[];
            this.form.number_of_students=0;
         for (let index = 0; index < this.Scores.length; index++) {

           var student_id=document.querySelector(`#student_id${index}`).value
           var rate_id=document.querySelector(`#rate_id${index}`).value
           //var comment_id=e.selectElement.options[e.selectedIndex].value


            this.form.student_id.push(student_id)
            this.form.rate_id.push(rate_id)
            this.form.number_of_students=++this.form.number_of_students;
         }
             //  console.log(this.form)
            this.form.post('/api/crecheComment')

            .then(res=>{

              toast.fire({
                        type: 'success',
                        title: 'Comments added successfully'
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

            },
            loadRating(){
                 axios.get('/api/ratings')
            .then(res=>{
                this.loadStudents()
                this.comments=res.data
            })
            }
    },
    created() {

          this.loadDomain();

           Fire.$on('AfterCreate',() => {

              // this.loadSubjects();
              // $this.form.reset();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
