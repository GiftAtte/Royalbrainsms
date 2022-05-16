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
<form  @submit.prevent="createActivation">
<div class="card-header">
<div class="ribbon-wrapper">
    <div class="ribbon bg-primary">
      Admin
      </div>
    </div>
</div>

             <div class="card-body">
              <div class="card-header">
                           <export-excel
                                class="btn btn-primary ml-2"
                                :data="downloadData"
                            >
                                 CSV
                                <i class="fa fa-download"></i>
                            </export-excel>



                <button class="btn btn-success float-right" type="button" @click="confirmPayments">
                  Confirm Complete Payments
                 </button>

                </div>
                <h1 class="text-center text-primary" v-if="!report">loading.........</h1>
<div v-show="isResults" class="card-body table-responsive" id="section-to-print"  ref="generatePDF">
 <h3>{{report.tittle}}</h3>
<table class="table table-hover table-sm">
<thead>
<tr>
<th>S/N</th>
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
<th colspan="2" class="text-center" >Payment/Status</th>

<th>Invoice</th>
</tr>
</thead>
<tbody>
<tr v-for="(activation,index) in Activation_status" :key="index">
<td>{{ index+1 }}
<input :id="activation.id"
:value="activation.student_id"
 type="checkbox"
v-model="form.studentIds"
>
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
   <a v-if="(amount-(activation.amount_paid+(report.discount/100*amount)))>0" href="#" @click="newModal(activation.student_id,activation.name,amount-(activation.amount_paid+(report.discount/100*amount)))"  class="btn btn-danger btn-sm" disabled>pay manually</a>
   <a href="#"  v-else class="btn btn-success btn-sm" disabled="true">----fee Paid----</a>
   </td>

   <td class="text-center">
   <a v-if="(amount-(activation.amount_paid+(report.discount/100*amount)))>0" href="#" @click="billAccount(activation.accountNumber,bill.neovastId,bill.amount,bill.feegroup_id)"  class="btn btn-danger btn-sm" disabled>pay from wallet</a>
   <a href="#"  v-else class="btn btn-success btn-sm" disabled="true">----fee Paid----</a>
   </td>
   <td class="text-center">
        <a href="#" @click="editeModal(activation.student_id,activation.name)">
                            <i class="fa fa-edit green"></i>
                        </a>
        <button class="btn"  @click="printInvoice(activation)">
        <i title="print invoice" class="fa fa-print"></i>
        </button>

  <!-- <router-link  :to="`/fee_description/${form.feegroup_id}/${activation.student_id}`"  tag="a" exact class="btn btn-sm btn-default"><i title="print invoice" class="fa fa-print"></i>
  </router-link> -->

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
<div class="card-footer action">

        </div>
</div>
</div>


</form>

    </div></div>
    <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">{{name}}</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>

                </div>
       <form @submit.prevent="editmode ? updatePayment() : makePayment()">
      <div class="modal-body">
             <div class="form-group" v-show="editmode">
                  <label>Update Type</label>
                <select class="form-control"
                v-model="payForm.updateType"
                name="updateType"
                >
                <option value="" selected> SELECT UPDATE TYPE</option>
                <option value="DEBIT"> DEBIT</option>
                <option value="CREDIT"> CREDIT</option>

                </select>
                 </div>


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
import { mapState } from "vuex";
import  invoiceInfo from '../../Invoice';
import moment from 'moment';

    export default {
  components: { Wallet },

  computed:mapState(['school']),
        data(){

            return{
                bill:'',
                 name:'',
                Activation_status:[],
                downloadData:[],
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
                editmode:false,

form:new Form({
      group_id:this.$route.params.feegroup_id,
      studentIds:[],
      isUpdate:false


}),
payForm:new Form({
    feegroup_id:this.$route.params.feegroup_id,
     student_id:'',
     activation_status:0,
     amount:'',
     fee:'',
    reference_id:'',
    transaction_id:'',
    message:'',
     updateType:'',
     isUpdate:false,
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
            editeModal(id,name){
                this.editmode = true;
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
          this.setCSVDownload()
           // console.log('scdvdv',this.Activation_status)
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

        billAccount(accountNumber,billId,amount,feegroupId){
                   axios.post(`/api/billAccount/${accountNumber}/${billId}/${amount}/${feegroupId}`)
                   .then(res=>{
                       toast.fire({
                        type: 'success',
                        title: 'Bill paid successfully'
                        })
                        Fire.$emit('AfterCreate')
                   })
        },
        computeAmountPaid(info){
            var sum=0;
            for(let item of info){
              sum +=item.amount_paid
            }
            return sum;
        },
        confirmPayments(){
    if(!this.form.studentIds.length){
      return  alert('You have to select atleast, one student')
    }
        this.form.post('/api/confirmPayments')
        .then(res=>{
            toast.fire({
                        type: 'success',
                        title: 'Payments confirmed successfully'
                        })
                        this.Activation_status=[];
                        this.form.reset()
                        Fire.$emit('AfterCreate')
        })

        },


        updatePayment(){

       this.payForm.post('/api/fee_pay/update')
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


        printInvoice(student){
            this.perfectPrint.content=null;
                     student.school=this.school;
                     student.feeSum=this.amount;
                      student.feegroup=this.report;
                      let content=invoiceInfo(student);

              return this.perfectPrint({
                   id:student.name,
                 content:content})

        },
        setCSVDownload(){
           // let downloadDetails=[];
            this.Activation_status.forEach(student=>{

                const feeDetails={
                    Name:student.name,
                    'Fee Sum':this.amount,
                     'Amount Paid':student.amount_paid,
                     Balance:this.amount-student.amount_paid,
                     Level:this.report.levels.level_name,
                     'Fee Type':this.report.title,
                     'Paid On':moment(student.created_at).format('MMMM Do YYYY')

                }
                this.downloadData.push(feeDetails);
            })
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
