<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Teachers Comment </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Teachers comment</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class="content" v-if="$gate.isTutorOrAdmin()">
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
               
                        <th>Comment</th>

                        <th>Modify</th>
                  </tr>


                  <tr v-for="comment in comments.data" :key="comment.id">

                    <td>{{comment.id}}</td>

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
                 <label>Comment</label>
                 <textarea v-model="form.comment"
                  placeholder="Enter comment" id="comment"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('comment') }"
                  >
                  Enter comment
                  </textarea>


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
                        axios.get('api/staff_comment?page=' + page)
                            .then(response => {
                                this.comments = response.data;
                            });
                },
            updateComment(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/staff_comment')
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
                this.form.post('api/staff_comment')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Comment Created  successfully'
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
                                this.form.delete('api/staff_comment/'+id).
                                then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'comment has been deleted.',
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

                if(this.$gate.isAdminOrTutor()){
                    axios.get("api/staff_comment").then(res  => this.comments = res.data);
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
