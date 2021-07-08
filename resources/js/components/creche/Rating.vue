<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Ratinng Domain</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Rating List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class=" content  " v-if="$gate.isAdminOrTutor()">
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
              <div class="card-body table-responsive ">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>D/ID</th>
                        <th>Ratings</th>

                        <th>Date Created</th>
                        <th>Action</th>
                  </tr>


                  <tr v-for="domain in domains" :key="domain.id">

                    <td>{{domain.id}}</td>
                    <td>{{domain.rate|upText}}</td>

                    <td>{{domain.created|myDate}}</td>


                    <td class="row">

                        <a href="#" @click="editModal(domain)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteDomain(domain.id) " class="pl-2">
                            <i class="fa fa-trash red"></i>
                        </a>



                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">

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
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update exam's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>

                </div>
       <form @submit.prevent="editmode ? updateRating() : createRating()">
      <div class="modal-body">
               <div class="p-1">
                                <div class="form-group ">

                                    <input type="" v-model="form.rate" class="form-control" id="title" placeholder="title" :class="{ 'is-invalid': form.errors.has('name') }">
                                     <has-error :form="form" field="rate"></has-error>
                                    </div>




        </div></div>

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
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';
import VueTimepicker from 'vue2-timepicker/dist/VueTimepicker.umd.min.js'
 import {mapState} from "vuex";
    export default {
             components:{
             VueTimepicker,Datepicker
             },
        data() {
            return {
                editmode: false,


                domains:{},

                  form: new Form({
                      id:'',
                   rate:'',


                }),

            }
        },

        methods: {
            customFormatter(date) {
      return moment(date).format("YYYY-MM-DD");
    },

            updateRating(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/ratings')
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

            createRating(){
                 this.$Progress.start();
                this.form.post('/api/ratings')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Domain Created in successfully'
                        })
                    this.$Progress.finish();
                     $('#addNew').modal('hide');
                      Fire.$emit('AfterCreate');
                })
                .catch(()=>{

                })

            },

            deleteRating(id){
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
                                this.form.delete('api/ratings/'+id).
                                then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'domain has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })
            },

         loadRating(){

                if(this.$gate.isAdminOrTutor()){
                    axios.get("api/ratings").then(res  => this.domains = res.data);
                }



        },


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
           this.loadRating();

           Fire.$on('AfterCreate',() => {
              this.loadRating();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
