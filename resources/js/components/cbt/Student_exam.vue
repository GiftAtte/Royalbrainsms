<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">CBT List</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Exam List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class=" content  " v-if="$gate.isAdminOrTutorOrStudent()">
          <div class="col-12">
            <div class="card card-navy card-outline">
              <div class="card-header">
                <div class="row float-right">


                <div class="card-tools">


                </div>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive ">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>E/ID</th>
                        <th>Title</th>
                        <th>Level</th>
                        <th>Exam Date</th>
                        <th>Action</th>
                  </tr>


                  <tr v-for="exam in exams.data" :key="exam.id">

                    <td>{{exam.id}}</td>
                    <td>{{exam.title|upText}}  </td>
                    <td>{{exam.level.level_name}}</td>
                    <td>{{exam.start_date|myDate}}</td>


                    <td class="row">



                        <router-link :to="`/exam/${exam.id}`"  class="m-1 btn btn-sm btn-danger">
                        Exam
                        </router-link>

                       <router-link v-show="$gate.isAdminOrTutor()" :to="`/cbt_scores/${exam.id}`"  class="m-1 btn btn-sm btn-primary">
                       scores
                        </router-link>
                         <router-link v-show="$gate.isStudent()" :to="`/cbt_review/${exam.id}`"  class="m-1 btn btn-sm btn-primary">
                           Result
                        </router-link>

                       <a href="#" @click="newModal(exam.id)" v-show="$gate.isAdminOrTutor()" :to="`/cbt_scores/${exam.id}`"  class="m-1 btn btn btn-sm btn-success">
                        Use
                        </a>

                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="exams" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdminOrTutorOrStudent()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->
<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Use CBT scores As</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update exam's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>

                </div>
       <form @submit.prevent="editmode ? updateExam() : useCBT()">
      <div class="modal-body">



                     <div class="form-group ">
                      <select
                    name="report_id"
                    id="report_id"
                    :class="{'is-invalid':form.errors.has('report_id')}"
                    class="form-control"
                    v-model="form.report_id"


                  >
                    <option value selected>Select Report set</option>
                    <option
                      v-for="report in reports"
                      :key="report.id"
                      :value="report.id"
                    >{{report.title}}</option>
                  </select>
                                     <has-error :form="form" field="level_id"></has-error>
                                    </div>

<div class="form-group">

                                    <input type="number" v-model="form.cbt_value" class="form-control" id="title" placeholder=" A percentage of : eg  10, 15, 30" :class="{ 'is-invalid': form.errors.has('title') }">
                                     <has-error :form="form" field="cbt_value"></has-error>
                                    </div>
<div class="form-group ">
 <div class="icheck-success">
<input id="exam" type="radio"  v-model="form.cbt" value="exams" name="cbt" >
<label for="exam">
    Exam Score
</label>
</div>
</div>
<div class="form-group ">
 <div class="icheck-success">
<input id="ca1" type="radio"  v-model="form.cbt" value="ca1" name="cbt" >
<label for="ca1">
    CA1 Score
</label>
</div>
</div>
<div class="form-group ">
 <div class="icheck-success">
<input id="ca2" type="radio"  v-model="form.cbt" value="ca2" name="cbt" >
<label for="ca2">
    CA2 Score
</label>
</div>
</div>
<div class="form-group ">
 <div class="icheck-success">
<input id="ca3" type="radio"  v-model="form.cbt" value="ca3" name="cbt" >
<label for="ca3">
    CA3 Score
</label>
</div>
</div>



                               </div>

            <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                    <button v-show="!editmode" type="submit" class="btn btn-primary">Use</button>
           </div>
</form>
</div>
</div>
</div>

    <!-- Level Modal -->



    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import VueTimepicker from 'vue2-timepicker/dist/VueTimepicker.umd.min.js'
 import {mapState} from "vuex";
    export default {
             components:{
             VueTimepicker,Datepicker
             },
        data() {
            return {
                editmode: false,


                exams:{},
                 reports:{},
                  levels:{},
                  arms:{},
                  form: new Form({
                   cbt_value:'',
                   report_id:'',
                   cbt:'',
                   exam_id:'',




                }),

            }
        },
  mounted(){
 axios.get('/api/get_levels')
        .then(res=>{this.levels=res.data});

  },
        methods: {
            getResults(page = 1) {
                        axios.get('api/exams?page=' + page)
                            .then(response => {
                                this.exams = response.data;
                            });
                },
            updateExam(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/exams')
                .then(() => {
                    // success
                    $('#addNew').modal('hide');
                     swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                        )
                        this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },

            useCBT(id){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(exam);
            },
            newModal(id){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.exam_id=id
            },

            createExam(){
                 this.$Progress.start();
                this.form.post('api/exams')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Exam Created in successfully'
                        })
                    this.$Progress.finish();
                     $('#addNew').modal('hide');
                      Fire.$emit('AfterCreate');
                })
                .catch(()=>{

                })

            },
            loadArms(){
       axios.get('/api/get_exam_arms/'+this.form.level_id).then(res=>{
           this.arms=res.data

       })},

         loadExams(){

                if(this.$gate.isAdminOrTutorOrStudent()){
                    axios.get("api/exams").then(res  => this.exams = res.data);
                }



        },
        loadReport(){

                if(this.$gate.isAdminOrTutor()){

                    axios.get('api/load_report')
            .then(result => {
               console.log(result.data);
                this.reports=result.data;
            }).catch((err) => {

            });
                }
        },
        useCBT(){
            if(this.$gate.isAdminOrTutor())
            {
               this.form.post('/api/use_cbt')
               .then(res=>{console.log(res.data)
               $('#addNew').modal('hide');
               })

            }
        }
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findSchool?q=' + query)
                .then((data) => {
                    this.levels = data.data
                })
                .catch(() => {

                })
            })
           this.loadExams();
           this.loadReport();
           Fire.$on('AfterCreate',() => {
              this.loadExams();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
