
 <template>
    <div class="col-md-12" >


<student-assignment v-if="$gate.isStudent()"></student-assignment>

        <div class="row " v-if="$gate.isAdminOrTutor()">
          <div class=" col-md-12">
            <div >
              <div class="card-header mb-2">


                <div class="card-tittle">

               <button v-show="$gate.isAdminOrTutor()" class="btn btn-success float-right m-2" @click="newModal">Upload PDF <i class="fa fa-file-pdf" aria-hidden="true"></i></button>

                </div>

              </div>

           <div class="col-md-12"  >
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
                    <option value selected>Select Class Level</option>
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
     <div class="form-group">
     <h3 class="text-center">Paste Word Document OR Type</h3>
        <editor
    :options="editorOptions"
    height="500px"
    initialEditType="wysiwyg"
    previewStyle="vertical"
     ref="toastuiEditor"
     :initialValue="note"
     @change="getHtml"
  />

    </div>

<div class="card-footer">
    <button class="btn btn-success" @click="createAssignment">submit</button>
</div></div>


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
                         <th>Submission List</th>
                        <th>Action</th>
                  </tr>


                  <tr v-for="(assignment,index) in assignments.data" :key="index">

                    <td>{{index+1}}</td>
                    <td>{{assignment.comment}}</td>
                    <td>{{assignment.subject?assignment.subject.name:''}}</td>
                    <td>{{assignment.level?assignment.level.level_name :''}}</td>
                    <td>{{assignment.arms?assignment.arms.name :''}}</td>
                    <td>{{assignment.created_at | myDate}}</td>
                    <td> <router-link  :to="`/getstudents/${assignment.id}`" tag="a"  class="pr-2">view  list</router-link></td>
                    <td class="row">
                        <router-link v-if="assignment.is_pdf===0" :to="`/noteviewer/${assignment.id}`" tag="a"  class="pr-2">view/download</router-link>
                        <router-link v-if="assignment.is_pdf===1" :to="`/pdfviewer/${assignment.id}`" tag="a" >view /</router-link>
                       <a  href="#" v-if="assignment.is_pdf===1" @click="downloadFile(assignment.id)" class="pr-2" title="download">download</a>
                        <router-link v-show="$gate.isStudent()" :to="`/pdfviewer/${assignment.id}`" tag="a" class="pl-2">Do Assignment</router-link>

                        <a href="#" v-show="$gate.isAdminOrTutor()"  @click="deleteAssignment(assignment.id)" class="pl-2" title="delete">
                            <i class="fa fa-trash red"></i>

                        </a>


                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="assignments" @pagination-change-page="getAssignment"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdminOrTutorOrStudent()">
            <not-found></not-found>
        </div>

    <!-- Modal -->
            <div class="modal fade" id="addNewAssignment" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateAssignment() : createAssignment()" enctype="multipart/form-data">
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
                    <option value selected>Select Level</option>
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
   import { VueEditor } from 'vue2-quill-editor';
   import { Editor,plugin} from '@toast-ui/vue-editor';
   import 'tui-color-picker/dist/tui-color-picker.css';
   import colorSyntax from '@toast-ui/editor-plugin-color-syntax';
   import { Viewer } from '@toast-ui/vue-editor'
   export default {
        components: {
    VueEditor,
     editor: Editor,
     viewer:Viewer

  },
        data() {
            return {
                editmode: false,

                assignments : {},
                arms:{},
                levels:{},
                subjects:{},
                note:'',
                  editorText: '',

                file:'',
                form: new Form({
                id:'',
                level_id:'',
                arm_id:'',
                subject_id:'',
                comment:'',
                file_name:'',
                 isEditor:false,
                }),
             editorOptions : {
    minHeight: '500px',
    language: 'en_US',
    useCommandShortcut: true,
    useDefaultHTMLSanitizer: true,
    usageStatistics: true,
    hideModeSwitch: true,
    plugins: [colorSyntax],
    toolbarItems: [
        'heading',
        'bold',
        'italic',
        'color',
        'strike',
        'divider',
        'hr',
        'quote',
        'divider',
        'ul',
        'ol',
        'task',
        'indent',
        'outdent',
        'divider',
        'table',
        'image',
        'link',
        'divider',
        'code',
        'codeblock',
        'underline'

    ]
}
            }
        },
        mounted(){
              axios.get('/api/get_levels')
                .then(res=>{
                    this.levels=res.data;})

        },
        methods: {
            getAssignment(page = 1) {
                        axios.get('/api/getAssignment?page=' + page)
                            .then(response => {
                              console.log(this.assignment.data)
                                this.assignment = response.data;
                            });
                },
                     loadArms(){
       axios.get('/api/loadArms/'+this.form.level_id).then(res=>{
           this.arms=res.data
           console.log(this.arms)
       })},
            updateAssignment(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('/api/assignment/'+this.form.id)
                .then(() => {
                    // success
                    $('#addNewAssignment').modal('hide');
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
            editModal(assigment){
                this.editmode = true;
                this.form.reset();
                $('#addNewAssignment').modal('show');
                this.form.fill(user);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                this.isEditor=false;
                $('#addNewAssignment').modal('show');
            },
            deleteAssignment(id){
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
                                this.form.delete('/api/assignment/'+id).then(()=>{
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
            setEditor(){
  this.isEditor=true
            },
           loadSubjects(){

                if(this.$gate.isAdminOrTutor()){
                    axios.get("/api/load_list/"+this.form.level_id).then( res  => {
                      this.subjects = res.data;
                           console.log(this.subjects)
                          }
                      );
                }


            },
         getHtml() {
          let html = this.$refs.toastuiEditor.invoke('getHtml');
          this.note=html
          console.log(this.note)
          },
            createAssignment(){
               this.$Progress.start();
           const formData=new FormData();
           formData.append('file',this.file);
           formData.append('level_id',this.form.level_id);
           formData.append('subject_id',this.form.subject_id);
           formData.append('comment',this.form.comment);
           formData.append('arm_id',this.form.arm_id);
           formData.append('note',this.note);
           // console.log(formData);
           axios.post('/api/create_assignment',formData)
           .then(res=>{
                 $('#addNewAssignment').modal('hide');
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
            loadAssignment(){
                axios.get('/api/getAssignment')
                .then(res=>{ this.assignments=res.data})
            },

downloadFile(id) {
  axios.get('/api/assignment_view/'+id,{responseType: 'arraybuffer'})
    .then(response => {
      let blob = new Blob([response.data], { type: 'application/pdf' })
      let link = document.createElement('a')
      link.href = window.URL.createObjectURL(blob)
      link.download = 'assignment.pdf'
      link.click()
    })
},

        },







        created() {

            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('/api/findAssignment?q=' + query)
                .then((data) => {
                    this.assignments = data.data
                })
                .catch(() => {

                })
            })
           this.loadAssignment();
           Fire.$on('AfterCreate',() => {
               this.loadAssignment();
           });
           $('#addNewAssignment').modal('hide');
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
