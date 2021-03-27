
<template>
    <div >
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

        <div class="content col-md-12 row">

<div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-navy card-outline">
              <div class="card-body box-profile">
                <div class="text-center">



                
                  <img class="profile-user-img img-fluid img-circle"
                      :src="`/img/profile/${form.users.photo}`"
                       alt="User profile picture">

                </div>

                <h3 class="profile-username text-center ">{{form.surname}}&nbsp; {{form.first_name}}</h3>

                <p class="text-muted text-center">STUDENT</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Level</b> <a class="float-right">{{form.levels.level_name}} {{form.arm.name}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Gender</b> <a class="float-right">{{form.gender}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Portal ID</b> <a class="float-right">{{form.users.portal_id}}</a>
                  </li>
                </ul>

                <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->

            <!-- /.card -->
          </div>



            <!-- tab -->

            <div class="col-md-9">
                <div class="card card-navy card-outline">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active show" href=" #settings" data-toggle="tab">Profile</a></li>
                        <li class="nav-item"><a class="nav-link " href="#activity" data-toggle="tab">Security</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane" id="activity">
                               <profile></profile>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <form class="form-horizontal">

                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 control-label">Surname</label>

                                    <div class="col-sm-10">
                                    <input type="" v-model="form.surname" class="form-control" id="inputName" placeholder="Name" :class="{ 'is-invalid': form.errors.has('surnname') }">
                                     <has-error :form="form" field="surname"></has-error>
                                    </div>
                                </div>
                                    <div class="form-group row">
                                    <label for="inputFirstname" class="col-sm-2 control-label">Firstname</label>
                                    <div class="col-sm-10">
                                    <input type="text" v-model="form.first_name" class="form-control" name="firstname" placeholder="firstname"  :class="{ 'is-invalid': form.errors.has('first_name') }">
                                     <has-error :form="form" field="email"></has-error>
                                    </div>
                                    </div>

                                    <div class="form-group row">
                                    <label for="inputMiddle" class="col-sm-2 control-label">Middlename</label>
                                    <div class="col-sm-10">
                                    <input type="text" v-model="form.middle_name" class="form-control" name="middle_name" placeholder="Middlename"  :class="{ 'is-invalid': form.errors.has('middle_name') }">
                                     <has-error :form="form" field="middle_name"></has-error>
                                    </div>
                                    </div>
                                   <div class="form-group row">
                                    <label for="phone" class="col-sm-2 control-label">Phone</label>
                                    <div class="col-sm-10">
                                    <input type="text" v-model="form.phone" class="form-control" name="phone" placeholder="phone"  :class="{ 'is-invalid': form.errors.has('middle_name') }">
                                     <has-error :form="form" field="phone"></has-error>
                                    </div>
                                    </div>

                                    <div class="form-group row">
                                    <label for="phone" class="col-sm-2 control-label"> Address</label>
                                    <div class="col-sm-10">
                                    <input type="text" v-model="form.contact_adress" class="form-control" name="contact_address" placeholder="dresscontact ad"  :class="{ 'is-invalid': form.errors.has('middle_name') }">
                                     <has-error :form="form" field="contact_address"></has-error>
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                    <label for="phone" class="col-sm-2 control-label">LGA</label>
                                    <div class="col-sm-10">
                                    <input type="text" v-model="form.lga" class="form-control" name="lga" placeholder="LGA"  :class="{ 'is-invalid': form.errors.has('lga') }">
                                     <has-error :form="form" field="lga"></has-error>
                                    </div>
                                    </div>

                                   <div class="form-group row">
                                    <label for="phone" class="col-sm-2 control-label">State</label>
                                    <div class="col-sm-10">
                                    <input type="text" v-model="form.state" class="form-control" name="state" placeholder="state of origin"  :class="{ 'is-invalid': form.errors.has('middle_name') }">
                                     <has-error :form="form" field="state"></has-error>
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                    <label for="phone" class="col-sm-2 control-label">Nationality</label>
                                    <div class="col-sm-10">
                                    <input type="text" v-model="form.nationality" class="form-control" name="nationality" placeholder="nationality"  :class="{ 'is-invalid': form.errors.has('middle_name') }">
                                     <has-error :form="form" field="phone"></has-error>
                                    </div>
                                    </div>



                               <div class="form-group row">
                            <div class="offset-sm-2 col-sm-10">
                          <div class="checkbox-primary">
                            <label>
                              <input type="checkbox" disabled checked> I agree to the <a href="#">terms and conditions</a>
                            </label>
                          </div>
                              </div>
                             </div>
                                <div class="form-group row">
                                    <div class="col-sm-offset-2 col-sm-12">
                                    <button v-show="$gate.isAdminOrTutor()" @click.prevent="updateInfo" type="submit" class="btn btn-danger">Update</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
          </div>
          <!-- end tabs -->
        </div>
    </div>
</template>



<script>
    export default {
        data(){
            return {
                id: this.$route.params.id,
                 form: new Form({
                    id:'',
                    surname : '',
                    first_name: '',
                    middle_name: '',
                    dob: '',
                    phone:'',
                    gender: '',
                    class_id:'',
                    contact_adress:'',
                    state:'',
                    nationality:'',
                    lga:'',
                    blood_group:'',
                    roll_no:'',
                    arm_id:'',
                    photo:'',
                    levels : {
                        level_name:'',
                    },
                    arm : {
                        name:'',
                    },
                    users:{
                        portal_id:'',
                        email:'',
                        photo:''
                    }
                })
            }
        },
        mounted() {
          if(this.$route.params.id){
  axios.get("/api/profile/"+ this.id)
            .then(({ data }) => {
                console.log(data)
                this.form.fill(data);

                });
          }else{
            axios.get("/api/profile")
            .then(({ data }) => {
                console.log(data)
                this.form.fill(data);

                });
          }

        },

        methods:{

            getProfilePhoto(){

                let photo = (this.form.photo.length > 200) ? this.form.photo : "img/profile/"+ this.form.photo ;
                return photo;
            },

            updateInfo(){
                this.$Progress.start();
                if(this.id){
                this.form.put('/api/updateStudent/'+this.id)
                .then((res)=>{
                    console.log(res.data)
                    swal.fire({
                        type: 'success',
                        title: 'Update',
                        text: 'Info updated successfully ',
                    })
                     Fire.$emit('AfterCreate');
                    this.$Progress.finish();
                })

            }else{
 this.form.put('/api/updateStudent')
                .then((res)=>{
                    console.log(res.data)
                    swal.fire({
                        type: 'success',
                        title: 'Update',
                        text: 'Info updated successfully ',
                    })
                     Fire.$emit('AfterCreate');
                    this.$Progress.finish();
                })
            }
            }
        },

        created() {


        // this.$route.params.id
        }
    }
</script>
