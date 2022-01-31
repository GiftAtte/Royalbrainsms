<template>
  <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Student Fee List</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Fee</a></li>
              <li class="breadcrumb-item active">Fee List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
   <div class="content col-12">
<div class="card card-navy card-outline ">
<form  @submit.prevent="createActivation" >
<div class="card-header">
<div class="ribbon-wrapper">
    <div class="ribbon bg-primary">
      Admin
      </div>
    </div>


</div>

<div class="card-body">



              <div class="card-header">
                <button class="btn btn-primary float-left pl-2" @click="printResults"><i class="fa fa-print"></i>Print List</button>
                </div>
<div v-show="isResults" class="card-body table-responsive" id="section-to-print"  ref="generatePDF">
<table class="table table-hover table-sm ">
<thead>
<tr>
<th>Invoice</th>
<th>Name</th>
<th>
 Amount
</th>
<th>
 Discount
</th>
<th>
 Paid
</th>
<th>
Balance
</th>
<th >wallet Balance</th>
<th colspan="3" class="text-center" >Make Payment/Status</th>


</tr>
</thead>
<tbody>
<tr v-for="(activation,index) in Activation_status" :key="index">
<td class="text-center">
  <router-link  :to="`/fee_description/${form.feegroup_id}/${activation.student_id}`"  tag="a" exact class="btn btn-sm btn-default"><i title="print invoice" class="fa fa-print"></i></router-link>

 </td>
<td >{{activation.name}}
 <input type="hidden" :id="`student_id${index}`" :value="activation.student_id">
 </td>
 <td>{{amount}}</td>
 <td>{{report.discount?(report.discount/100*amount):0.00}}({{report.discount}}%)</td>
 <td>{{activation.amount_paid}}</td>
 <td>{{round(amount-(activation.amount_paid+(report.discount/100*amount)))}}</td>
<td>{{activation.accountBalance?Number(activation.accountBalance).toFixed(2):'0.00'}}</td>
<td class="text-center">
  <router-link v-if="(amount-(activation.amount_paid+(report.discount/100*amount)))>0" :to="`/fee_description/${form.feegroup_id}/${activation.student_id}`"  tag="a" exact class="btn btn-sm btn-danger">pay with card</router-link>
 <a href="#"  v-else class="btn btn-success btn-sm" disabled>--fee cleared--</a>
 </td>
 <td class="text-center">
   <a v-if="(amount-(activation.amount_paid+(report.discount/100*amount)))>0" href="#" @click="newModal(activation.student_id,activation.name,amount-(activation.amount_paid+(report.discount/100*amount)))"  class="btn btn-danger btn-sm" disabled>pay manually</a>
   <a href="#"  v-else class="btn btn-success btn-sm" disabled="true">----fee Paid----</a>
   </td>

   <td class="text-center">
   <a v-if="(amount-(activation.amount_paid+(report.discount/100*amount)))>0" href="#" @click="billAccount(activation.accountNumber,bill.neovastId,bill.amount,bill.feegroup_id)"  class="btn btn-danger btn-sm" disabled>pay from wallet</a>
   <a href="#"  v-else class="btn btn-success btn-sm" disabled="true">----fee Paid----</a>
   </td>
</tr>
<b>SUMMARY</b>
<tr>
    <th colspan="3">EXPECTED INCOME</th>
    <th colspan="2">
        {{ expectedIncome }}
    </th>
    <th colspan="3">
       INCOME RECIEVED
    </th>
<th colspan="2">
    {{ incomeRecieved }}
    </th>
</tr>
</tbody>
</table>
<div class="card-footer action"><button class="btn btn-success">Activate Payment</button></div>
</div>
</div>


</form>

    </div></div>
    <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">{{name}}</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update subject's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>

                </div>
       <form @submit.prevent="editmode ? updateSession() : makePayment()">
      <div class="modal-body">

                <div class="form-group">
                  <label>Amount</label>
                <input type="text" name="name" placeholder="Enter Name" id="name"
                   class="form-control" :class="{ 'is-invalid': form.errors.has('name') }"
                   v-model="payForm.amount" >
                <has-error :form="form" field="name"></has-error>
                 </div>




        </div>

            <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                    <button v-show="!editmode" type="submit" class="btn btn-primary">Pay</button>
           </div>
</form>
</div>
</div>
</div>

    </div>
</template>

