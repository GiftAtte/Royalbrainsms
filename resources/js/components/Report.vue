<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Reports</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Report Group</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class="content" v-if="$gate.isAdminOrTutorOrStudent()">
          <div class="col-12">
            <div class="card card-navy card-outline">
              <div class="card-header">
                <div class="row float-right">


                <div class="card-tools">

               <button v-show="$gate.isAdmin()" class="btn btn-success btn-sm float-right m-2" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="  table table-hover " id="data_tb">
                  <tbody>
                    <tr>
                        <th>R/ID</th>
                        <th>Title</th>

                        <th >Class/Level</th>
                          <th v-show="$gate.isSuperadmin()" >Downloads</th>
                        <th>Action</th>
                        <th v-show="$gate.isAdminOrTutor()">Status</th>
                  </tr>


                  <tr v-for="report in reports.data" :key="report.id">

                    <td>{{report.id}}</td>
                    <td>{{report.title}}</td>
                    <td>{{report.levels?report.levels.level_name:''}} </td>
                    <td><a v-show="$gate.isSuperadmin()" disabled="true"   href="#" @click="download(report.id)" >downloads</a></td>
                     <td >
                         <router-link v-show="$gate.isAdminOrTutor()"  :to="`result_list/${report.id}`" title="view report list" tag="a" exact><i class="fa fa-eye blue"></i></router-link>
                       <a href="#" @click="editModal(report)" v-show="$gate.isAdmin()" class="pl-2">
                            <i class="fa fa-edit blue"></i>
                        </a>

                        <a href="#" @click="deleteReport(report.id)" v-show="$gate.isAdmin()" class="pl-2">
                            <i class="fa fa-trash red"></i>
                        </a>
                         <router-link v-show="$gate.isAdminOrTutor()"   :to="`master/${report.id}`" title="view report list" tag="a" exact>master sheet</router-link>

                     </td>

<td v-show="$gate.isAdmin()" >
    <button v-show="$gate.isAdminOrTutor()" type="btn" class="btn btn-success btn-sm mr-2" @click="loadArms(report.id)">Compute Summary</button>
<toggle-button @change="activateReport(report.id)"
v-show="$gate.isAdmin()"
                         :label="true"
                         :labels="{checked: 'ON', unchecked: 'OFF'}"

                         :height="20"
                         :font-size='14'
                         :value="report.is_ready"
                         :color="'green'"
                         :name="'activated'"
                         class="pl-2"
                         />


</td>
                    <td class="row" v-show="$gate.isStudent()">

                      <router-link v-if="report.is_ready>0"  class="btn btn-success btn-sm" v-show="report.activation_status" :to="`result/${report.id}/${isStudent}`" title="view report card" tag="a" exact><i class="fa fa-eye "></i> &nbsp;View Reult</router-link>
                      <button v-else disabled class="btn btn-danger btn-sm" v-show="report.activation_status" href="#`" title="Result not ready" ><i class="fa fa-eye "></i> &nbsp; Not ready </button>
                       <button  @click="showActivationModal(report.id)" v-show="!report.activation_status" class="btn btn-sm btn-danger" :title="report.is_ready?'enter activation code':'result not ready'"> Activation Key?</button>



                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="reports" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdminOrTutorOrStudent()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->
