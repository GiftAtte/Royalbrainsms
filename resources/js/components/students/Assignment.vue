<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Report</h1>
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




        <div class="row mt-5" v-if="$gate.isAdminOrTutor()">
          <div class="col-md-12">
            <div class="card card-navy card-outline">
              <div class="card-header">
                <div class="row float-right">


                <div class="card-tools">

               <button class="btn btn-success btn-sm float-right m-2" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="  table table-hover ">
                  <tbody>
                    <tr>
                        <th>S/N</th>
                        <th>Title</th>

                        <th ><div><span>Class/Level </span></div></th>
                        <th>Modify</th>
                  </tr>


                  <tr v-for="report in reports.data" :key="report.id">

                    <td>{{report.id}}</td>
                    <td>{{report.title}}</td>
                    <td v-if="report.levels">{{report.levels.level_name|upText}} </td>

                    <td>
                     <router-link :to="`result_list/${report.id}`"  tag="a" exact><i class="fa fa-eye blue"></i></router-link>
                        <a href="#" @click="editModal(report)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteReport(report.id)">
                            <i class="fa fa-trash red"></i>
                        </a>/
                          <router-link :to="`result_activation/${report.id}`"  tag="a" exact><i class="fa fa-eye blue"></i> Activate Results</router-link>
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

        <div v-if="!$gate.isAdminOrTutor()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->
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
                            placeholder="Report Title"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('title') }"/>
                        <has-error :form="form" field="title"></has-error>
                    </div>
                    <div class="form-group">

                     <select
                    name="level_id"
                    id="level_id"
                    :class="{'is-invalid':form.errors.has('level_id')}"
                    class="form-control"
                    v-model="form.level_id"
                  >
                    <option value selected>Class Level</option>
                    <option
                      v-for="level in levels"
                      :key="level.id"
                      :value="level.id"
                    >{{level.level_name}}</option>
                  </select>
                        <has-error :form="form" field="level_id"></has-error>
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


                    <div class="form-group">
                    <label>Term Start</label>
                        <input v-model="form.term_start" type="date" name="term_start" id="term_start" placeholder="Term Start"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('term_start') }">
                        <has-error :form="form" field="term_start"></has-error>
                    </div>
                    <div class="form-group">
                    <label>Term End</label>
                        <input v-model="form.term_end" type="date" name="term_end" id="term_end" placeholder="Term end"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('term_end') }">
                        <has-error :form="form" field="term_end"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.school_days" type="number" name="school_days" id="school_days" placeholder="Number of School days(optional)"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('term_end') }">
                        <has-error :form="form" field="term_end"></has-error>
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
                levels : {},
                subjects:{},
                reports:{},
                form: new Form({
                    id:'',
                    comment:'',
                    level_id : '',
                    subject_id: '',




                }),



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
                                this.levels = response.data;
                            });
                },
            updateReport(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/report/'+this.form.id)
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

                if(this.$gate.isAdminOrTutor()){
                    axios.get("api/report").then( response  => {

                      this.reports = response.data
                       console.log(this.reports)
                      });

                }
            },




        },
        created() {
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
