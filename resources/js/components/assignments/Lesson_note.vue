
 <template>
    <div class="col-12" >



        <div class="row " v-if="this.$gate.isAdminOrTutorOrStudent()">
          <div class="col-12">
            <div >
              <div class="card-body">


                <div class="card-tittle">

               <button v-show="$gate.isAdminOrTutor()" class="btn btn-success float-right m-2" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>

                </div>

              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive ">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>S/N</th>
                        <th>Comment</th>
                        <th>Subject</th>
                        <th>Level</th>
                        <th>Arm</th>
                        <th>Updated At</th>
                        <th>Action</th>
                  </tr>


                  <tr v-for="(assignment,index) in assignments.data" :key="index">

                    <td>{{index+1}}</td>
                    <td>{{assignment.topic}}</td>
                    <td>{{assignment.subject?assignment.subject.name:''}}</td>
                    <td>{{assignment.level?assignment.level.level_name :''}}</td>
                    <td>{{assignment.arms?assignment.arms.name :''}}</td>
                    <td>{{assignment.created_at | myDate}}</td>

                    <td class="row">
                       <a  href="#" @click="downloadFile(assignment.id)" class="pl-2"><i class="fa fa-download green" title="download"></i> </a>
                        /
                        <a href="#" @click="deleteLesson(assignment.id)" class="pl-2" v-show="$gate.isAdminOrTutor()" title="delete">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="assignments" @pagination-change-page="getResult"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdminOrTutorOrStudent()">
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
                <form @submit.prevent="editmode ? updateLesson() : createLesson()" enctype="multipart/form-data">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.comment" type="text" name="name" class="form-control" placeholder="comment">
                    </div>

                     <div class="form-group">

                     <select
                    name="level_id"
                    id="level_id"
                    class="form-control"
                    v-model="form.level_id"
                    @change="loadArms"
                  >
                    <option value selected>Class Level</option>
                    <option
                      v-for="level in levels"
                      :key="level.id"
                      :value="level.id"
                    >{{level.level_name}}</option>
                  </select>

                 </div>
                 <div class="form-group">

                     <select
                    name="level_id"
                    id="level_id"
                    class="form-control"
                    v-model="form.arm_id"
                    @change="loadSubjects"
                  >
                    <option value selected>Select Arm</option>
                    <option
                      v-for="arm in arms"
                      :key="arm.id"
                      :value="arm.arms.id"
                    >{{arm.arms.name}}</option>
                  </select>

                 </div>


                <div class="form-group">
                             <select
                    name="subject_id"
                    id="subject_id"
                    class="form-control"
                   v-model="form.subject_id"
                  >
                    <option value selected>Select subject to Add to list</option>
                    <option
                      v-for="(subject,index ) in subjects"
                      :key="index"
                      :value="subject.subjects?subject.subjects.id:subject.id"
                    >{{subject.subjects?subject.subjects.name:subject.name}}
                    </option>
                  </select>
                    </div>

           <div class=" form-group">
               <input type="file"  ref="file" @change="setFile" class=" form-control">

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

    </div>



</template>

<script>
    export default {

        data() {
            return {
                editmode: false,
                arms:{},
                assignments : {},
                levels:{},
                subjects:{},
                file:'',
                form: new Form({
                 id:'',
                level_id:'',
                arm_id:'',
                subject_id:'',
                topic:'',
                file_name:'',

                }),

            }
        },
        mounted(){
              axios.get('/api/get_levels')
                .then(res=>{
                    this.levels=res.data;})

        },
        methods: {
            getResult(page = 1) {
                        axios.get('/api/getLesson?page=' + page)
                            .then(response => {

                                this.assignments = response.data;
                                console.log(this.assignments.data.data)
                            });
                },
            updateLesson(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('/api/Lesson/'+this.form.id)
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
            editModal(lesson){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteLesson(id){
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
                                this.form.delete('/api/lesson/'+id).then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })
            },
            loadArms(){
       axios.get('/api/loadArms/'+this.form.level_id).then(res=>{
           this.arms=res.data
           console.log(this.arms)
       })},
           loadSubjects(){

                if(this.$gate.isAdminOrTutorOrStudent()){
                    axios.get("/api/load_list/"+this.form.level_id)
                    .then( res  => {
                      this.subjects = res.data;
                           console.log(this.subjects)
                          }
                      );
                }


            },

            createLesson(){
               this.$Progress.start();
           const formData=new FormData();
           formData.append('file',this.file);
           formData.append('level_id',this.form.level_id);
           formData.append('subject_id',this.form.subject_id);
           formData.append('topic',this.form.comment);
           formData.append('arm_id',this.form.arm_id);
           // console.log(formData);
           axios.post('/api/create_lesson',formData)
           .then(res=>{
                  $('#addNew').modal('hide');
               toast.fire({
                        type: 'success',
                        title: 'Assignment successfully uploaded'
                        })
                         console.log(res.data)
                    this.$Progress.finish();

                    Fire.$emit('AfterCreate');

           })
           .catch(err=>{
              toast.fire({
                        type: 'failure',
                        title: 'there was error uploading the file'+err
                        })
                         console.log(err)
           })


            },
           setFile () {
                this.file=this.$refs.file.files[0];
                console.log(this.file);
          },
            loadLesson(){
                axios.get('/api/getLesson')
                .then(res=>{ this.assignments=res.data
                  console.log(this.assignments);
                })
            },

downloadFile(id) {
  axios.get('/api/lesson/'+id,{responseType: 'arraybuffer'})
    .then(response => {
      let blob = new Blob([response.data], { type: 'application/pdf' })
      let link = document.createElement('a')
      link.href = window.URL.createObjectURL(blob)
      link.download = 'lesson_note.pdf'
      link.click()
    })
},

        },







        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('/api/findLesson?q=' + query)
                .then((data) => {
                    this.assignments = data.data
                })
                .catch(() => {

                })
            })
           this.loadLesson();
           Fire.$on('AfterCreate',() => {
               this.loadLesson();
           });
           $('#addNew').modal('hide');
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
