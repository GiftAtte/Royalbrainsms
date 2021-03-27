<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">CBT</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Question Options</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
 <div class="col-md-12">
 <div class="card card-navy card-outline">
     <div class="card-bod">
<div class="card-header bg-primary"> Question Options</div>
<div class="col-md-12" >
    <p class="mt-5 ml-5" v-html="questions.question"></p>
</div>
<div v-for="option in options"  :key="option.id" class="ml-5">
<h5>{{option.options.option}}: &nbsp; {{option.option_value}}</h5>
</div>
<div class="ml-5">
<h3 class="text-success mt-5  "> RIGHT OPTION</h3>
<h4 class="mb-5"> {{answer.options.option}}:&nbsp; {{answer.option_value}}.&nbsp; &nbsp; =>>>>&nbsp;&nbsp; <span class="text-danger">{{questions.marks}}marks</span> </h4>&nbsp;

</div>


</div>

  <div class="card-footer">
     <router-link :to="`/questions/${answer.exam_id}`" class="btn btn-danger">Go Back</router-link>

  </div>

                                </div>

</div>
</div>

<!-- Arms Modal -->


    <!-- Level Modal -->



</template>

<script>
import Datepicker from 'vuejs-datepicker';
import VueTimepicker from 'vue2-timepicker/dist/VueTimepicker.umd.min.js'

 import { timer } from 'vue-timers'
    export default {

             components:{
             VueTimepicker,Datepicker
             },
        data() {
            return {
                editmode: false,
               config:{},
                questions:{},
                selected_file:'',
                  isNew:false,
                  isUpdate:false,
                  options:{},
                  questions:{},
                  answer:'',
                   form: new Form({
                       id:'',
                   question:'',
                   exam_id:this.$route.params.id,
                   level_id:'',
                   option_id:[],
                   right_option:'',
                   marks:'',


                }),

            }
        },
       mounted(){

   $('.textarea').summernote()
       },
        methods: {
            log () {
      console.log('Hello world')
    },

         loadQuestions(){

                if(this.$gate.isAdminOrTutor()){
 this.$Progress.start()
                    axios.get("/api/question/"+this.$route.params.id).then( res  => {
                      this.questions = res.data.question;
                      this.options=res.data.options
                      this.answer=res.data.answer
                         console.log(res.data)
 this.$Progress.finish()
                          }
                      );
                }
        },

        },
        created() {

            this.loadQuestions();



           Fire.$on('AfterCreate',() => {

           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
