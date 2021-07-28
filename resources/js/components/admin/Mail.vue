<template>
<div>
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Email</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Send Mail</li>
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


</div>
<div class="col-md-6">
    <div class="card card-navy row ">

  <div class="card-header">

      <h3 class="card-title">Email Sender </h3>
      <div class="ribbon-wrapper">
    <div class="ribbon bg-primary">
      Email
      </div>
    </div>
  </div>
<form  @submit.prevent="sendMessage">
<div class="card-body">
<div class="table-responsive">
<table class="table ">
<thead>
<tr>
<th>Send SMS to Parents	</th>
<th>Send SMS to Staff	</th>
<th>Send SMS to Students</th>
<th>Add Email Address	</th>
</tr>
</thead>
<tbody>
<tr>
<td><div class="icheck-primary">
  <input type="radio" id="checkbox1" name="type" value="parents" v-model="form.type">
  <label for="checkbox1">
    Parents
  </label>
</div></td>
<td><div class="icheck-primary">
  <input type="radio"  name="type" id="checkbox2" value="staff" v-model="form.type">
  <label for="checkbox2">
    Staff
  </label>
</div></td>
<td><div class="icheck-primary">
  <input type="radio"  name="type" id="checkbox3" value="students" v-model="form.type">
  <label for="checkbox3">
    Students
  </label>
</div></td>
<td>
<button type="button" class="btn btn-primary" @click="setEmail"><i class="fa fa-envelope" ></i> Add Email Address</button>
</td>

</tr>
</tbody>
</table>
</div>
<div v-show="isEmail" class="form-group">
<label>Enter  Email</label>
<input  class="form-control" name="email" v-model="form.email" placeholder="enter email address" type="email">
</div>
 <div class="form-group" style="display:none" id="phoneDiv">
     <label>Enter Email</label>
     <input  name="email" class="form-control">
    </div>

        <div class="form-group">
     <label>Enter Subject</label>
     <input  v-model="form.subject" name="subject" class="form-control">
    </div>

    <div class="form-group">
     <label>Enter  Message</label>
      <textarea rows="5"  class="form-control">Enter Message</textarea>

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
        Recipients:'2348063611790',
        sender:'Royalbrains',
        message:'Testing things',
        email:'attegift@gmail.com',
        apikey:'22b6d93d3efda662aac6da47790ebbe21712fbe2',

        form:new Form({
          type:'',
          email:'',
          subject:'',
          message:"<h1>Some initial content</h1>"

        })
      }

    },
 methods:{
     setEmail(){
       this.isEmail=true
     },
     getRecipients(){
       this.form.post('api/send_mail')
       .then(result=> {
             this.Recipients=result.data
                console.log(this.Recipients);
       })


     },
     sendMessage(){
         let request = new XMLHttpRequest();
         let url=`
             http://api.ebulksms.com:4433/sendsms?username=${this.email}&apikey=${this.apikey}&sender=${this.sender}&messagetext=${this.message}&flash=0&recipients=${this.Recipients}`

              request.open('GET', url);
              request.send(null)


     }

 }
    }
</script>
