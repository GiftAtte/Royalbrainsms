<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">CBT Questions</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Questions  settings</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
 <div class="col-md-12">
 <div class="card card-navy card-outline">
     <div class="card-header"><button @click="setForm" class="btn btn-success card-tools float-right"><i class="fas fa-user-plus" ></i>Add New</button></div>
     <div class="card-bod">

<div v-show="!addNew"  class="col-md-12">
    <table class="table table-striped">
        <thead>
            <tr>
               <th>S/N</th><th>Questions</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
 <tr v-for="(question,index) in questions.data " :key="question.id">
     <td>{{index+1}}</td>
     <td v-html="question.question"></td>
     <td>
         <a href="#" @click="deleteQuestion(question.id)"><i class="fas fa-trash pl-2 red" ></i></a>/

     <a href="#" @click="updateQuestion(question)"><i class="fas fa-edit pl-2 green"></i></a>/
     <router-link :to="`/options/${question.id}`"><i class="fas fa-eye pl-2"></i></router-link>

     </td>
 </tr>

        </tbody>
    </table>
</div>



                             <div v-show="addNew" class="col-md-12" >
                                <div class="form-group col-md-12 mt-5">
                                  <label>Question</label>
                                  <editor apiKey="iwn8pu5nycy45llbr36ffh5czp4yt712apbn3txi3fsf2oda"
                                  v-model="form.question"
                                  :init="{


         height: 300,
          menubar: 'file edit view insert format tools table tc help',
        imagetools_cors_hosts: ['picsum.photos'],
         plugins: [
           'advlist autolink lists link image charmap print preview anchor',
           'searchreplace visualblocks code fullscreen',
           'insertdatetime media table paste code help wordcount'
         ],
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',

               image_caption: true,
                importcss_append: true,
                 importcss_append: true,


       }"

                                  />

                                  <div v-html="form.question"></div>




                                     <has-error :form="form" field="question"></has-error>
                                    </div>

                                    <div v-for="option in options" :key="option.id" class="form-group col-md-12 ">
                                     <label>Option: {{option.option}}</label>
                                    <input class="  form-control" :id="`${option.id}`" placeholder=" Enter optio  value"
                                         >
                                    </div>
                                    <div class="form-group col-md-12 ">
                                     <label class="text-bold text-danger">Right Option</label>
                                        <select
                                        class="form-control"
                                        v-model="form.right_option">
                                        <option selected value=""> Select The Right Option</option>
                                            <option v-for="option in options" :key="option.id"
                                             :value="option.id">Option: {{option.option}}</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-12 ">
                                        <input type="number" v-model="form.marks" required="true" placeholder="Enter Marks"
                                        class="form-control">
                                    </div>
</div>

  <div class="card-footer">
      <button  v-show="addNew" class="btn btn-success " @click="saveQuestion">Save question</button>
      <button v-show="addNew" class="btn btn-danger " @click="cancel">cancel</button>
  </div>

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
  import Editor from '@tinymce/tinymce-vue'
 import {mapState} from "vuex";
    export default {
             components:{
             VueTimepicker,Datepicker,
             editor: Editor
             },
        data() {
            return {
                editmode: false,
               config:{},
                questions:{},
                selected_file:'',
                  addNew:false,
                  isUpdate:false,
                  options:[],
                  E_options:[],
                  questions:{},
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


       },
        methods: {
             deleteQuestion(id){
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
                               axios.delete('/api/question/'+id).
                                then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'question has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })



            },
            cancel(){
  this.addNew=false
  this.loadQuestions();
            },
       setForm(){
          if(this.E_options.length>0){
         for(let i=0; i < this.options.length; ++i){

              document.getElementById(`${i+1}`).value=''
                   this.form.reset()

             }
          }

          this.addNew=true


       },
        saveQuestion(){
   this.form.option_id=[];
    for(let i=1; i <= this.options.length; ++i){
       let option= document.getElementById(`${i}`).value
       this.form.option_id.push({option_id:i,option:option})
    }
    console.log(this.form.option_id);


     this.form.post('/api/question_options').then( res=>{
  this.form.id=res.data
  this.addNew=false
  toast.fire({
                        type: 'success',
                        title: 'Question Saved Successfully'
                        })
                    this.$Progress.finish();
                     Fire.$emit('AfterCreate')

        })
     .catch(err=>{
     })


        },
          updateQuestion(question){
             this.E_options=[],
              this.form.fill(question)
              axios.get("/api/question/"+question.id).then( res  => {
                      this.questions = res.data.question;
                      this.E_options=res.data.options
                      this.form.right_option=res.data.answer.option_id


             for(let i=0; i < this.options.length; ++i){

              document.getElementById(`${i+1}`).value=this.E_options[i].option_value
                   console.log(document.getElementById(`${i+1}`).value)

             }
                       this.addNew=true

              })

          },


         loadQuestions(){

                if(this.$gate.isAdminOrTutor()){
 this.$Progress.start()
                    axios.get("/api/questions/"+this.form.exam_id).then( res  => {
                      this.questions = res.data;
                           if(this.questions.length>0){
         this.isNew=false
          this.$Progress.finish()
                           }
                          }
                      );
                }
        },
        loadOptions(){
            this.options={},
       axios.get('/api/exam_options').then(res=>{
           this.options=res.data
            console.log(this.options)
       })},

        },
        created() {

            this.loadOptions();
            this.loadQuestions();



           Fire.$on('AfterCreate',() => {
this.loadQuestions();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
