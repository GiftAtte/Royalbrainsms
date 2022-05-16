<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Export Login Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Login Details</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class="content" v-if="$gate.isAdmin()">
          <div class="col-12">
            <div class="card">
              <div class="card-header">

                <div class="row float-right">


                <div class="card-tools">

               <button class="btn btn-success btn-sm float-right m-2" @click="loadSchools">load Schools <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>S/N</th>
                        <th>Details Type</th>
                        <th>Action</th>
                  </tr>


                  <tr>
                    <td>1</td>
                    <td> Student Login Details </td>

                    <td>
     <export-excel
       class="btn btn-primary"

       :data="student_login">
       Download Data
       <i class="fa fa-download"></i>
     </export-excel>


                    </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td> Staff Login Details</td>
                    <td>
     <export-excel
       class="btn btn-primary"
       :data="staff_login"

     >
       Download Data
       <i class="fa fa-download"></i>
     </export-excel>


                    </td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td> Parents Logins</td>
                    <td>
     <export-excel
       class="btn btn-primary"
       :data="parent_login"

     >
       Download Data
       <i class="fa fa-download"></i>
     </export-excel>


                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">

              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>


<!-- Arms Modal -->


    <!-- Level Modal -->



    </div>
</template>

<script>
 import {mapState} from "vuex";
    export default {
      computed: mapState(['student_login','staff_login','parent_login']),

        data() {
            return {
                editmode: false,
                 details:[],

                schools:{},
                selected_file:'',
                  activate:false,
                  form: new Form({
                    id : '',
                    name:'',
                    state:'',
                    contact_address:'',
                    phone:'',
                    website:'',
                    logo:'',
                    country:'',
                    short_name:'',
                    email: '',

                }),
               json_data: [
            {
                'name': 'Tony Peña',
                'city': 'New York',
                'country': 'United States',
                'birthdate': '1978-03-15',
                'phone': {
                    'mobile': '1-541-754-3010',
                    'landline': '(541) 754-3010'
                }
            },
            {
                'name': 'Thessaloniki',
                'city': 'Athens',
                'country': 'Greece',
                'birthdate': '1987-11-23',
                'phone': {
                    'mobile': '+1 855 275 5071',
                    'landline': '(2741) 2621-244'
                }
            }
        ],
            }
        },

        methods: {

            getResults(page = 1) {
                        axios.get('api/schools?page=' + page)
                            .then(response => {
                                this.subjects = response.data;
                            });
                },
                loadData(){
                    axios.get('api/login_export')
                    .then(res=>{
                       return res.data

                    })
                   // return this.json_data
                },



            deleteSchool(id){
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
                                this.form.delete('api/school/'+id).
                                then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'school has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })
            },

         loadSchools(){

                if(this.$gate.isAdminOrTutor()){
                    axios.get("api/school").then(res  => this.schools = res.data);
                }



        },

        },
        created() {
        //  this.loadData()
            this.$store.dispatch('loginDetails');
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findSchool?q=' + query)
                .then((data) => {
                    this.levels = data.data
                })
                .catch(() => {

                })
            })
          // this.loadSchools();

           Fire.$on('AfterCreate',() => {
               this.loadSchools();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
