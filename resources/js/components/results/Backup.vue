<template>
     <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Backup scores</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Backup scores</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>


<div class="card card-navy card-outline">
<div class="card-header">
<div class="ribbon-wrapper">
    <div class="ribbon bg-primary">
     Backup
      </div>



    </div>
  <loading :active.sync="isLoading"
        :can-cancel="false"
        :on-cancel="onCancel"
        color="blue"
        :is-full-page="fullPage"></loading>
 <export-excel
 v-show="isSubject"
       class="btn btn-primary"
       :data="score_template"
       :name="`${subject}.csv`"
       type="csv"

       >
       Download Scores
       <i class="fa fa-download"></i>
     </export-excel>
 <button v-show="!isSession" @click="resetSession" class="btn btn-success float-right" ><i class="fa fa-plus"></i>Load Session</button>
</div>
<div class="card-body">

<div class="row ">

                <div v-show="!isSession" class="form-group col-md-12" >
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

           <div v-show="isSession" class="form-group col-md-12">
                             <select
                    name="subject_id"
                    id="report_id"
                    class="form-control"
                    v-model="subject_id"
                   @change="downloadScores"

                         >   <option value selected>Select Subject</option>
                    <option
                      v-for="subject in subjects"
                      :key="subject.id"
                      :value="subject.subjects?subject.subjects.id:subject.id"
                    >{{subject.subjects?subject.subjects.name:subject.name}}</option>
                  </select>
                        <has-error :form="form" field="subject_id"></has-error>
                    </div>

</div>

    </div>    </div>

    </div>

</template>





<script >
 import { VueCsvImport } from 'vue-csv-import';
  import Loading from 'vue-loading-overlay';

     export default{
        components: { VueCsvImport,Loading },


        data(){

            return{
                isSession:false,
                isSubject:false,
                isLoading:false,
                fullPage: true,
                reports:{},
                subjects:{},
                Scores:[],
                subject:'',
                report_id:'',
                $id:'',
                subject_id:'',
                score_template:[],
                isloading:false,

form:new Form({
     report_id:'',
     subject_id:'',

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
           //this.form.reset();
           this.Scores='';
            },
           loadStudents(){
                this.$Progress.start();
          axios.get(`api/load_students/${this.report_id}/${this.form.subject_id}`)
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
                if(this.$gate.isAdminOrTutor()){
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
            downloadScores(){
  axios.get(`/api/backup/${this.report_id}/${this.subject_id}`)
  .then(res=>{
    this.score_template= res.data.score_template;
     this.subject=res.data.subject
   this. isSubject=true

  })
            },
              afterImport(){


                    toast.fire({
                        type: 'success',
                        title: 'Scores Imported successfully'
                        })
                      this.resetLoading()
                        this.$router.push('/scores')
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