<script>
import Wallet from '../students/Wallet.vue';


    export default {
  components: { Wallet },


        data(){

            return{
                bill:'',
                 name:'',
                Activation_status:[],
                reports:{},
               isResults:false,
                isSelectAll:false,
                arms:{},
                selected: [],
                allSelected: false,
                studentIds: [],
                isChecked:false,
                isStudentId:false,
                amount:'',
                report:'',
                walletInfo:'',
                expectedIncome:0,
                incomeRecieved:0,
form:new Form({
    group_id:'',
     student_id:[],
     activation_status:[],
     number_of_students:''
}),
payForm:new Form({
    feegroup_id:'',
     student_id:'',
     activation_status:0,
     amount:'',
     fee:'',
    reference_id:'',
    transaction_id:'',
    message:'',
})


            }


        },
        mounted(){
            axios.get('/api/load_report')
            .then(result => {
               console.log(result.data);
                this.reports=result.data;
            }).catch((err) => {

            });
           // this.loadSubjects();
        },
        methods:{

            newModal(id,name,amount){
                this.editmode = false;
                this.payForm.amount=amount
                 this.payForm.student_id=id
                this.name='pay for '+name
                $('#addNew').modal('show');
            },

reference(){
        let text = "";
        let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( let i=0; i < 10; i++ )
          text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
      },
makePayment: function(response){

          this.payForm.reference_id=this.reference();
          this.payForm.transaction_id=this.reference();
          this.payForm.message='success';
          this.payForm.feegroup_id=this.$route.params.feegroup_id
          //this.PayForm.amount=response.amount
          // this.payForm.fee=this.discounted_amount
         // this.payForm.student_id=this.student_id

    console.log(response)
        this.payForm.post('/api/fee_pay')
                .then(()=>{
                    //$('#detailsModal').modal('hide')
$('#addNew').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Fee paid successfully'
                        })
                    this.$Progress.finish();
                      Fire.$emit('AfterCreate');
                })
                .catch(()=>{

                })


      },
           loadActivation(){
                this.$Progress.start();
          axios.get(`/api/student_fees/${this.$route.params.feegroup_id}`)
          .then((result) => {
             this.Activation_status=result.data.description;
             this.amount=result.data.amount
             this.report=result.data.report
             this.bill=result.data.bill
             this.walletInfo=result.data.walletInfo;
             this.isResults=true;
             this.expectedIncome=result.data.expectedIncome
             this.incomeRecieved=this.computeAmountPaid(this.Activation_status)
         for(let student of this.Activation_status){
             for(let wInfo of this.walletInfo){
                 if(student.id===wInfo.studentId){
                     JSON.parse(JSON.stringify(student))
                     student.accountBalance=wInfo.balance
                 }
             }
         }

            console.log('scdvdv',this.Activation_status)
             this.$Progress.finish();


          }).catch((err) => {
              this.$Progress.fail();
          });
           },

            createActivation(){


            this.form.student_id=[];
            this.form.activation_status=[];



            this.form.number_of_students=0;
         for (let index = 0; index < this.Activation_status.length; index++) {

           var student_id=document.querySelector(`#student_id${index}`).value
            this.form.student_id.push(student_id)



          if(document.querySelector(`#student${index}`).checked){
           this.form.activation_status.push(true);
          }else{
           this.form.activation_status.push(false);
          }


            this.form.number_of_students=++this.form.number_of_students;
         }
        this.$Progress.start();
          if(this.$gate.isAdmin()){

            this.form.post(`/api/student_fees`)

            .then(res=>{
             console.log(res.data)

              toast.fire({
                        type: 'success',
                        title: 'Result activation updated successfully'
                        })
             this.$Progress.finish();
             this.loadActivation()
             this.isResults=false
              }).catch(err=>{
                toast.fire({
                        type: 'danger',
                        title: 'there was error uploading the file'+err
                        })
              });


    }},

printResults(){
                  window.print();
                },

    //  loadArms(){
    //    axios.get('/api/getArms/'+this.form.report_id).then(res=>{
    //        this.arms=res.data
    //        console.log(this.arms)
    //    })},
round(num){
return Math.round((num + Number.EPSILON) * 100) / 100
},
         selectAll() {
            this.studentIds = [];
                 if(this.isChecked){

                    this.isChecked=false

                    return this.checkStudentId()
                 }
               const students=this.Activation_status
               this.isChecked=true
            if (!this.allSelected) {
                for (let index = 0; index <students.length; index++) {

                    this.studentIds.push(students[index].student_id);
                    this.checkStudentId()
                    this.allSelected=true
                }
             //   console.log(this.studentIds)
                this.checkStudentId()
            }
        },
        select(id) {
            if(this.allSelected){

            const index = this.studentIds.indexOf(id);
          if (index > -1) {
           this. studentIds.splice(index, 1);
           this.checkStudentId()
}
else{
                this.studentIds.push(id);
                this.checkStudentId()

            }


            }else{
                const index = this.studentIds.indexOf(id);
                if(index > -1){
                 this. studentIds.splice(index, 1);
                 this.checkStudentId()
                }
                else{
                   this.studentIds.push(id);
                   this.checkStudentId()
                }

            }

               this.checkStudentId()
         console.log(this.studentIds)
        },
        checkStudentId(){
            let studentLength=this.studentIds.length
            if(this.studentIds.length>0){
                      this.isStudentId=true

            }
            else{
                this.isStudentId=false

            }

             if(this.studentIds.length===this.Activation_status.length){
                          this.isSelectAll=true;
                     }else{
                         this.isSelectAll=false;
                     }
        },
        billAccount(accountNumber,billId,amount,feegroupId){
                   axios.post(`/api/billAccount/${accountNumber}/${billId}/${amount}/${feegroupId}`)
                   .then(res=>{
                       toast.fire({
                        type: 'success',
                        title: 'Bill paid successfully'
                        })
                        Fire.$emit('afterCreated')
                   })
        },
        computeAmountPaid(info){
            var sum=0;
            for(let item of info){
              sum +=item.amount_paid
            }
            return sum;
        }



    },
    created() {

          this.loadActivation();
this.form.feegroup_id=this.$route.params.feegroup_id
           Fire.$on('AfterCreate',() => {

               this.loadActivation();
              // $this.form.reset();
           });
        // //    setInterval(() => this.loadUsers(), 3000);

        }
    }

</script>
