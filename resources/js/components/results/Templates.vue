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
                <div class="row float-right">


                <div class="card-tools">

               <button class="btn btn-success btn-sm float-right m-2" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>S/ID</th>
                        <th>Template Name</th>

                        <th>Date Created</th>
                        <th>Modify</th>
                  </tr>


                  <tr v-for="template in templates" :key="template.id">

                    <td>{{template.id}}</td>
                    <td>{{template.name|upText}}  </td>
                    <td>{{template.created_at|myDate}}</td>
                    <td>
                        <a href="#" @click="editModal(template)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
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
       <form @submit.prevent="editmode ? updateTemplate() : createTemplate()">
      <div class="modal-body">

                <div class="form-group">
                <input type="text" name="name" placeholder="Enter Name" id="name"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('name') }"
                   v-model="form.name" >
                <has-error :form="form" field="name"></has-error>
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


        data() {
            return {
                editmode: false,


                templates:{},
                selected_file:'',
                  activate:false,
                  form: new Form({
                    id : '',
                    name:'',


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

                this.form.post('api/templates')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Template Created in successfully'
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
                                this.form.delete('api/templates/'+id).
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
                    axios.get("api/templates").then(res  => this.templates = res.data);
                }



        }

        },
        created() {

           this.loadTemplate();

           Fire.$on('AfterCreate',() => {
               this.loadTemplate();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
