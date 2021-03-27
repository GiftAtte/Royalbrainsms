<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Grading Group</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Settings</a></li>
              <li class="breadcrumb-item active">Grading List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class="content" v-if="$gate.isAdminOrTutor()">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row float-right">


                <div class="card-tools">

               <button class="btn btn-success btn-sm float-right m-2" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive ">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>G/ID</th>
                        <th>Group Name</th>

                        <th>Modify</th>
                  </tr>


                  <tr v-for="grade in grades.data" :key="grade.id">

                    <td>{{grade.id}}</td>

                    <td>{{grade.group_name}}</td>


                    <td>

                        <a href="#" @click="editModal(grade)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteGrading(grade.id)">
                            <i class="fa fa-trash red"></i>
                        </a>




                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="grades" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdminOrTutor()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->
<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update subject's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>

                </div>
       <form @submit.prevent="editmode ? updateGrading() : createGrading()">
      <div class="modal-body">
                 <div class="form-group">
                 <label>Grading Group Name</label>
                <input type="text" name="group_name" placeholder="Enter group_name" id="grade"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('group_name') }"
                   v-model="form.group_name" >
                <has-error :form="form" field="group_name"></has-error>
                 </div>





        </div>

            <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                    <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
           </div>
</form>
</div>
</div>
</div>

    <!-- Level Modal -->



    </div>
</template>

<script>
 import {mapState} from "vuex";
    export default {
        computed:mapState(['school']),

        data() {
            return {
                editmode: false,

                grades:{},
                  activate:false,
                  form: new Form({
                    id : '',
                    group_name:'',

                }),

            }
        },

        methods: {
            getResults(page = 1) {
                        axios.get('api/grading_group?page=' + page)
                            .then(response => {
                                this.grades = response.data;
                            });
                },
            updateGrading(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('/api/grading_group')
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
            editModal(school){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(school);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            createGrading(){
                 this.$Progress.start();
                this.form.post('api/grading_group')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Student Created in successfully'
                        })
                    this.$Progress.finish();
                     $('#addNew').modal('hide');
                      Fire.$emit('AfterCreate');
                })
                .catch(()=>{

                })

            },
            deleteGrading(id){
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
                                this.form.delete('api/grading_group/'+id).
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

         loadGrading(){

                if(this.$gate.isAdminOrTutor()){
                    axios.get("api/grading_group").then(res  => this.grades = res.data);
                }



        },

        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findGrade?q=' + query)
                .then((data) => {
                    this.grades = data.data
                })
                .catch(() => {

                })
            })
           this.loadGrading();

           Fire.$on('AfterCreate',() => {
               this.loadGrading();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
