<?php $title = isset($item) ? $item->name: "Send Mail" ?>

<div class="row">
  <div >
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">{{ $title }}</h3>
      </div>
      <div class="box-body">
       
       
  <div class="col-md-8 col-md-offset-2">
      <div class="table-responsive">
      <table class="table  table-responsive cellspacing="0""  width="100%">
          <thead>
            <tr>
              <th>Send SMS to Parents</th>
              
               
                   <th>Send Email to Employee</th>
                     <th>Send Email  to Students</th>
                      <th>Add Email  Addressr</th>
            </tr>
          </thead>
          


          <tbody id="attendance">
            
          </tbody>
          </table>
          </div>
    <div >
        <div class="form-group" style="display:none" id="phoneDiv">
     <label>Enter Email</label>
     <input  name="email" class="form-control">
    </div>
   <div >
        <div class="form-group">
     <label>Enter Subject</label>
     <input  name="subject" class="form-control">
    </div>
   
    <div class="form-group">
     <label>Enter  Message</label>
     <textarea  id="compose-textarea" name="message" class="form-control"style="height:200px"></textarea>
    </div>
   
    
<div class="box-footer">
  	 <button type="submit" class="btn btn-primary " ><i class="fa envelope"></i> SendMessage</button>
  	</div>
  </div>
  
  

</div>
      </div>
      
    </div>
    

  