<loading :active.sync="isLoading"
        :can-cancel="false"
        :on-cancel="onCancel"
        color="blue"
        :is-full-page="fullPage"></loading>
       <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Report's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateReport() : CreateReport()">
                <div class="modal-body">
                     <div class="form-group">

                        <input v-model="form.title" type="text" name="title"
                            placeholder="Report Title eg 2018/2019-JSS1-FIRST_TERM"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('title') }"/>
                        <has-error :form="form" field="title"></has-error>
                    </div>
                     <div class="form-group">

                     <select
                    name="level_id"
                    id="level_id"
                    class="form-control"
                    v-model="form.level_id"

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
                    name="session_id"
                    id="session_id"
                    :class="{'is-invalid':form.errors.has('session_id')}"
                    class="form-control"
                    v-model="form.session_id"
                  >
                    <option value selected>Select session</option>
                    <option
                      v-for="session in sessions"
                      :key="session.id"
                      :value="session.id"
                    >{{session.name}}</option>
                  </select>
                        <has-error :form="form" field="session_id"></has-error>
                 </div>

                 <div class="form-group">
                     <select
                    name="term_id"
                    id="term_id"
                    :class="{'is-invalid':form.errors.has('term_id')}"
                    class="form-control"
                    v-model="form.term_id"
                  >
                    <option value selected>Select Term</option>
                    <option
                      v-for="term in terms"
                      :key="term.id"
                      :value="term.id"
                    >{{term.name}}</option>
                  </select>
                        <has-error :form="form" field="term_id"></has-error>
                 </div>

                 <div class="form-group" v-show="form.term_id===3">
                     <label>Results Format</label>
                     <select
                    name="isCummulative"
                    id="term_id"
                    :class="{'is-invalid':form.errors.has('isCummulative')}"
                    class="form-control"
                    v-model="form.isCummulative"
                  >
                    <option value selected>select report format</option>
                    <option

                      value="1"
                    >Use Cummulative</option>
                    <option

                      value="0"
                    >Use Termial Results</option>
                  </select>
                        <has-error :form="form" field="term_id"></has-error>
                 </div>



                    <div class="form-group">
                    <label>Next Term Begins:</label>
                        <input v-model="form.term_start" type="date" name="term_start" id="term_start" placeholder="Term Start"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('term_start') }">
                        <has-error :form="form" field="term_start"></has-error>
                    </div>
                    <div class="form-group">
                    <label>Report Type</label>
                        <select v-model="form.type"  name="type" id="term_end"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                        <option value="" selected>Select Report Type</option>
                        <option value="default-result">Default-Terminal</option>
                        <option value="default-midterm">Default Midterm</option>
                        <option value="creche">Default Creche Comment</option>
                        <option value="mid_term" selected>Mid-Term Report</option>
                        <option value="terminal">Madonna Termial</option>
                        <option value="mock">Mock Exam Report</option>
                        <option value="annual"> Madonna Annual Report</option>
                        <option value="primary-midterm">Madonna Primary Mid-Term</option>
                        <option value="primary-terminal"> Madonna Primary Terminal</option>


                        </select>

                        <has-error :form="form" field="term_end"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.school_days" type="number" name="school_days" id="school_days" placeholder="Number of School days(optional)"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('term_end') }">
                        <has-error :form="form" field="term_end"></has-error>
                    </div>
                     <div class="form-group">
                        <input v-model="form.header" type="text" name="school_days" id="header" placeholder="Result Header(optional)"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('header') }">
                        <has-error :form="form" field="header"></has-error>
                    </div>
                     <div class="form-group">

                     <select
                    name="gradinggroup_id"
                    id="gradinggroup_id"
                    class="form-control"
                    v-model="form.gradinggroup_id"
                    :class="{ 'is-invalid': form.errors.has('gradinggroup_id')}"

                  >
                    <option value selected>Select Grading Group</option>
                    <option
                      v-for="group in gradinggroup"
                      :key="group.id"
                      :value="group.id"
                    >{{group.group_name}}</option>
                  </select>
             <has-error :form="form" field="gradinggroup_id"></has-error>
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





<div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="example">computeSummary</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <form @submit.prevent="computeSummary">
      <div class="modal-body">


             <div class="form-group">

                     <select
                    name="level_id"
                    id="level_id"
                    class="form-control"
                    v-model="computeForm.arm_id"

                  >
                    <option value selected>Select Level</option>
                    <option
                      v-for="arm in arms"
                      :key="arm.arms.id"
                      :value="arm.arms.id"
                    >{{arm.arms.name}}</option>
                  </select>

                 </div>



        </div>

        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button  class="btn btn-primary">Compute</button>
      </div>
</form>
</div>
</div>
</div>




<div class="modal fade" id="activationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="example">Activate Result</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <form @submit.prevent="getActivated()">
      <div class="modal-body">

                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Enter Activation Key"
                     :class="{'is-invalid':activationForm.errors.has('activation_key')}"
                      v-model="activationForm.activation_key"
                    >
                      <has-error :form="activationForm" field="activation_key"></has-error>
                </div>



        </div>

        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button  class="btn btn-primary">Save changes</button>
      </div>
</form>
</div>
</div>
</div>


        </div>



</template>

