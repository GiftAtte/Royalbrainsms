<template>
    <div class="col-12" >

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

  <loading :active.sync="isLoading"
        :can-cancel="false"
        :on-cancel="onCancel"
        color="blue"
        :is-full-page="fullPage"></loading>
        <div class="row " v-if="this.$gate.isAdminOrTutorOrStudentOrParent()">
          <div class="col-12">
            <div >
              <div class="card card-navy card-outline">


                <div class="card-header">

               <button class="btn btn-success float-right m-2"
               v-show="$gate.isAdminOrTutor()"
                @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>


              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>S/N</th>
                        <th>Title</th>
                        <th>Uploaded By</th>
                        <th>Level</th>
                        <th>Created On</th>
                        <th>Action</th>
                  </tr>


                  <tr v-for="(video,index) in videos.data" :key="index">

                    <td>{{index+1}}</td>
                    <td>{{video.title}}</td>
                    <td>{{video.author.name}}</td>
                    <td>{{video.levels.level_name | upText}}</td>
                    <td>{{video.created_at | myDate}}</td>

                    <td class="row">
                        <router-link :to="`/watch_video/${video.id}`" class="btn bt-primary btn-sm">
                        <i class="fa fa-eye"></i> Watch</router-link>

                        /
                        <a href="#" @click="deleteVideo(video.id)" class="pl-2">
                            <i class="fa fa-trash red"></i>
                        </a>
                        <a href="#" @click="downloadVideo(video.id)" class="pl-2">
                            <i class="fa fa-download green"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="videos" @pagination-change-page="getVideos"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        </div>
        <div v-if="!$gate.isAdminOrTutorOrStudentOrParent()">
            <not-found></not-found>
        </div>

    <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Vido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateVideo() : createVideo()" enctype="multipart/form-data">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.title" type="text" name="title" class="form-control" placeholder="Title">
                    </div>

                     <div class="form-group">

                     <select
                    name="level_id"
                    id="level_id"
                    class="form-control"
                    v-model="form.level_id"

                  >
                    <option value selected>Class Level</option>
                    <option
                      v-for="level in levels"
                      :key="level.id"
                      :value="level.id"
                    >{{level.level_name}}</option>
                  </select>

                 </div>


           <div class=" form-group">
               <input type="text"  v-model="form.video_path" 
               class=" form-control"
              placeholder="youtube video link"
               >

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

    </div>



</template>

<script>
 import Loading from 'vue-loading-overlay';
    export default {
 components: { Loading },
        data() {
            return {
                editmode: false,

                videos : {},
                levels:{},
                subjects:{},
                   file:'',
                   isLoading:false,
                form: new Form({
                 id:'',
                level_id:'',
                title:'',
                video_path:''

                }),

            }
        },
        mounted(){
              axios.get('/api/get_levels')
                .then(res=>{
                    this.levels=res.data;})

        },
        methods: {
            getVideos(page = 1) {
                        axios.get('/api/videos?page=' + page)
                            .then(response => {

                                this.videos = response.data;
                                console.log(this.videos.data.data)
                            });
                },
            updateVideo(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('/api/videos')
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
            editModal(video){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(video);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteVideo(id){
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
                                this.form.delete('/api/videos/'+id).then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                                });
                         }
                    })
            },
           loadVideos(){

                if(this.$gate.isAdminOrTutorOrStudentOrStudent()){
                    axios.get("/api/videos").then( res  => {
                      this.videos = res.data;
                           console.log(this.videos)
                          }
                      );
                }


            },

            createVideo(){
               this.isLoading=true;
           const formData=new FormData();
           formData.append('file',this.file);
           formData.append('level_id',this.form.level_id);
           formData.append('title',this.form.title);
           formData.append('video_path',this.form.video_path)
            console.log(this.file);
          axios.post('/api/videos',formData)
           .then(res=>{
                  $('#addNew').modal('hide');
               toast.fire({
                        type: 'success',
                        title: 'Assignment successfully uploaded'
                        })
                         console.log(res.data)
                    this.$Progress.finish();
               this.isLoading=false;
                    Fire.$emit('AfterCreate');

           })
           .catch(err=>{
               this.$Progress.fail();
              toast.fire({
                        type: 'failure',
                        title: 'there was error uploading the file'+err
                        })
                         console.log(err)
           })


            },
           setFile () {
                this.file=this.$refs.file.files[0];
                console.log(this.file);
          },
            loadVideos(){
                axios.get('/api/videos')
                .then(res=>{ this.videos=res.data
                  console.log(this.videos);
                })
            },

downloadVideo(id) {
  axios.get('/api/videos/'+id,{responseType: 'arraybuffer'})
    .then(response => {
      let blob = new Blob([response.data], { type: 'application/mp4' })
      let link = document.createElement('a')
      link.href = window.URL.createObjectURL(blob)
      link.download = 'lesson_video.mp4'
      link.click()
    })
},

        },







        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('/api/findLesson?q=' + query)
                .then((data) => {
                    this.assignments = data.data
                })
                .catch(() => {

                })
            })
           this.loadVideos();
           Fire.$on('AfterCreate',() => {
               this.loadVideos();
           });
           $('#addNew').modal('hide');
        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>
