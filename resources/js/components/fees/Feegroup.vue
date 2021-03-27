<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Fee </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Fees Group</li>
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

               <button v-show="$gate.isAdmin()" class="btn btn-success btn-sm float-right m-2" @click="newModal">Add New Fee <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="  table table-hover " id="data_tb">
                  <tbody>
                    <tr>
                        <th>R/ID</th>
                        <th>Fee Title</th>

                        <th ><div><span>Class/Level </span></div></th>
                          <th >Bank</th>
                        <th >Discount</th>
                      
                         <th >Due Date</th>
                        <th>Action</th>
                  </tr>


                  <tr v-for="report in reports.data" :key="report.id">

                    <td>{{report.id}}</td>
                    <td>{{report.tittle}}</td>
                    <td>{{report.levels?report.levels.level_name:''}} </td>
                     <td>{{report.paystacks?report.paystacks.bank:''}}</td>
                     <td>{{report.discount +'%'}}</td>
                     <td>{{report.due_date|myDate}}</td>
                     <td v-show="$gate.isAdminOrTutor()">
                         <router-link  :to="`fee_list/${report.id}`" title="view fee list" tag="a" exact><i class="fa fa-eye blue"></i></router-link>
                       <a href="#" @click="editModal(report)" v-show="$gate.isAdmin()" class="pl-2">
                            <i class="fa fa-edit blue"></i>
                        </a>

                        <a href="#" @click="deleteReport(report.id)" v-show="$gate.isAdmin()" class="pl-2">
                            <i class="fa fa-trash red"></i>
                        </a>
                         
                   
                         <router-link  :to="`/fee_description/${report.id}`" title="view fee list" tag="a" exact class="pl-2"> Fee details</router-link>
                     </td>

                     
                    <td class="row" v-show="$gate.isStudent()">
                        <router-link  :to="`fee_description/${report.id}`" title="view fee list" tag="a" exact class="pl-2"> Fee details</router-link>
                       
                       



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
       <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Fee Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateReport() : CreateReport()">
                <div class="modal-body">
                     <div class="form-group">

                        <input v-model="form.tittle" type="text" name="tittle"
                            placeholder="Fee Title eg JSS3-2020/2021 Second term Fee"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('title') }"/>
                        <has-error :form="form" field="tittle"></has-error>
                    </div>
                    
                       

                     <div class="form-group">

                     <select
                    name="level_id"
                    id="level_id"
                    class="form-control"
                    v-model="form.level_id"
                    @change="loadArms"
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

                    <div class="form-group">

                      <input v-model="form.due_date" type="date" name="tittle" 
                            placeholder="Due datee"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('title') }"/>
                        <has-error :form="form" field="tittle"></has-error>
                    </div>
                    <div class="form-group">

                      <input v-model="form.discount" type="number" name="discount" 
                            placeholder="discount"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('discount') }"/>
                        <has-error :form="form" field="discount"></has-error>
                    </div>
                    

                 <div class="form-group">
                     <select
                    name="paystack_key"
                    id="paystack_key"
                    :class="{'is-invalid':form.errors.has('paystack_key')}"
                    class="form-control"
                    v-model="form.paystack_key"
                  >
                    <option value selected>Select Bank</option>
                    <option
                      v-for="paystack in payStack"
                      :key="paystack.id"
                      :value="paystack.id"
                    >{{paystack.bank}}</option>
                  </select>
                        <has-error :form="form" field="term_id"></has-error>
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






<!-- DetailsModal -->

<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="example">Add new desscription</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <form @submit.prevent="createDescription()">
      <div class="modal-body">
   <div class="form-group">
                    <input class="form-control" type="text" placeholder="Enter Fee desscription"
                     :class="{'is-invalid':DetailsForm.errors.has('description')}"
                      v-model="DetailsForm.description"
                    >
                      <has-error :form="DetailsForm" field="description"></has-error>
               </div>
                <div class="form-group">
                    <input class="form-control" type="number" placeholder="Enter Amount"
                     :class="{'is-invalid':DetailsForm.errors.has('amount')}"
                      v-model="DetailsForm.amount"
                    >
                      <has-error :form="DetailsForm" field="amount"></has-error>
               
               
               </div>
                



        </div>

        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button  class="btn btn-primary">Save </button>
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
                sessions:{},
                terms:{},
                payStack:{},
                gradinggroup:'',
                arms:{},
                isNotActivated:true,
                isActivationKey:false,
                isStudent:window.user.student_id,
                reports:{},

                form: new Form({
                    id:'',
                    tittle:'',
                    level_id : '',
                    term_id: '',
                    session_id:'',
                    due_date:'',
                    paystack_key:'',
                    discount:''
                  


                }),

                DetailsForm:new Form({
                   description:'',
                   feegroup_id:'',
                   amount:''
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
                        axios.get('api/fees?page=' + page)
                            .then(response => {
                                this.reports = response.data;
                            });
                },
            updateReport(id){
                this.$Progress.start();

                this.form.put('/api/fees/'+id)
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
            editModal(group){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(group);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },

            CreateReport(){
                 this.$Progress.start();

                this.form.post('api/fees')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Student Created  successfully'
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
                                axios.delete('api/fees/'+id).
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
            loadFees(){

                if(this.$gate.isAdminOrTutorOrStudent()){
                    axios.get("api/fees").then( response  => {

                      this.reports = response.data
                       
                      });

                }
            },
             loadPaystack(){

                if(this.$gate.isAdmin()){
                    axios.get("/api/paystack").then(res  => this.payStack = res.data);
                }},
           AddDetails(id){
            $('#detailsModal').modal('show')
            this.DetailsForm.feegroup_id=id;
            
           },





        showActivationModal(id){
            $("#activationModal").modal('show')
                 this.activationForm.reset()
                  this. activationForm.report_id=id

        },
//         getActivated(){
//        this.activationForm.post('api/get_activated')
//        .then(res=>{
//            if(res.data.already_used){
//              swal.fire(
//                         'fail!',
//                         'This key has alreay been used.',
//                         'error'
//                         )

//            }
//        else if(res.data.invalid_key){
//          swal.fire(
//                         'Invalid!',
//                         'Invalid Key.',
//                         'warning'
//                         )
//        }
//            else if(res.data.success){
//                swal.fire(
//                         'success',
//                         'Result activated Successfully.',
//                         'success'
//                         )
//                         $("#activationModal").modal('hide')
//                         Fire.$emit('AfterCreate')
//            }
//        })
// .catch(err=>{
//       swal.fire(
//                         'fail!',
//                         'server errors',
//                         'failure'
//                         )


// })

//         },
loadArms(){
       axios.get('/api/loadArms/'+this.form.level_id).then(res=>{
           this.arms=res.data

       })},

createDescription(){
         this.$Progress.start();

                this.DetailsForm.post('api/fee_description')
                .then((res)=>{
                    $('#detailsModal').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Description created  successfully'
                        })
                    this.$Progress.finish();
                     this.$router.push('/fee_description/'+res.data.feegroup_id)
                })
                .catch(()=>{

                })

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
              this.loadPaystack();
           this.loadFees();
           Fire.$on('AfterCreate',() => {
               this.loadFees();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
