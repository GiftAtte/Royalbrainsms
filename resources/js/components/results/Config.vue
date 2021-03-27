<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Result Config</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Result  Config</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class="content" v-if="$gate.isAdminOrTutor()">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row ">


                <div class="card-tools">

                <h4 class="card-tittle">Config Table</h4>
                </div>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive ">
                <table class="table table-hover ">
                  <tbody>
                    <tr>
                        <th>S/ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr>


                  <tr v-for="(setting,index) in config" :key="index">
                      <td>{{index+1}}</td>
                    <td>{{setting.name}}</td>
                    <td v-if="setting.status" class="text-green text-capitalized ">{{'Active'|upText}} </td>
                   <td v-if="!setting.status" class="text-red text-capitalized ">Inactive</td>
                    <td >

                         <toggle-button
                        @change="setOption($event.value,setting.id)"
                         :label="true"
                         :labels="{checked: 'ON', unchecked: 'OFF'}"
                         :id="`setting${setting.id}`"
                         :value="setting.status"
                         :color="'green'"
                         :syn="true"

                         />


                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <button class="btn btn-primary" @click="setConfig">Save Settings</button> &nbsp; &nbsp; <button @click="resetSettings" class="btn btn-danger" >Reset</button>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdminOrTutor()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->


    <!-- Level Modal -->



    </div>
</template>

<script>
 import {mapState} from "vuex";
    export default {
        computed:mapState(['school']),

        data() {
            return {
                editmode: false,
               config:{},

                selected_file:'',
                  activate:false,
                  form: new Form({
                   settings:[]

                }),

            }
        },
       mounted(){
          axios.get('/api/result_config')
          .then((result) => {
             this.config=result.data
          }).catch((err) => {

          });
       },
        methods: {
           resetSettings(){
             this.settings=[]
             this.$router.push('/config')
              this.loadSettings()
           },

         loadSettings(){

                if(this.$gate.isAdminOrTutor()){
                axios.get('/api/result_config')
          .then((result) => {
             this.config=result.data
          }).catch((err) => {

          });
                }



        },
        setOption(status,id){
          let options={'status':status,'id':id}
          this.form.settings.push(options)
             console.log(this.form.settings)

        },
        setConfig(){
            this.$Progress.start()


          this.form.post('api/result_config')
        .then(()=>{
                toast.fire({
                        type: 'success',
                        title: 'Configuration Saved Successfully'
                        })
                    this.$Progress.finish();
                     Fire.$emit('AfterCreate')
        })
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

            this.loadSettings();
           Fire.$on('AfterCreate',() => {
               this.loadSettings();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>
