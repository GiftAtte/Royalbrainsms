<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Grading List</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                        <th>Grading Group</th>
                        <th>Score Range</th>
                        <th>Grade</th>
                        <th>Credit Point</th>
                        <th>Narration</th>
                        <th>Modify</th>
                  </tr>


                  <tr v-for="grade in grades.data" :key="grade.id">

                    <td>{{grade.id}}</td>
                    <td>{{grade.gradinggroups?grade.gradinggroups.group_name:''}}</td>
                    <td>{{grade.lower_bound}} - {{grade.upper_bound}} </td>
                    <td>{{grade.grade}}</td>
                    <td>{{grade.credit_point}}</td>
                    <td>{{grade.narration}}</td>

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

                     <select
                    name="group_id"
                    id="group_id"
                    class="form-control"
                    v-model="form.group_id"
                    :class="{ 'is-invalid': form.errors.has('group_id')}"

                  >
                    <option value selected>Select Grading Group</option>
                    <option
                      v-for="group in gradinggroup"
                      :key="group.id"
                      :value="group.id"
                    >{{group.group_name}}</option>
                  </select>
             <has-error :form="form" field="group_id"></has-error>
                 </div>
                <div class="form-group">
                 <label>Lower Bound</label>
                <input type="number" name="lower_bound" placeholder="Enter From" id="lower_bound" step="0.01"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('lower_bound') }"
                   v-model="form.lower_bound" >
                <has-error :form="form" field="lower_bound"></has-error>
                 </div>
                 <div class="form-group">
                  <label>Upper Bound</label>
                <input type="number" name="upper_bound" placeholder="Enter To" id="upper_bound" step="0.01"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('upper_bound') }"
                   v-model="form.upper_bound" >
                <has-error :form="form" field="upper_bound"></has-error>
                 </div>

                 <div class="form-group">
                 <label>Grade</label>
                <input type="text" name="grade" placeholder="Enter Grade" id="grade"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('grade') }"
                   v-model="form.grade" >
                <has-error :form="form" field="grade"></has-error>
                 </div>
                 <div class="form-group">
                  <label>Credit Point</label>
                <input type="number" name="credit_point" placeholder="Enter Credit Point" id="credit_point" step="0.01"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('credit_point') }"
                   v-model="form.credit_point" >
                <has-error :form="form" field="credit_point"></has-error>
                 </div>
                  <div class="form-group">
                  <label>Narration</label>
                <input type="text" name="narration" placeholder="Enter Narration" id="state"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('narration') }"
                   v-model="form.narration" >
                <has-error :form="form" field="narration"></has-error>
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
                gradinggroup:{},
                  activate:false,
                  form: new Form({
                    id : '',
                    lower_bound:'',
                    upper_bound:'',
                   credit_point:'',
                    grade:'',
                    group_id:'',
                    narration:'',


                }),

            }
        },

        methods: {
            getResults(page = 1) {
                        axios.get('api/grading?page=' + page)
                            .then(response => {
                                this.grades = response.data;
                            });
                },
            updateGrading(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/grading')
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
                this.form.post('api/grading')
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
                                this.form.delete('api/grading/'+id).
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
                    axios.get("api/grading").then(res  => this.grades = res.data);
                }



        },
        setSchool(id){
        //     this.$Progress.start()
        // this.$store.dispatch('loadSchool',[id])

          axios.put('api/set_school/'+id)
        .then(()=>{
                toast.fire({
                        type: 'success',
                        title: 'School switched successfully'
                        })
                    this.$Progress.finish();
                    this.$store.dispatch('loadSchool')
                      window.location.replace("/");
        })
        },
        getGradinggroup(){
                axios.get('api/gradinggroup')
                .then(res=>this.gradinggroup=res.data)
        }
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
           this.getGradinggroup();

           Fire.$on('AfterCreate',() => {
               this.loadGrading();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
