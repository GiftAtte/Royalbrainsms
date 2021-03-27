<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">CBT Score List</h1>
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
              <div class="container">
        <loading :active.sync="isLoading"

        color="green"
        :is-full-page="true"></loading>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive ">

                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>S/ID</th>
                        <th>Name</th>
                        <th>scores 100%</th>
                        <th>Action</th>
                  </tr>


                  <tr v-for="score in scores" :key="score.id">

                    <td>{{score.id}}</td>
                    <td>{{score.name}} </td>
                    <td>{{score.score}} </td>
                    <td class="row">



                        <router-link :to="`/cbt_review/${score.exam_id}/${score.id}`" tag="a" class="pl-2">
                       Answers sheet
                        </router-link>


                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <!-- <pagination :data="scores" @pagination-change-page="getResults"></pagination> -->
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdminOrTutorOrStudent()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->

</div>


    <!-- Level Modal -->




</template>

<script>
import Loading from 'vue-loading-overlay';

     export default{
        components: { Loading },


        data() {
            return {
                editmode: false,


                scores:[],
                 subjects:{},
                  levels:{},
                  arms:{},
                  exam_id:'',
                  isLoading:false,
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
            getResults(page = 1) {
                        axios.get(`/api/cbt_scores/${this.$route.params.id}?page=` + page)
                            .then(response => {
                                this.scores = response.data;
                            });
                },

         loadScores(){

                if(this.$gate.isAdminOrTutor()){
                    this.isLoading=true;
                    axios.get("/api/cbt_scores/"+this.$route.params.id).then(res  => {
                        this.scores = res.data
                         this.isLoading=false;
                        });

                }



        },
        loadSubjects(){

                if(this.$gate.isAdminOrTutor()){

                    axios.get("api/load_list").then( res  => {
                      this.subjects = res.data;

                          }
                      );
                }
        },
        isActive(id){
            if(id===window.user.school_id)
            {
                console.log(id)
                return true
            }else{
                console.log(id)
                return false
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
           this.loadScores();

        //    Fire.$on('AfterCreate',() => {
        //       this.loadExams();
        //    });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
