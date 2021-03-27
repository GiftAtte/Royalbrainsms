<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Learning Domain</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item active">Learning Domain</li>
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
              <div class="card-body table-responsive ">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>LD/ID</th>
                        <th>Domain</th>
                        <th>Type</th>
                        <th>Modify</th>
                  </tr>


                  <tr v-for="domain in domains.data" :key="domain.id">

                    <td>{{domain.id}}</td>

                    <td>{{domain.domain}}</td>
                    <td>{{domain.type}}</td>

                    <td>

                        <a href="#" @click="editModal(domain)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteLDomain(domain.id)">
                            <i class="fa fa-trash red"></i>
                        </a>




                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="domains" @pagination-change-page="getResults"></pagination>
              </div>
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
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update learning domain</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>

                </div>
       <form @submit.prevent="editmode ? updateLDomain() : createLDomain()">
      <div class="modal-body">

                <div class="form-group">
                 <label>Domain Type</label>
                <select name="type"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('type') }"
                   v-model="form.type" >
                   <option value selected> Select Learning Domain</option>
                   <option value="Behavioural"> Behavioural Domain</option>
                   <option value="Skill"> Skilful Domain</option>
                </select>
                <has-error :form="form" field="typ"></has-error>
                </div>


                 <div class="form-group">
                 <label>Learning Domain</label>
                <input type="text" name="domain" placeholder="attribute" id="domain"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('domain') }"
                   v-model="form.domain" >
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

                domains:{},
                  activate:false,
                  form: new Form({
                    id : '',
                    type:'',
                    domain:'',



                }),

            }
        },

        methods: {
            getResults(page = 1) {
                        axios.get('api/learning_domain?page=' + page)
                            .then(response => {
                                this.grades = response.data;
                            });
                },
            updateLDomain(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/learning_domain')
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
            editModal(domain){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(domain);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            createLDomain(){
                 this.$Progress.start();
                this.form.post('api/learning_domain')
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
            deleteLDomain(id){
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
                                this.form.delete('api/learning_domain/'+id).
                                then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'learning domain has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })
            },

         loadLDomain(){

                if(this.$gate.isAdminOrTutor()){
                    axios.get("api/learning_domain").then(res  => this.domains = res.data);
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
                axios.get('api/findGrade?q=' + query)
                .then((data) => {
                    this.grades = data.data
                })
                .catch(() => {

                })
            })
           this.loadLDomain();

           Fire.$on('AfterCreate',() => {
               this.loadLDomain();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
