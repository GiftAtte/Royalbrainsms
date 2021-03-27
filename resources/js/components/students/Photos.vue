<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Bulk Image Upload</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Imgage</li>
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


                </div>
                 </div>
              </div>
               <form @submit.prevent="uploadImage" enctype="multipart/form-data">
              <!-- /.card-header -->
              <div class="card-body table-responsive ">
               <div class="form-group" >

                     <select
                    name="type"
                    id="type"
                    class="form-control"
                    v-model="image_type"
                    @change="setType"
                  >
                    <option value selected>Select Image Type</option>
                    <option value ="staff_signatures">Staff Signatures</option>
                    <option value="profile_pictures">Profile Pictures</option>
                  </select>
                 </div>
                <div class="form-group" v-show="isType">
                <label>Upload Zip Image file</label>
                <input type="file" name="photo" accept="image/*" id="name" ref="file" @change="setFile"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('photo') }"
                   >
                <has-error :form="form" field="name"></has-error>
                <span class="text-red">{{image_type}}</span>
                 </div>

              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <button class="btn btn-primary btn-md float-right m-2">Upload zip </button>
              </div>
              </form>
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
       <form @submit.prevent="editmode ? updateSession() : createSession()">
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
                 file:'',
                isType:false,
                sessions:{},
                selected_file:'',
                  activate:false,
                  image_type:'',
                  form: new Form({
                    id : '',
                    name:'',



                }),

            }
        },

        methods: {
            getResults(page = 1) {
                        axios.get('api/academic_session?page=' + page)
                            .then(response => {
                                this.subjects = response.data;
                            });
                },

        setFile () {
                this.file=this.$refs.file.files[0];
                console.log(this.file);
          },

           setType(){
             this.isType=true;
           },


            uploadImage(){
                 if(this.$gate.isAdmin()){
                 this.$Progress.start();
                     this.$Progress.start();
           const formData=new FormData();
           formData.append('file',this.file);
           formData.append('image_type',this.image_type)
                axios.post('api/upload_image',formData)
                .then((res)=>{

                    toast.fire({
                        type: 'success',
                        title: 'Image Uploaded  successfully'
                        })
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                   this.isType=false;
                })
                .catch((err)=>{
                 toast.fire({
                        type: 'failure',
                        title: err
                        })
                })
                  }
            },

            deleteSession(id){

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
                                this.form.delete('api/academic_session/'+id).
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

            }
            },
         loadSessions(){

                if(this.$gate.isAdmin()){
                    axios.get("api/academic_session").then(res  => this.sessions = res.data);
                }



        }

        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findSchool?q=' + query)
                .then((data) => {
                    this.levels = data.data
                })
                .catch(() => {

                })
            })
           this.loadSessions();

           Fire.$on('AfterCreate',() => {
               this.loadSessions();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
