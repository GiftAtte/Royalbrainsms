<template>
<div class="card-body table-responsive col-md-12">
<table class="  table table-hover " id="data_tb">
                  <tbody>
                    <tr>
                        <th>R/ID</th>
                        <th>Title</th>

                        <th >Class/Level</th>

                        <th>Action</th>

                  </tr>


                  <tr v-for="report in reports" :key="report.id">

                    <td>{{report.id}}</td>
                    <td>{{report.title}}</td>
                    <td>{{report.levels?report.levels.level_name:''}} </td>
                    <td><a v-show="$gate.isSuperadmin()" disabled="true"   href="#" @click="download(report.id)" >downloads</a></td>

                    <td class="row" v-show="$gate.isStudentOrParent()">

                       <router-link v-if="report.is_ready>0"  class="btn btn-success btn-sm" v-show="report.activation_status" :to="`/result/${report.id}/${studentId}`" title="view report card" tag="a" exact><i class="fa fa-eye "></i> &nbsp;View Reult</router-link>
                       <button v-else disabled class="btn btn-danger btn-sm" v-show="report.activation_status" href="#`" title="Result not ready" ><i class="fa fa-eye "></i> &nbsp; Not ready </button>
                       <button  @click="showActivationModal(report.id)" v-show="!report.activation_status" class="btn btn-sm btn-danger" :title="report.is_ready?'enter activation code':'result not ready'"> Activation Key?</button>

                       <router-link v-if="report.term_id===3&&report.activation_status &&report.type==='terminal'"    :to="`/result/${report.id}/${isStudent}/annual`"  tag="a" exact class=" btn btn-primary ml-2  btn-sm">view Annual Report</router-link>


                    </td>
                  </tr>
                </tbody></table>




<div class="modal fade" id="activationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="example">Activate Result</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <form @submit.prevent="getActivated()">
      <div class="modal-body">

                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Enter Activation Key"
                     :class="{'is-invalid':activationForm.errors.has('activation_key')}"
                      v-model="activationForm.activation_key"
                    >
                      <has-error :form="activationForm" field="activation_key"></has-error>
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

</div>
</template>

<script>
export default {
   data(){
       return{
           isStudent:false,
           reports:'',
           studentId:this.$route.params.id,
           activationForm:new Form({
                   activation_key:'',
                   report_id:''
                })
       }

   },
   methods:{
   showActivationModal(id){
            $("#activationModal").modal('show')
                 this.activationForm.reset()
                  this. activationForm.report_id=id

        },
        getActivated(){
       this.activationForm.post('api/get_activated')
       .then(res=>{
           if(res.data.already_used){
             swal.fire(
                        'fail!',
                        'This key has alreay been used.',
                        'error'
                        )

           }
       else if(res.data.invalid_key){
         swal.fire(
                        'Invalid!',
                        'Invalid Key.',
                        'warning'
                        )
       }
           else if(res.data.success){
               swal.fire(
                        'success',
                        'Result activated Successfully.',
                        'success'
                        )
                        $("#activationModal").modal('hide')
                        Fire.$emit('AfterCreate')
           }
       })
.catch(err=>{
      swal.fire(
                        'fail!',
                        err,
                        'failure'
                        )


})

        },


  getReports(){
axios.get(`/api/siblings/results/${this.studentId}`)
.then(res=>this.reports=res.data)
.catch(err=>console.log(err))
  }
   },
created(){
this.getReports()
}

}
</script>

<style>

</style>
