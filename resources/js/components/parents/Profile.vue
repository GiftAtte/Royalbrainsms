
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

        <div class="row col-12">

<div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-navy card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                      :src="`/img/profile/${form.users.photo}`"
                       alt="User profile picture">

                </div>

                <h3 class="profile-username text-center">{{form.name}}</h3>

                <p class="text-muted text-center">{{form.users.type}}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Occupation</b> <a class="float-right">{{form.occupation?form.occupation:''}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Portal ID</b> <a class="float-right">{{form.users.portal_id}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Phone</b> <a class="float-right">{{form.phoneNumber}}</a>
                  </li>
                </ul>

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
                        <li class="nav-item"><a class="nav-link " href="#security" data-toggle="tab">Security</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane" id="security">
                               <profile></profile>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <form class="form-horizontal">
 <input-field
                                v-model="form.name"
                                type="text"
                                label="Full Name"
                                :form="form"
                                field="name"
                                placeholder="Enter fullname"
                            />

                             <input-field
                                v-model="form.phoneNumber"
                                type="text"
                                label="Phone Numbers"
                                :form="form"
                                field="phoneNumber"
                                placeholder="Enter phone eg 0803333, 09013333"
                            />
                             <input-field
                                v-model="form.contactAddress"
                                type="text"
                                label="Contact Address"
                                :form="form"
                                field="contactAddress"
                                placeholder="Enter Contact Address"
                            />
                             <input-field
                                v-model="form.occupation"
                                type="text"
                                label="Sponsors's Occupation"
                                :form="form"
                                field="name"
                                placeholder="Sponsors's Occupation"
                            />
                                <div class="form-group row">
                                    <div class="col-sm-offset-2 col-sm-12">
                                    <button @click.prevent="updateInfo" type="submit" class="btn btn-danger">Update</button>
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
                   name:'',
                    phoneNumber:'',
                    contactAddress:'',
                    occupation:'',
                    school_id:'',
                    users:{
                        portal_id:'',
                        email:'',
                        photo:'',
                        type:''
                    }
                })
            }
        },
        mounted() {
          if(this.$route.params.id!='undefined'){
    axios.get(`/api/parent/${this.id}`)
            .then(({ data }) => {
               // console.log(data)
                this.form.fill(data);

                }).catch(err=>{});
          }else{
            axios.get(`/api/parent`)
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

                this.form.put('/api/parents/')
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
                .catch(() => {
                    this.$Progress.fail();
                });
            },

        },

        created() {


        // this.$route.params.id
        }
    }
</script>
