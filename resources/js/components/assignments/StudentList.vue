
 <template>
    <div class="col-md-12" >
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Assignment</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Assignment</a></li>
              <li class="breadcrumb-item active">Student List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>


        <div class="row " v-if="$gate.isAdminOrTutor()">
          <div class=" col-md-12">
            <div >
              <div class="card-header mb-2">


                <div class="card-tittle">

 <a href="#" @click="newModal(form.id)" v-show="$gate.isAdminOrTutor()"   class="m-1 btn btn btn-md btn-success">
                        Use Scores
                        </a>

                </div>

              </div>

           <div class="col-md-12 row"  >
<div class="col-md-6">
    <div class="card-body table-responsive ">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>S/N</th>
                        <th>Names</th>
                        <th>Scores</th>
                        <th>Action</th>
                  </tr>


                  <tr v-for="(assignment,index) in assignments" :key="index">

                    <td>{{index+1}}</td>
                    <td>{{assignment.students.surname}},&nbsp;{{assignment.students.first_name}}&nbsp;{{assignment.students.middle_name}}&nbsp; </td>
                    <td>{{assignment.score?assignment.score +'%':''}}</td>

                    <td class="row">


                        <a v-show="!assignment.is_graded" href="#"  @click="getNote(assignment)" class="text-danger">Not Graded</a>/
                        <a v-if="assignment.is_pdf" href="#"  @click="downloadFile(assignment)" >Download</a>

                        <a href="#" v-show="assignment.is_graded"  @click="getNote(assignment)" class="pl-2" title="grade">
                            <i class="fa fa-check green"></i>

                        </a>


                    </td>
                  </tr>
                </tbody></table>
                <div class="card-footer">
                  <pagination :data="assignments" @pagination-change-page="getAssignment"></pagination>
              </div>
              </div>
</div>
<div class="col-md-6 card">
    <h4>{{title}}</h4>

         <div v-html="assignment_note" class="card-body">

         </div>
         <div class="card-body mt-5">
   <div class="form-group row">
       <label>Score</label>
       <input type="number" v-model="form.score" class="form-control" placeholder="score">
   </div>
   <div class="form-group row">
       <label>Comment</label>
       <input type="text" v-model="form.comment" class="form-control" placeholder="comment">
   </div>
   <div class="card-footer">
    <button v-show="!is_submited" class="btn btn-success float-right" @click="updateAssignment">submit</button>
</div>
    </div>
</div>
    </div>



              <!-- /.card-body -->

            </div>
            <!-- /.card -->
          </div>
        </div>
<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Use Assignment scores As</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update exam's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>

                </div>
       <form @submit.prevent="editmode ? updateExam() : useAssignment()">
      <div class="modal-body">



                     <div class="form-group ">
                         <label>select Report</label>
                      <select
                    name="report_id"
                    id="report_id"
                    :class="{'is-invalid':form.errors.has('report_id')}"
                    class="form-control"
                    v-model="form.report_id"


                  >
                    <option value="" selected>Select Report set</option>
                    <option
                      v-for="report in reports"
                      :key="report.id"
                      :value="report.id"
                    >{{report.title}}</option>
                  </select>
                                     <has-error :form="form" field="level_id"></has-error>
                                    </div>

<div class="form-group">

                                    <input type="number" v-model="form.assignment_value" class="form-control" id="title" placeholder=" A percentage of : eg  10, 15, 30" :class="{ 'is-invalid': form.errors.has('title') }">
                                     <has-error :form="form" field="cbt_value"></has-error>
                                    </div>
<div class="form-group ">
 <div class="icheck-success">
<input id="exam" type="radio"  v-model="form.cbt" value="exams" name="cbt" >
<label for="exam">
    Exam Score
</label>
</div>
</div>
<div class="form-group ">
 <div class="icheck-success">
<input id="ca1" type="radio"  v-model="form.cbt" value="ca1" name="cbt" >
<label for="ca1">
    CA1 Score
