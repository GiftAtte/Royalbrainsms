<template>
 <div>
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Template List</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Template List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class="content" v-if="$gate.isAdmin()">
          <div class="col-12">
            <div class="card card-navy card-outline">
              <div class="card-header">
                <div class="row float-left">

                     <h4 class="text-uppercase">
                         {{ school.name }}
                     </h4>
                    </div>
                <div class="card-tools">

               <button class="btn btn-success btn-sm float-right m-2" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>

              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>S/ID</th>
                        <th>Template Name</th>
                        <th>Modify</th>
                  </tr>


                  <tr v-for="template in schoolTemplates" :key="template.id">

                    <td>{{template.id}}</td>
                    <td>{{template.name}}  </td>

                    <td>

                        <a href="#" @click="deleteTemplate(template.id)">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>

                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->

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

      <div class="modal-body">

                <div class="form-group">
                      <label> RESULTS TEMPLATE</label>
                  <multiselect
                        v-model="form.templateIds"
                        :items="templates"
                         item-key="id"
                         item-label="name"
                        title="Select template"
                         menu-position="float"

                            />

                 </div>



        </div>

            <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="!editmode"
                    type="button" @click="createTemplate"
                     class="btn btn-primary">Create</button>
           </div>

</div>
</div>
</div>

    <!-- Level Modal -->



    </div>
</template>

<script>
 import {mapState} from "vuex";
    export default {


        data() {
            return {
                editmode: false,
                school:'',
                schoolTemplates:'',
                templates:[],
                selected_file:'',
                  activate:false,
                  form: new Form({
                    id : '',
                    templateIds:[],
                    school_id:this.$route.params.id,


                }),

            }
        },

        methods: {

            updateTemplate(){
                 if(this.$gate.isAdmin()){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('/api/templates')
                .then(() => {
                    // success
                    $('#addNew').modal('hide');
                     swal.fire(
                        'Updated!',
                        'Session has been updated.',
                        'success'
                        )
                        this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            }},


            editModal(template){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(template);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            createTemplate(){
                 if(this.$gate.isAdmin()){
                 this.$Progress.start();
                  console.log(this.form.school_id)
                this.form.post('/api/schoolTemplates')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Template(s) added  successfully'
                        })
                    this.$Progress.finish();
                     $('#addNew').modal('hide');
                      Fire.$emit('AfterCreate');
                })
                .catch(()=>{

                })
                  }
            },

            deleteTemplate(id){

                  if(this.$gate.isAdmin()){
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
                                this.form.delete('/api/schoolTemplates/'+id).
                                then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'template has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })

            }
            },
         loadTemplate(){

                if(this.$gate.isAdmin()){
                    axios.get(`/api/templates`).then(res  => this.templates = res.data);
                }
        },
        loadSchoolTemplate(){
            axios.get(`/api/schoolTemplates/${this.form.school_id}`)
            .then(res=>{
                this.schoolTemplates=res.data.templates
                this.school=res.data.school
            }
                )
            .catch(err=>console.log(err))
        }

        },
        created() {

           this.loadTemplate();
           this.loadSchoolTemplate();

           Fire.$on('AfterCreate',() => {
              this.loadSchoolTemplate();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
