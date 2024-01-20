<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">CBT Config</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Exam  Config</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
 <div class="col-md-12">
 <div class="card card-navy card-outline">
     <div class="card-bod">
     <form-wizard @on-complete="onComplete"
      title="Examination Center"
      subtitle="Exam configuration and settings"
      color="red"

     >
            <tab-content title="Exam Details"
                         icon=" fas fa-pencil-alt"
                         :before-change="saveExam">
                    <div class="container">

                                <div class="form-group row">
                                    <label for="inputName" class="col-md-2 control-label">Exam Tittle</label>

                                    <div class="col-md-10">
                                    <input type="" v-model="form.title" class="form-control" id="title" placeholder="title" :class="{ 'is-invalid': form.errors.has('title') }">
                                     <has-error :form="form" field="title"></has-error>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="subject" class="col-md-2 control-label">Subject</label>

                                    <div class="col-md-10">
                                    <select
                    name="subject_id"
                    id="subject_id"
                    :class="{'is-invalid':form.errors.has('subject_id')}"
                    class="form-control"
                    v-model="form.subject_id"


                  >
                    <option value selected>Select subject to Add to list</option>
                    <option
                      v-for="subject in subjects"
                      :key="subject.id"
                      :value="subject.id"
                    >{{subject.name}}</option>
                  </select>
                                     <has-error :form="form" field="level_id"></has-error>
                                    </div></div>
                                   <div class="form-group row">
                                    <label for="inputName" class="col-md-2 control-label">Level</label>

                                    <div class="col-md-10">
                                    <select
                    name="subject_id"
                    id="subject_id"
                    :class="{'is-invalid':form.errors.has('level_id')}"
                    class="form-control"
                    v-model="form.level_id"
                     @change="loadArms"

                  >
                    <option value selected>Select Level</option>
                    <option
                      v-for="level in levels"
                      :key="level.id"
                      :value="level.id"
                    >{{level.level_name}}</option>
                  </select>

                                     <has-error :form="form" field="level_id"></has-error>
                                    </div></div>
                                   <div class="form-group row">
                                    <label for="inputName" class="col-md-2 control-label">Arm</label>
                                    <div class="col-md-10">
                                    <select
                    name="arm_id"
                    id="arm_id"
                    :class="{'is-invalid':form.errors.has('report_id')}"
                    class="form-control"
                    v-model="form.arm_id"


                         >
                    <option value selected>Select Class/Level Arm</option>
                    <option
                      v-for=" arm in arms"
                      :key="arm.arms.id"
                      :value="arm.arms.id"

                    >{{arm.arms.name}}</option>
                  </select>
                                     <has-error :form="form" field="arm_id"></has-error>
                                    </div>
                                   </div>
                                   <div class="form-group row">
                                    <label for="inputName" class="col-md-2 control-label">Scheduled Date</label>

                                    <div class="col-md-10">
                                     <datepicker input-class="form-control"
                                      placeholder="Exam/Test Start Date"

                                     v-model="form.start_date"
                                     :class="{ 'is-invalid': form.errors.has('start_date') }"></datepicker>
                                     <has-error :form="form" field="end_date"></has-error>
                                    </div>
                                   </div>
                                   <div class="form-group row">
                                    <label for="inputName" class="col-md-2 control-label">Scheduled Time</label>

                                    <div class="col-md-10">
                                    <vue-timepicker format="hh:mm A" input-width="100%"
                                    placeholder="Exam time "
                                    :close-on-complete="true"
                                    v-model="form.start_time"
                                    :class="{ 'is-invalid': form.errors.has('title') }"></vue-timepicker>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputName" class="col-md-2 control-label">Exam Duration</label>

                                    <div class="col-md-10">
                                    <input class="form-control" type="number"
                                    placeholder="Exam duration (optional)"
                                    v-model="form.duration"
                                    :class="{ 'is-invalid': form.errors.has('duration') }">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputName" class="col-md-2 control-label">Exam Venue</label>

                                    <div class="col-md-10">
                                    <input type="text" v-model="form.venue" name="venue" class="form-control" id="title" placeholder="Exam venue" :class="{ 'is-invalid': form.errors.has('venue') }">
                                     <has-error :form="form" field="venue"></has-error>
                                    </div>
                                </div>

                                </div>
            </tab-content>
            <tab-content title="Exam Questions"
                         icon="fas fa-question">
              My second tab content
            </tab-content>
            <tab-content title="Finished"
                         icon="fas fa-check">
              Yuhuuu! This seems pretty damn simple
            </tab-content>

        </form-wizard>
</div>
</div>
</div>
        <div v-if="!$gate.isAdminOrTutor()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->


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
               config:{},

                selected_file:'',
                  activate:false,
                  subjects:{},
                  levels:{},
                  arms:{},
                   form: new Form({
                       id:'',
                   title:'',
                   subject_id:'',
                   exam_date:'',
                   level_id:'',
                   arm_id:'',
                   venue:'',
                   time:'',
                   duration:''

                }),

            }
        },
       mounted(){
        axios.get('/api/get_levels')
        .then(res=>{this.levels=res.data});


       },
        methods: {
             onComplete: function(){
          alert('Yay. Done!');
       },

        saveExam(){

     this.form.post('api/exam').then( res=>{
         this.form.id=res.data

        })
     .catch(err=>{
     })
if(this.form.id>0){
    console.log(this.form.id)
return true

}
else{
return false
}
        },

        setConfig(){
            this.$Progress.start()


          this.form.post('api/result_config')
        .then(()=>{
                toast.fire({
                        type: 'success',
                        title: 'Configuration Saved Successfully'
                        })
                    this.$Progress.finish();
                     Fire.$emit('AfterCreate')
        })
        },
         loadSubjects(){

                if(this.$gate.isAdminOrTutor()){

                    axios.get("api/load_list").then( res  => {
                      this.subjects = res.data;

                          }
                      );
                }
        },
        loadArms(){
       axios.get('/api/get_exam_arms/'+this.form.level_id).then(res=>{
           this.arms=res.data

       })},

        },
        created() {
            this.loadSubjects();


           Fire.$on('AfterCreate',() => {

           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
