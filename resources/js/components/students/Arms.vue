<template>
 <div>
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Classes/Arms</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item ">class List</li>
              <li class="breadcrumb-item active">Arms</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class="content col-12" v-if="$gate.isAdmin()">
          <div class="col-12">
            <div class="card card-navy card-outline">
              <div class="card-header">
                <h4>Level Arms</h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>S/N</th>
                        <th>Level Names</th>
                        <th>Class Teachers </th>
                        <th> Arms</th>
                        <th>Modify</th>
                  </tr>


                  <tr v-for="level in levels" :key="level.id">

                    <td>{{level.id}}</td>
                    <td>{{level.levels.level_name || ''}}  </td>


                    <td v-if="level.staff">{{level.staff.surname || ''}}&nbsp;{{level.staff.first_name || ''}} </td>
                    <td v-else >{{''}} </td>
                    <td v-if="level.arms.name ">{{level.arms.name }}</td>
                    <td v-else>{{'' }}</td>


                    <td class="row">

                        <a href="#" @click="editModal(level)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteArm(level.id)" class="pl-2">
                            <i class="fa fa-trash red"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
              </div>
              <h3 v-if="no_arm"></h3>
              <!-- /.card-body -->
              
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdminOrTutor()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->
<div class="modal fade" id="arms_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Arm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <form @submit.prevent="updateArm()">
      <div class="modal-body">

                <div class="form-group">
                     <select
                    name="arms_id"
                    id="arms_id"
                    :class="{'is-invalid':form.errors.has('arm_id')}"
                    class="form-control"
                    v-model="Arm_form.arms_id"
                  >
                    <option value selected>Select Arm</option>
                    <option
                      v-for="arm in arms"
                      :key="arm.id"
                      :value="arm.id"
                    >{{arm.name}}</option>
                  </select>
                        <has-error :form="Arm_form" field="arm_id"></has-error>
                 </div>

               <div class="form-group">
                     <select
                    name="staff_id"
                    id="staff_id"
                    :class="{'is-invalid':form.errors.has('staff_id')}"
                    class="form-control"
                    v-model="Arm_form.staff_id"
                  >
                    <option value selected>Select Class Teacher</option>
                    <option
                      v-for="staff in Staff"
                      :key="staff.id"
                      :value="staff.id"
                    >{{staff.name}}</option>
                  </select>
                        <has-error :form="Arm_form" field="staff_id"></has-error>
                 </div>




        </div>

        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button  class="btn btn-primary">Save changes</button>
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
                no_arm:false,
                id:this.$route.params.id,
                levels : {},
                Staff:{},
                arms:{},
                selected_file:'',
                importFile:'api/importUser',
                form: new Form({
                    id:'',
                    level_name : '',
                    staff_id: '',
                              arm:{
                            arms_id : '',
                    staff_id: '',
                    level_id:'',
                              }


                }),

                   Arm_form: new Form({
                    arms_id : '',
                    staff_id: '',
                    level_id:'',
                    school_id:'',



                }),

            }
        },
        mounted(){

       axios.get(`/api/arms/${this.id}`)
       .then( res  => {
                        this.levels=res.data
                      console.log(this.levels)
                            })
        .catch(err=>{console.log(err)});

            axios.get('/api/employees')
                .then(res=>{

                    this.Staff=res.data;})

// arma
axios.get('/api/arms')
                .then(res=>{
                    this.arms=res.data;})

                    },

        methods: {

            updateArm(){
                this.$Progress.start();
                // console.log('Editing data');
                this.Arm_form.put('/api/arms/'+this.form.id)
                .then(() => {
                    // success
                    $('#arms_form').modal('hide');
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
                $('#arms_form').modal('show');
                this.form.fill(level);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },


            deleteArm(id){
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
                                this.form.delete('/api/arms/'+id).
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

            loadArms(){
                axios.get(`/api/arms/${this.$route.params.id}`)
       .then( res  => {
                        this.levels=res.data
                        if(this.levels===null){
                          this.no_arm=true
                        }
                      $("#arms_form").modal('hide');
                            })
        .catch(err=>{console.log(err)});

              },
           selectFile () {
                this.selected_file=this.$refs.file.files[0];
           console.log(this.selected_file);
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
          // this.loadArms();
           Fire.$on('AfterCreate',() => {
             this.loadArms();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
