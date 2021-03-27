<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">CBT Questions Sheet</h1>
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
      subtitle="Examination In Progress"
      color="red"

     ><center><timer></timer></center>



        <tab-content title="Exam Details And Instruction"
             icon="fas fa-book-open"
              :before-change="startExam"
             >
         <ul class="list-group list-group-unbordered mb-3 mt-5">
                  <li class="list-group-item">
                    <b>Title:</b> <a class="pl-5"> &nbsp;{{exam.title}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Level:</b> <a class="pl-5"> &nbsp;{{exam.level.level_name}}</a>
                  </li>
                   <li class="list-group-item">
                    <b>subject:</b> <a class="pl-5">{{exam.subject.name?exam.subject.name:''}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Date:</b> <a class="pl-5"> &nbsp;{{start_date|myDate}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Time:</b> <a class="pl-5"> &nbsp;{{start_time}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Venue:</b> <a class="pl-5"> &nbsp;{{exam.venue}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Duration:</b> <a class="pl-4"> &nbsp;{{exam.duration}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Instruction:</b> <a class="pl-3 text-danger"> &nbsp;{{exam.instruction?exam.instruction:'Answer All The Questions'}}</a>
                  </li>

         </ul>

            <h3 class="text-success mt-5 text-center"> Click the next button to start Test/Exam</h3>

            </tab-content>



            <tab-content title="Question List"
                         icon=" fas fa-pencil-alt"
                         :before-change="checkSubmit"
                         >
                         <div class="container mt-5" v-show="isSuccess"><h3 class="text-danger text-center">Thanks! You have Submitted this test, You can't access the questions again</h3></div>
                          <div class="container " v-show="!isSuccess">
                <div v-for="(question,index) in questions" :key="question.id" class="col pb-2" >

                    <p v-html="question.question.question"> <span>{{index+1}}.</span> </p>
                    <div v-for="options in question.options" :key="options.id" class="row">
                  <div class="icheck-success icheck-inline">
                <input type="radio" :id="`q${options.id}`"   :value="options.option_id" :name="`q${question.question.id}`" >

                 <label :for="`q${options.id}`">{{options.options.option}}: &nbsp; {{options.option_value}}</label>
                 </div>
                    </div>
                </div>
                <div class="mt-5"><button class="btn btn-md btn-success " @click="saveAnswers">submit</button></div>
            </div>
            </tab-content>
            <tab-content title="Examiner Message"
                         icon="fas fa-graduation-cap">
             <h4 class="text-center mt-5">Thank you for participating in this test.<br>
             Wishing you a  succeful outcome!!!

</h4>
            <h3 class="text-success mt-5 text-center"> Click the next button to see your Scores</h3>

            </tab-content>
            <tab-content title="Test Results"
                         icon="fas fa-check">
            <h1  v-show="score>=50" class="text-center text-success"> Waooo !!! You have Scored {{score}}%</h1>
            <h1  v-show="score<50" class="text-center text-danger"> Sorry! You have Scored {{score}}%</h1>
            </tab-content>

        </form-wizard>
</div>
</div>
</div>
        <div v-if="!$gate.isAdminOrTutorOrStudent()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->


    <!-- Level Modal -->



    </div>
</template>


<script>
 import moment from 'moment';
    export default {

        data() {
            return {
                editmode: false,
                isExam:false,
                isSuccess:0,
                score:0,
                exams:{},
                exam:'',
                 subjects:{},
                  questions:[],
                  arms:{},
                  start_date:'',
                   end_date:'',
                  start_time:'',
                  form: new Form({
                      id:'',
                   exam_id:this.$route.params.id,
                   questions:[],



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

            onComplete(){

           console.log('finished')
       },
            deleteExam(id){
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
                                this.form.delete('api/exams/'+id).
                                then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'examhas been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })
            },

         loadQuestions(){

                if(this.$gate.isAdminOrTutorOrStudent()){
                    axios.get("/api/cbt/"+this.$route.params.id)
                    .then(res  => {this.questions = res.data.cbt
                    this.exam=res.data.exam;
                    this.score=res.data.score;
                    this.start_date=moment(this.exam.start_date).format('YYYY-MM-DD')
                    this.start_time=moment(this.exam.start_date).format('h:mm a')
                     if(res.data.isSubmitted){
                         this.isSuccess=true
                         this.score=(this.score/this.questions.length)*100
                     }
                     console.log(this.questions)
                     });

                }



        },

        checkSubmit(){
  if(this.isSuccess){
      return true
  }else{
      return false
  }
        },
startExam(){
         let now=moment(new Date()).format('YYYY-MM-DD h:mm:ss a')
        let date_diff=moment(now).diff(moment(this.exam.end_date).format('YYYY-MM-DD h:mm:ss a'))
    if(date_diff>0){
        swal.fire({
            title:'Test ended',
            text:'Sorry! This test has ended',
            type:'warning'
        })
        return false;
    }
        if((moment(now).diff(moment(this.exam.start_date).format('YYYY-MM-DD h:mm:ss a')))<0){
swal.fire({
            title:'Not Started',
            text:'Sorry! This test has not started yet',
            type:'warning'
        })
        return false;
        }
    else{


return true
    }
},
saveAnswers(){

    swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able access the questions once submitted !",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit !'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {

            this.form.questions=[];
            let selected;
          var isSuccess=false
         for (let index = 0; index < this.questions.length; index++) {
            selected=false
                 let option=document.getElementsByName(`q${this.questions[index].question.id}`)

                    for (let i = 0; i < option.length; i++){
                         if(option[i].checked){
                             selected = option[i];

                                break;
                         }
                     }
                     if(selected){
                         this.form.questions.push({'option_id':selected.value,'question_id':this.questions[index].question.id})

                     }


        }

    this.form.post('/api/save_answers')

       .then((res)=>{
            this.isSuccess=1
           this.score=res.data
           this.score=(this.score/this.questions.length)*100

       }).catch(err=>{
     this.isSuccess=0
       })

                         }})

},

        },
        created() {

           this.loadQuestions();
           Fire.$on('AfterCreate',() => {
              this.loadExams();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
