<template>
<div>
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">SMS CENTER</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Send SMS</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="col-md-12 row">

        <div class="col-md-6">
            <div class="form-group">
                <label>RECIPIENTS</label>
 <select class="form-control"
 v-model="form.type"
 @change="getRecipients"
 >
    <option value="">Select Recipients</option>
     <option value="parents">Parents</option>
      <option value="students">Students</option>
       <option value="staff">Teachers</option>
 </select>

        </div>

<h2 class="text-primary text-uppercase text-center pt-5"> SEND MESSAGE TO: {{form.type}}</h2>
<hr class="text-bold text-danger">
</div>
<div class="col-md-6">
    <div class="card card-navy card-outline row ">

  <div class="card-header">

      <h3 class="card-title">Send SMS </h3>
      <div class="ribbon-wrapper">
    <div class="ribbon bg-primary">
      Email
      </div>
    </div>
  </div>
<form  @submit.prevent="sendMessage">
<div class="card-body">
<div class="form-group">

<button type="button" class="btn btn-primary float-right" @click="setEmail"><i class="fa fa-envelope" ></i> Add Numbers</button>

</div>

 <div class="form-group" v-show="isEmail">
     <label>Phone Number</label>
     <input type="text" name="resipeint" v-model="resipeint" class="form-control">
    </div>

        <div class="form-group">
     <label>FROM</label>
     <input  v-model="sender" name="subject" class="form-control" required maxlength="11"
     placeholder="message from">
    </div>

    <div class="form-group">
     <label>Enter  Message</label>
      <textarea rows="5"  class="form-control" v-model="message" required >Enter Message</textarea>

    </div>

<div class="card-footer">
<button class="btn btn-success" type="submit"><i class="fa fa-envelope" ></i> send </button>
</div>
</div>
</form>


    </div>
</div>
    </div>

    </div>
</template>

<script >

import { VueEditor } from "vue2-editor";
    export default {
        components: {
    VueEditor
  },
    data(){
      return {
        isEmail:false,
        Recipients:'',
        resipeint:'',
        sender:'',
        message:'',
        email:'attegift@gmail.com',
        apikey:'22b6d93d3efda662aac6da47790ebbe21712fbe2',

        form:new Form({
          type:'',
          email:'',
          subject:'',


        })
      }

    },
 methods:{
     setEmail(){
         if(!this.isEmail){
       this.isEmail=true
         }else{
              this.isEmail=false
         }



     },
     getRecipients(){
       this.form.post('api/send_mail')
       .then(result=> {
             this.Recipients=result.data
               console.log(this.Recipients);
       })


     },
     sendMessage(){
let Recipient=`${this.resipeint?'234'+this.resipeint:''}${this.Recipients?this.Recipients:''}`
 swal.fire({
                    title: 'Are you sure?',
                    text: "You want to send message to: "+ ''+ Recipient,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Send!'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {

           let request = new XMLHttpRequest();
           let url=`
             http://api.ebulksms.com:4433/sendsms?username=${this.email}&apikey=${this.apikey}&sender=${this.sender}&messagetext=${this.message}&flash=0&recipients=${Recipient}`

              request.open('GET', url);
              request.send(null)

                                        swal.fire(
                                        'success!',
                                        'Message Sent successfully',
                                        'success'
                                        )
this.Recipients=''
this.resipeint='';
this.message='';
this.sender='';

                         }
                    })








     },
     sendEmail(){
         emailjs.sendForm('gmail', 'template_jf2ujch', e.target, 'user_oJ72BF49hV8qMCMISA5OE')
        .then((result) => {
            console.log('SUCCESS!', result.status, result.text);
        }, (error) => {
            console.log('FAILED...', error);
        });
     }

 }
    }
</script>
