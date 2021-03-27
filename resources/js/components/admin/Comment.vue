<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Comment List</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Comment List</li>
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

               <button class="btn btn-success btn-sm float-right m-2" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>C/ID</th>
                        <th>Average Range</th>
                        <th>Comment</th>

                        <th>Modify</th>
                  </tr>


                  <tr v-for="comment in comments.data" :key="comment.id">

                    <td>{{comment.id}}</td>
                    <td>{{comment.lower_bound}} - {{comment.upper_bound}} </td>
                    <td>{{comment.comment}}</td>


                    <td>

                        <a href="#" @click="editModal(comment)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteComment(comment.id)">
                            <i class="fa fa-trash red"></i>
                        </a>




                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="comments" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->
<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Comment's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>

                </div>
       <form @submit.prevent="editmode ? updateComment() : createComment()">
      <div class="modal-body">

                <div class="form-group">
                 <label>Lower Bound</label>
                <input type="number" name="lower_bound" placeholder="Enter From" id="lower_bound" step="0.01"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('lower_bound') }"
                   v-model="form.lower_bound" >
                <has-error :form="form" field="lower_bound"></has-error>
                 </div>
                 <div class="form-group">
                  <label>Upper Bound</label>
                <input type="number" name="upper_bound" placeholder="Enter To" id="upper_bound" step="0.01"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('upper_bound') }"
                   v-model="form.upper_bound" >
                <has-error :form="form" field="upper_bound"></has-error>
                 </div>

                 <div class="form-group">
                 <label>Comment</label>
                <input type="text" name="comment" placeholder="Enter comment" id="comment"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('comment') }"
                   v-model="form.comment" >
                <has-error :form="form" field="grade"></has-error>
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

    export default {


        data() {
            return {
                editmode: false,

                grades:{},
                comments:{},
                  activate:false,
                  form: new Form({
                    id : '',
                    lower_bound:'',
                    upper_bound:'',
                  comment:''


                }),

            }
        },

        methods: {
            getResults(page = 1) {
                        axios.get('api/comment?page=' + page)
                            .then(response => {
                                this.comments = response.data;
                            });
                },
            updateComment(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/comment')
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
            editModal(comment){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(comment);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            createComment(){
                 this.$Progress.start();
                this.form.post('api/comment')
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
            deleteComment(id){
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
                                this.form.delete('api/comment/'+id).
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
            },

         loadComment(){

                if(this.$gate.isAdmin()){
                    axios.get("api/comment").then(res  => this.comments = res.data);
                }



        },

        isActive(id){
            if(id===window.user.school_id)
            {
                console.log(id)
                return true
            }else{
                console.log(id)
                return false
            }
        }
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findComment?q=' + query)
                .then((data) => {
                    this.grades = data.data
                })
                .catch(() => {

                })
            })
           this.loadComment();

           Fire.$on('AfterCreate',() => {
               this.loadComment();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