<script>
  import Loading from 'vue-loading-overlay';
    export default {
         components: { Loading },
        data() {

            return {
                 isLoading:false,
                fullPage: true,
                editmode: false,
                levels : {},
                sessions:{},
                terms:{},
                gradinggroup:'',
                arms:{},
                isNotActivated:true,
                isActivationKey:false,
                isStudent:window.user.student_id,
                reports:{},
                arm_id:'',

                form: new Form({
                    id:'',
                    title:'',
                    level_id : '',
                    term_id: '',
                    session_id:'',
                    term_start:'',
                    type:'',
                    school_days:'',
                    arm_id:'',
                    next_term:'',
                    header:'',
                    gradinggroup_id:'',
                    isCummulative:0,
                    school_id:window.user.school_id


                }),

                computeForm:new Form({
                  arm_id:'',
                   report_id:''
                }),
                  activationForm:new Form({
                   activation_key:'',
                   report_id:''
                })

        }},
        mounted(){
      axios.get('api/get_levels')
                .then(res=>{
                    this.levels=res.data;})

                    axios.get('api/term')
                .then(res=>{
                    this.terms=res.data;})

                    axios.get('api/load_session')
                .then(res=>{
                    this.sessions=res.data;})

        },


        methods: {
            getResults(page = 1) {
                        axios.get('api/report?page=' + page)
                            .then(response => {
                                this.reports = response.data;
                            });
                },
            updateReport(id){
                this.$Progress.start();

                this.form.put('/api/report')
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
            editModal(level){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(level);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },


          activateReport(id){
              axios.post('/api/activate_report/'+id)
              .then(()=>{

                    toast.fire({
                        type: 'success',
                        title: 'Report activated successfully'
                        })
                      Fire.$emit('AfterCreate');
                })
                .catch(()=>{

                })

          },
          download(id){
          location.replace(`/download_page/${id}`)
          },
            CreateReport(){
                 this.$Progress.start();

                this.form.post('api/report')
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
            deleteReport(id){
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
                                this.form.delete('api/report/'+id).
                                then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'level has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })
            },
            loadReports(){

                if(this.$gate.isAdminOrTutorOrStudent()){
                    axios.get("api/report").then( response  => {

                      this.reports = response.data

                      });

                }
            },
        showActivationModal(id){
            $("#activationModal").modal('show')
                 this.activationForm.reset()
                  this. activationForm.report_id=id

        },
        getActivated(){
       this.activationForm.post('api/get_activated')
       .then(res=>{
           if(res.data.already_used){
             swal.fire(
                        'fail!',
                        'This key has alreay been used.',
                        'error'
                        )

           }
       else if(res.data.invalid_key){
         swal.fire(
                        'Invalid!',
                        'Invalid Key.',
                        'warning'
                        )
       }
           else if(res.data.success){
               swal.fire(
                        'success',
                        'Result activated Successfully.',
                        'success'
                        )
                        $("#activationModal").modal('hide')
                        Fire.$emit('AfterCreate')
           }
       })
.catch(err=>{
      swal.fire(
                        'fail!',
                        err,
                        'failure'
                        )


})

        },


        loadArms(id){
       axios.get('/api/getArms/'+id).then(res=>{
           this.arms=res.data
           this.computeForm.report_id=id;

           $('#summaryModal').modal('show');
              console.log(res.data)
       })},


      getGradinggroup(){
                axios.get('api/gradinggroup')
                .then(res=>this.gradinggroup=res.data)
        },
             computeSummary(){
                 this.setLoading();
axios.post('api/computeSummary/'+this.computeForm.report_id+'/'+this.computeForm.arm_id)
                .then(res=>{
           if(res.data.message==='success'){
                $('#summaryModal').modal('hide');
             swal.fire(
                        'success',
                        'Summary computed successfully',
                        'success'
                        )

          this.resetLoading()
         }

           else if(res.data.message==='no record'){
               swal.fire(
                        'error',
                        'No results found',
                        'error'
                        )
                        $("#activationModal").modal('hide')
                        Fire.$emit('AfterCreate')
                        this.resetLoading()
           }
       })
.catch(err=>{
      swal.fire(
                        'fail!',
                        'server errors',
                        'failure'
                        )

this.resetLoading()
})

     //  this.resetLoading()
             },

              setLoading(){
             this.isLoading=true
           },
           resetLoading(){
             this.isLoading=false
           }
        },
        created() {
             this.getGradinggroup();
         // console.log(window.user)
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findStudent?q=' + query)
                .then((data) => {
                    this.levels = data.data
                })
                .catch(() => {

                })
            })

           this.loadReports();
           Fire.$on('AfterCreate',() => {
               this.loadReports();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
