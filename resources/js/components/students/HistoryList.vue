<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Student History</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Student History List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class="content col-12" v-if="$gate.isAdminOrTutor()">

            <div class="card card-navy card-outline ">
              <div class="card-header">
                <div class="row float-right">
               <div class="card-title ">

              
                </div>

                <div class="card-tools">



                </div>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive ">
                <table class="table table-hover">
                  <tbody>
                    <tr> <th>Select All&nbsp;<input type="checkbox" @click="selectAll" v-model="allSelected" :checked="isSelectAll"></th>
                        <th>S/ID</th>
                        <th>Name</th>
                        <th>Phone </th>
                        <th>Class</th>
                        <th>Arm</th>
                        <th>Gender</th>

                  </tr>


                  <tr v-for="(student,index) in students.data" :key="student.id">
                    <td><input :id="`student${student.id}`" type="checkbox"  @click="select(student.id)" :checked="isChecked">
                    <td>{{index+1}}</td>
                    <td>{{student.students?student.students.surname:''}}, &nbsp;{{student.students?student.students.first_name:''}} </td>
                    <td>{{student.students?student.students.phone:''}}</td>
                    <td>{{student.levels?student.levels.level_name:''}}</td>
                     <td>{{student.arm?student.arm.name:''}}</td>
                    <td>{{student.students?student.students.gender:''}}</td>


                  </tr>
                </tbody></table>
 <button v-show="isStudentId" class="btn btn-md btn-danger" @click="deleteStudent"><i class="fa fa-trash"></i> Delete</button>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="students" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->

        </div>

        <div v-if="!$gate.isAdminOrTutor()">
            <not-found></not-found>
        </div>




    </div>
</template>

<script>
    export default {
        props: ['post-route'],
        computed:{
    school(){   return this.$store.state.school
                }
        },
        data() {
            return {
                editmode: false,
                students : {},
                levels:{},
                selected_file:'',
                isSelectAll:false,
                arms:{},
                hasArm:false,
                selected: [],
                allSelected: false,
                studentIds: [],
                isChecked:false,
                isStudentId:false,

                importFile:'api/importUser',
                form: new Form({
                    id:'',
                    arm_id:'',
                    surname : '',
                    first_name: '',
                    middle_name: '',
                    dob: '',
                    phone:'',
                    gender: '',
                    dob:'' ,
                    class_id:'',
                    school_id:window.user.school_id


                }),
              deleteForm:new Form({
                  studentIds:[]
              })


            }
        },
        mounted(){
             axios.get('api/arms')
               .then(res=>{
                   this.arms=res.data
                    // this.id=id;
               })
        },
        methods: {
            getResults(page = 1) {
                 axios.get(`/api/student_history/${this.$route.params.id}?page=${page}`)
                            .then(response => {
                                this.students = response.data;
                            });
                },
            updateStudent(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('/api/student_update/'+this.form.id)
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
            editModal(student){
                this.editmode = true;
               // Fire.$emit('AfterCreate');
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(student);
            },
            newModal(){
                this.editmode = false;
               // Fire.$emit('AfterCreate');
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteStudent(id){
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
                             this.deleteForm.studentIds=this.studentIds
                                this.deleteForm.delete('api/student_history').
                                then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'student has been deleted.',
                                        'success'
                                        )
                                        this.isStudentId=false

                                    Fire.$emit('AfterCreate');

                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })
            },
            loadLevels(){

                if(this.$gate.isAdminOrTutor()){
                    axios.get("api/get_levels").then(({ data }) => {
                        this.levels = data
                        console.log(this.levels);
                        });

                }
            },
            loadStudents(id){

                if(this.$gate.isAdminOrTutor()){
                    axios.get(`/api/student_history/${this.$route.params.id}`)
                    .then(({ data }) => (this.students = data));
                }
            },

            createStudent(){
                this.$Progress.start();

                this.form.post('api/student')
                .then((res)=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Student Created in successfully'
                        })
                        console.log(res)
                    this.$Progress.finish();

                })
                .catch((err)=>{
                 toast.fire({
                        type: 'fail',
                        title: err
                        })
                          this.$Progress.fail();
                })
            },
           selectFile () {
                this.selected_file=this.$refs.file.files[0];
           console.log(this.selected_file);
          },
            importStudents() {
                 this.$Progress.start();
           const formData=new FormData();
           formData.append('selected_file',this.selected_file);
            //console.log(formData.values);
           axios.post('api/importStudents',formData)
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
            checkArm(){
                var e = document.querySelector("#level");
                var id = e.options[e.selectedIndex].value;
                console.log(id)
              axios.get(`api/check_arm/${id}`).then(res=>{
                  if(res.data>0){
                    this.hasArm=true;
                    console.log(res.data)
                  }else{
                     this.hasArm=false;
                  }

              }).catch(err=>{
                  console.log(err)
              })
            },


         selectAll() {
            this.studentIds = [];
                 if(this.isChecked){

                    this.isChecked=false

                    return this.checkStudentId()
                 }
               const students=this.students.data
               this.isChecked=true
            if (!this.allSelected) {
                for (let index = 0; index <students.length; index++) {

                    this.studentIds.push(students[index].id);
                    this.checkStudentId()
                    this.allSelected=true
                }
                console.log(this.studentIds)
                this.checkStudentId()
            }
        },
        select(id) {
            if(this.allSelected){

            const index = this.studentIds.indexOf(id);
          if (index > -1) {
           this. studentIds.splice(index, 1);
           this.checkStudentId()
}
else{
                this.studentIds.push(id);
                this.checkStudentId()

            }


            }else{
                const index = this.studentIds.indexOf(id);
                if(index > -1){
                 this. studentIds.splice(index, 1);
                 this.checkStudentId()
                }
                else{
                   this.studentIds.push(id);
                   this.checkStudentId()
                }

            }

               this.checkStudentId()
         console.log(this.studentIds)
        },
        checkStudentId(){
            let studentLength=this.studentIds.length
            if(this.studentIds.length>0){
                      this.isStudentId=true

            }
            else{
                this.isStudentId=false

            }

             if(this.studentIds.length===this.students.length){
                          this.isSelectAll=true;
                     }else{
                         this.isSelectAll=false;
                     }
        },
         updatePassword(){
      swal.fire({
                    title: 'Are you sure?',
                    text: "You are generating new password for all the students !",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update !'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {

                                axios.post('api/update_password').
                                then(()=>{
                                        swal.fire(
                                        'Updated!',
                                        'passwords have been updated.',
                                        'success'
                                        )


                                    Fire.$emit('AfterCreate');

                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })
             },



        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findStudent?q=' + query)
                .then((data) => {
                    this.students = data.data
                })
                .catch(() => {

                })
            })
           this.loadStudents();
            this.loadLevels();

           Fire.$on('AfterCreate',() => {
               this.loadStudents();

           });
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
