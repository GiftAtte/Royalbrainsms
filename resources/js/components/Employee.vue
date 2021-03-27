<template>
<div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Staff</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">staff List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class="col-12 content" v-if="$gate.isAdminOrTutor()">

            <div class="card card-navy card-outline">
              <div class="card-header">
                <div class="row float-right">
               <div class="card-title ">

               <!-- <form @submit.prevent="importUsers" enctype="multipart/form-data"  >
                <input @change="selectFile" type="file" class="btn btn-xs " name="selected_file" ref="file" />
                <button  name="upload" class="btn btn-primary bt-sm float-right m-2" value="Upload">upload</button>
                </form> -->
                </div>

                <div class="card-tools">

               <button class="btn btn-success float-right m-2" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive ">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Employee No</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Phone </th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Modify</th>
                  </tr>


                  <tr v-for="employee in employees.data" :key="employee.id">

                    <td>{{employee.id}}</td>
                    <td>{{employee.surname|upText}}, &nbsp;{{employee.first_name|upText}} </td>
                    <td>{{employee.users.type?employee.users.type:''}}</td>
                    <td>{{employee.phone}}</td>
                    <td>{{employee.users.email?employee.users.email:''}}</td>
                    <td>{{employee.gender|upText}}</td>

                    <td class="row ">

                    <router-link :to="`staff_profile/${employee.id}`"  tag="a" exact><i class="fa fa-eye blue"></i></router-link>
                         /
                        <a href="#" @click="editModal(employee)" class="pl-2">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteEmployee(employee.id)" class="pl-2">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="employees" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->

        </div>

        <div v-if="!$gate.isAdminOrTutor()">
            <not-found></not-found>
        </div>

    <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateEmployee() : createEmployee()">
                <div class="modal-body">
                <div class="form-group">
                        <input v-model="form.surname" type="text" name="surname"
                            placeholder="Surname"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('surname') }">
                        <has-error :form="form" field="surname"></has-error>
                    </div>
                     <div class="form-group">
                        <input v-model="form.first_name" type="text" name="first_name"
                            placeholder=" First Name"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('first_name') }">
                        <has-error :form="form" field="first_name"></has-error>
                    </div>

                     <div class="form-group">
                        <input v-model="form.middle_name" type="text" name="middle_name"
                            placeholder="Middle Name (optional)"
                            class="form-control"/>

                    </div>

                    <div class="form-group">
                        <input v-model="form.phone" type="text" name="phone"
                            placeholder="Phone Number (optional)"
                            class="form-control"/>

                    </div>

                     <div class="form-group">
                        <input v-model="form.qualification" type="text" name="phone"
                            placeholder="Qualification (optional)"
                            class="form-control"/>

                    </div>

                        <div class="form-group">
                        <select name="gender" v-model="form.gender" id="gender" class="form-control" :class="{ 'is-invalid': form.errors.has('gender') }">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <has-error :form="form" field="gender"></has-error>
                    </div>

                       <div class="form-group">
                        <select name="type" v-model="form.type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('gender') }">
                            <option value="">Select Role</option>
                            <option value="admin">Administrator</option>
                            <option value="principal">Principal</option>
                            <option value="accountant">Accountant</option>
                            <option value="tutor">Form Tutor</option>
                            <option value="subjectTeacher">Subject Teacher</option>
                            <option v-show="$gate.isSuperadmin()" value="superadmin">Superadmin</option>
                            <option v-show="$gate.isSuperadmin()" value="manager">Manager</option>
                        </select>
                        <has-error :form="form" field="type"></has-error>
                    </div>


                     <div> <router-link @click="closeModal" v-show="editmode" :to="`/users`"  tag="a" exact>Edit email or password</router-link></div>


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

    </div>
</template>

<script>
    export default {
        props: ['post-route'],
        data() {
            return {
                editmode: false,
                employees : {},
                levels:{},
                selected_file:'',
                importFile:'api/importUser',
                form: new Form({
                    id:'',
                    surname : '',
                    first_name: '',
                    middle_name: '',
                    phone:'',
                    gender: '',
                    qualification:'',
                    type:'',



                }),



            }
        },
        methods: {
            getResults(page = 1) {
                        axios.get('api/employee?page=' + page)
                            .then(response => {
                                this.employees = response.data;
                            });
                },
            updateEmployee(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/employee')
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
            editModal(employee){
                this.editmode = true;
               // Fire.$emit('AfterCreate');
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(employee);
            },
            newModal(){
                this.editmode = false;
               // Fire.$emit('AfterCreate');
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteEmployee(id){
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
                                axios.delete(`api/delete_emp/${id}`).
                                then(res=>{
                                        swal.fire(
                                        'Deleted!',
                                        'staff has been deleted.',
                                        'success'
                                        )
                                        console.log(res)
                                    Fire.$emit('AfterCreate');
                                }).catch(err=> {

                                    swal.fire("Failed!", "There was something wronge.", "warning" +err);
                                });
                         }
                    })
            },
            loadEmployees(){

                if(this.$gate.isAdminOrTutor()){
                    axios.get("api/employee").then(({ data }) => {
                        this.employees = data
                        console.log(this.employees);
                        });

                }
            },


            createEmployee(){
                this.$Progress.start();

                this.form.post('api/employee')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Student Created in successfully'
                        })
                    this.$Progress.finish();

                })
                .catch(()=>{

                })
            },
           selectFile () {
                this.selected_file=this.$refs.file.files[0];
           console.log(this.selected_file);
          },
            importEmployees() {
                 this.$Progress.start();
           const formData=new FormData();
           formData.append('selected_file',this.selected_file);
            //console.log(formData.values);
           axios.post('api/importEmployees',formData)
           .then(res=>{
               toast.fire({
                        type: 'success',
                        title: 'Students successfully imported'
                        })
                         console.log(res.data)
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');

           })
           .catch(err=>{
              toast.fire({
                        type: 'danger',
                        title: 'there was error uploading the file'+err
                        })
                         console.log(err)
           })


            },
            closeModal(){
                 $('#addNew').modal('hide');
            }
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findEmployee?q=' + query)
                .then((data) => {
                    this.employees = data.data
                })
                .catch(() => {

                })
            })
           this.loadEmployees();

           Fire.$on('AfterCreate',() => {
               this.loadEmployees();

           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
