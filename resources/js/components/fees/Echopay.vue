<template>
 <div>
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Echopay</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">echoPay</li>
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
                         <th>Bank</th>
                        <th>Key</th>

                        <th>Date Created</th>
                        <th>Modify</th>
                  </tr>


                  <tr v-for="pkey in payStack" :key="pkey.id">

                    <td>{{pkey.id}}</td>
                    <td>{{pkey.bank}}</td>
                    <td>{{pkey.paystack_key}}  </td>
                    <td>{{pkey.created_at|myDate}}</td>
                    <td>
                        <a href="#" @click="editModal(pkey)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deletePaystack(pkey.id)">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>

                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="sessions" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Key's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>

                </div>
       <form @submit.prevent="editmode ? updatePaystack() : createPaystack()">
      <div class="modal-body">
<form action="http://echopay.ng/eschool/prepayment" method="post">
<div class="row justify-content-center">
<input type="hidden" id="firstName" class="form-control" name="firstName" value="John" />
<input type="hidden" id="lastName" class="form-control" name="lastName" value="Doe" />
<input type="hidden" id="Amount" class="form-control" name="Amount" max="1000000" value="496" />
<input type="hidden" id="RegNo" class="form-control" name="RegNo" value="123ABC" />
<input type="hidden" id="Email" class="form-control" name="Email" value="info@eChoPay.com.ng" />
<input type="hidden" id="sess1" class="form-control" name="sess1" max="1000000" value="1" />
<input type="hidden" id="sess2" class="form-control" name="sess2" max="1000000" value="2" />
<input type="hidden" id="level" class="form-control" name="level" max="10" value="1" />
<input type="hidden" id="notification_url" class="form-control" name="notification_url" value="https://www.mywebsite/LogNotification.php" />
<input type="hidden" id="txnRef" class="form-control" name="txnRef" value="184048633031" />
<input type="hidden" id="SchoolId" class="form-control" name="SchoolId" value="xxxxxxx" />
<input type="hidden" id="FeeDescription" class="form-control" name="FeeDescription" value="AmissionAcceptance Fee" />
<input type="hidden" id="Faculty" class="form-control" name="Faculty" placeholder="Faculty" value="Arts" />
<input type="hidden" id="Department" class="form-control" name="Department" value="Law" />
<input type="hidden" id="redirect_url" class="form-control" name="redirect_url" value="https://www.mywebsite/redirect.php"/>
<input type="hidden" id="AccessKey" class="form-control" name="AccessKey" value="xxxxxxxxxxxxxxxxxxxxxxx" />
<p><button type="submit" class="btn">Proceed to Payment</button></p> </div>





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
                payStack:'',


                sessions:{},
                selected_file:'',
                  activate:false,
                  form: new Form({
                    id : '',
                    bank:'',
                    paystack_key:'',
                    school_id:''


                }),

            }
        },

        methods: {
            getResults(page = 1) {
                        axios.get('api/academic_session?page=' + page)
                            .then(response => {
                                this.paystck = response.data;
                            });
                },
            updatePaystack(){
                 if(this.$gate.isAdmin()){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('/api/paystack')
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


            editModal(paystck){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(paystck);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            createPaystack(){
                 if(this.$gate.isAdmin()){
                 this.$Progress.start();

                this.form.post('api/paystack')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Session Created in successfully'
                        })
                    this.$Progress.finish();
                     $('#addNew').modal('hide');
                      Fire.$emit('AfterCreate');
                })
                .catch(()=>{

                })
                  }
            },

            deletePaystack(id){

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
                                this.form.delete('/api/paystack/'+id).
                                then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'paystack has been deleted.',
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
         loadPaystack(){

                if(this.$gate.isAdmin()){
                    axios.get("/api/paystack").then(res  => this.payStack = res.data);
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
           this.loadPaystack();

           Fire.$on('AfterCreate',() => {
               this.loadPaystack();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
