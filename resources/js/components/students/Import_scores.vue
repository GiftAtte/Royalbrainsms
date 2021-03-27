<template>
     <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Import scores</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Import scores</li>
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
       Download Template
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
                   @change="downloadTemplate"

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
<vue-csv-import
    v-model="csv"
    url="/api/import_scores"
    :map-fields="['report_id','student_id','subject_id','arm_id','test1', 'test2','test3','exams','is_history']"
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
            <th>Select Columns To Match</th>

 
   </tr>
<rotate-square2 v-show="isloading"   id="loader"></rotate-square2>
     
    </template>

    <template slot="next" slot-scope="{load}">
        <button class="btn btn-primary" @click.prevent="load">load scores!</button>
    </template>

    <template slot="submit" slot-scope="{submit}">
        <button class="btn btn-success" @click.prevent="submit"><i class="fa fa-user-plus" @click="setLoading"> Submit</i></button>
    </template>
</vue-csv-import>

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
            downloadTemplate(){
  axios.get(`/api/score_template/${this.report_id}/${this.subject_id}`)
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


