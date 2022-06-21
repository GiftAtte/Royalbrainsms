<template>
<div class="row col-md-12" >
  
          <div class="col-md-3 col-xs-12 ">
            <!-- small box -->
            <div class="small-box bg-navy">
              <div class="inner">
                <h3>{{user_count}}</h3>

                <p>Active Users</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="/users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-md-3 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                 <h3>{{student_count}}</h3>

                <p>Active Students</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
             <router-link to="/students" class="small-box-footer" tag="a" exact>More info <i class="fas fa-arrow-circle-right"></i></router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-md-3 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                 <h3>{{staff_count}}</h3>

                <p>Active Staff</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <router-link to="/staff" class="small-box-footer" tag="a" exact>More info <i class="fas fa-arrow-circle-right"></i></router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-md-3 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{level_count}}</h3>

                <p>Levels/classes</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <router-link to="/levels" class="small-box-footer" tag="a" exact>More info <i class="fas fa-arrow-circle-right"></i></router-link>
            </div>
          </div></div>

</template>

<script>

 import Fullcalendar   from '@fullcalendar/vue';
 import DaygridPlugin   from '@fullcalendar/daygrid';
 import TimegridPlugin   from '@fullcalendar/timegrid';
 import InteractionPlugin   from '@fullcalendar/interaction';
import ListPlugin   from '@fullcalendar/list';
import {mapState}  from "vuex";
    export default {
     computed: {school(){
       return this.$store.state.school
     }},
      mounted() {


        },

     components:{Fullcalendar},
       data:()=>{
          return{
             editmode: false,
            CalenderPlugins:[
              DaygridPlugin,
              TimegridPlugin ,
              InteractionPlugin,
              ListPlugin,

            ],
            active_school:'',
            user_count:0,
             student_count:0,
              staff_count:0,
               level_count:0,

            events:'',
            indexToUpdate: "",
            form: new Form({
                    id:'',
                    event_name : '',
                    start_date: '',
                    end_date: '',
                    school_id: window.user.school_id

                })
          }
        },

       methods:{
          getCounts() {

                        axios.get('api/count/'+this.school.id)
                            .then(response => {
                             const {user_count, student_count, staff_count,level_count}=response.data;
                              this.user_count=user_count;
                               this.student_count=student_count;
                                this.staff_count=staff_count;
                                this.level_count=level_count;
                              })
                      .catch(err => console.log(err.response.data));
                },
                editModal(event){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(event);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            getEvent(){
              axios.get('api/event')
              .then(response=>{
                this.events=response.data;
                console.log(this.events)
                })
              .catch()
            },
showEvent(arg) {

                this.editmode = true;
                this.form.reset();
               $('#addNew').modal('show');
                const {id, title,start,end}=arg.event
                this.form.id=id;
                this.form.event_name=title;
                this.form.start_date=start;
                this.form.end_date=end;
                 console.log([id,title,start,end])


    },

         createEvent(){
                this.$Progress.start();

                this.form.post('api/event')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                       this.$Progress.finish();
                    toast.fire({
                        type: 'success',
                        title: 'Event Created in successfully'
                        })


                })
                .catch(()=>{

                })
            },
        updateEvent(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/event/'+this.form.id)
                .then(() => {
                    // success
                    $('#addNew').modal('hide');
                     swal.fire(
                        'Updated!',
                        'event has been updated.',
                        'success'
                        )
                        this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            deleteEvent(){
              $('#addNew').modal('hide');
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
                                axios.delete('api/event/'+this.form.id).then(()=>{
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
       },

      created(){
          if(this.$gate.isStudent()){
            this.$router.push('/reports')
          }
   this.getCounts();
         this.getEvent();
          Fire.$on('AfterCreate',() => {
               this.getEvent();
           });
        },
    }
</script>

<style>
.small-box{
    width:100%
}
</style>