</label>
</div>
</div>
<div class="form-group ">
 <div class="icheck-success">
<input id="ca2" type="radio"  v-model="form.cbt" value="ca2" name="cbt" >
<label for="ca2">
    CA2 Score
</label>
</div>
</div>
<div class="form-group ">
 <div class="icheck-success">
<input id="ca3" type="radio"  v-model="form.cbt" value="ca3" name="cbt" >
<label for="ca3">
    CA3 Score
</label>
</div>
</div>



                               </div>

            <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                    <button v-show="!editmode" type="submit" class="btn btn-primary">Use</button>
           </div>
</form>
</div>
</div>
</div>

        <div v-if="!$gate.isAdminOrTutorOrStudent()">
            <not-found></not-found>
        </div>

    <!-- Modal -->

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
                reports:{},
                arms:{},
                levels:{},
                subjects:{},
                students:{},
                note:'Type or paste assignment',
                  editorText: '',
                  title:'Current Student',
                assignment_note:'Select Assignment to submit',
                file:'',
                isEditor:false,
                is_submited:false,

                form: new Form({
                id:this.$route.params.id,
                level_id:'',
                arm_id:'',
                note:'',
                score:'',
                comment:'',
                file_name:'',
                assignment_value:'',
                cbt:'',
                report_id:''

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


        },
        methods: {
            getAssignment(page = 1) {
                        axios.get(`/api/get_students_assignment/${this.$route.params.id}?page=${page}`)
                            .then(response => {

                                this.students = response.data.data;
                            });
                },
                     getNote(assignment){




                       this.assignment_note=assignment.note
                        if(assignment.is_pdf===1){
                         this.assignment_note="please download pdf"
                        }
                       this.title=assignment.students.surname +' '+assignment.students.first_name;
                       this.form.level_id=assignment.level_id
                       this.form.arm_id=assignment.arm_id
                       this.form.id=assignment.id
                       this.form.score=assignment.score
                       this.form.comment=assignment.comment






                        },
            updateAssignments(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('/api/update_students_assignment')
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
                this.form.fill(assignment);
            },
             newModal(id){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.id=id
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
                    axios.get("/api/load_list").then( res  => {
                      this.subjects = res.data;
                           console.log(this.subjects)
                          }
                      );
                }


            },
         getHtml() {
          let html = this.$refs.toastuiEditor.invoke('getHtml');
          this.note=html

          },
            updateAssignment(){
            this.$Progress.start();
          this.form.put('/api/update_students_assignment')
           .then(res=>{
               toast.fire({
                        type: 'success',
                        title: 'Assignment successfully uploaded'
                        })
                         console.log(res.data)
                    this.$Progress.finish();
                    this.assignment_note='Select Assignment to submit'
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
               axios.get(`/api/get_students_assignment/${this.$route.params.id}`)
                .then(res=>{
                    this.assignments=res.data.data;})
            },

downloadFile(assignment) {
    this.getNote(assignment);
  axios.get('/api/get_student_pdf/'+assignment.id,{responseType: 'arraybuffer'})
    .then(response => {
      let blob = new Blob([response.data], { type: 'application/pdf' })
      let link = document.createElement('a')
      link.href = window.URL.createObjectURL(blob)
      link.download = `${this.title} Assignment`
      link.click()
    })
},
loadReport(){

                if(this.$gate.isAdminOrTutor()){

                    axios.get('/api/load_report')
            .then(result => {
               console.log(result.data);
                this.reports=result.data;
            }).catch((err) => {

            });
                }
        },
        useAssignment(){
            if(this.$gate.isAdminOrTutor())
            {
               this.form.post('/api/use_assignment')

               .then(res=>{console.log(res.data)
               $('#addNew').modal('hide');



               toast.fire({
                        type: 'success',
                        title: 'Assignment used successefully'
                        })
               })

            }
        }
        },







        created() {
            this.loadReport();
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
