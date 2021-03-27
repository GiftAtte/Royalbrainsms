<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Class subject list</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Subject List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class="row " v-if="$gate.isAdminOrTutor()" >
          <div class="col-12">
            <div class="card card-navy card-outline col-12">
              <div class="card-header ">
                <div class="row col-12">

                <div v-show="$gate.isAdminOrTutor()" class="form-group col-10">
                             <select
                    name="subject_id"
                    id="subject_id"
                    :class="{'is-invalid':form.errors.has('level_id')}"
                    class="form-control"
                    v-model="form.level_id"
                   @change="loadSubjects_list"

                  >
                    <option value selected>Select Level</option>
                    <option
                      v-for="level in levels"
                      :key="level.id"
                      :value="level.id"
                    >{{level.level_name}}</option>
                  </select>
                        <has-error :form="form" field="level"></has-error>
                    </div>
                       <div class="card-tools float-right col-md-2">
                           <button class="btn btn-success btn-sm float-right m-2" @click="set_AddToList">Add Subjects  <i class="fas fa-user-plus fa-fw"></i></button></div>
                      <div class="form-group col-md-6" v-show="addToList">
                      <select
                      class="form-control"
                      v-model="form.type"
                      name="type">
                      <option value selected>Select Class Of Subject</option>
                      <option value=" None Academic">None Academic Subjects </option>
                      <option value="Academic"> Academic Subjects</option>
                      </select>
                 </div>
                <div class="form-group col-md-6" v-show="addToList">
                             <select
                    name="subject_id"
                    id="subject_id"
                    :class="{'is-invalid':form.errors.has('subject_id')}"
                    class="form-control"
                    v-model="form.subject_id"
                   @change="AddSubject"

                  >
                    <option value selected>Select subject to Add to list</option>
                    <option
                      v-for="subject in subjects"
                      :key="subject.id"
                      :value="subject.id"
                    >{{subject.name}}</option>
                  </select>
                        <has-error :form="form" field="subject"></has-error>
                    </div>

                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive ">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>S/ID</th>
                        <th>SUBJECT</th>
                        <th>CODE </th>
                        <th>TYPE </th>
                        <th>Modify</th>
                  </tr>


                  <tr  v-for="subject in subjects_list" :key="subject.id"  >

                    <td>{{subject.subject_id}}</td>
                    <td >{{subject.subjects?subject.subjects.name:''}}  </td>

                    <td >{{subject.subjects?subject.subjects.code:''}}</td>
                    <td >{{subject.type?subject.type:"Academic"}}  </td>

                    <td>

                        <a href="#" @click="deleteSubject_list(subject.id)" class="pl-2">
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
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update subject's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
       <form @submit.prevent="editmode ? updateSubject() : createSubject()">
      <div class="modal-body">

                <div class="form-group">
                    <input type="text" name="name" placeholder="Enter Name" id="name"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('name') }"
                   v-model="form.name" >
                <has-error :form="form" field="name"></has-error>
                 </div>

                 <div class="form-group">
                    <input type="text" name="code" placeholder="Enter Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('code') }"
                   v-model="form.code"
                    >
                <has-error :form="form" field="code"></has-error>
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
                addToList:false,
                subjects:{},
                levels:{},
                subjects_list:'',
                selected_file:'',
                counter:0,

                  form: new Form({
                      type:'',
                    id : '',
                    name:'',
                    code: '',
                    level_id:'',
                    subject_id:''

                }),

            }
        },
      mounted(){
        axios.get('/api/get_levels')
        .then(res=>{this.levels=res.data});

          this.loadSubjects_list();
      },
        methods: {

            updateSubject(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/subjects/'+this.form.id)
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
            editModal(subject){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(subject);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            set_AddToList(){
           this.addToList=true;
           Fire.$emit('AfterCreate');
            },
            AddSubject(){
                if(this.form.type===''){
                    alert("please select your subject tpye")
                }else{
                 this.$Progress.start();

                this.form.post('api/level_subjects')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'subject added successfully'
                        })
                    this.$Progress.finish();

                      Fire.$emit('AfterCreate');
                })
                .catch(()=>{

                })
                }
            },
            deleteSubject_list(id){
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
                                axios.delete('api/delete_list/'+id).
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
            loadSubjects(){

                if(this.$gate.isAdminOrTutor()){

                    axios.get("api/load_list").then( res  => {
                      this.subjects = res.data;
                           console.log(this.subjects)
                          }
                      );
                }


            },
             loadSubjects_list(){
               if(this.$gate.isAdmin()){
                 console.log(this.$gate.isAdmin());

                 axios.get(`api/load_subjects/`+this.form.level_id)
                 .then(res=>{
               this.subjects_list=res.data

               })
               }else{
           axio.get('api/load_subjects').then(res=>{

               this.subjects_list=res.data
 //console.log(res);
     })
     .catch(err=>{});
             }}
        },


        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findSubject?q=' + query)
                .then((data) => {
                    this.data = data.data
                })
                .catch(() => {

                })
            })
           this.loadSubjects();

           Fire.$on('AfterCreate',() => {
               this.loadSubjects();
             this.loadSubjects_list();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
