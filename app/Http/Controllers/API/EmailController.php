<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Student;
use App\Staff;
use App\User;
use App\Http\Controllers\Controller;
class EmailController extends Controller
{
    function index()
    {
     return view('admin.Mail.create');
    }

     function send(Request $request)
    {   $name=null;
        $Recipients='';

if(($request->email!=null)||($request->type!=null)){

         if($request->type=='students'){
             $name="STUDENT";
              $recipients=Student::whereNotNull('phone')
              ->where('school_id',auth('api')->user()->school_id)
              ->select('phone')->get();
              foreach($recipients as $res){
                  $phone=',234'.$res->phone;
           $Recipients=$Recipients.',234'.$res->phone;
       }
       return$Recipients;
}
      if($request->type=='staff'){
          $name="STAFF";
              $recipients=Staff::whereNotNull('phone')
              ->where('school_id',auth('api')->user()->school_id)
              ->select('phone')->get();
              foreach($recipients as $res){
              $phone=',234'.$res->phone;
           $Recipients=$Recipients.',234'.$res->phone;
       }
      return$Recipients;
}
      if($request->type=='parents'){
          $name="PARENTS";
              $recipients=Student::whereNotNull('fphone')
              ->where('school_id',auth('api')->user()->school_id)
              ->select('fphone')->get();
              foreach($recipients as $res){
                $phone=',234'.$res->fphone;
           $Recipients=$Recipients.',234'.$res->phone;
       }
       return$Recipients;
}
 elseif($request->email!=null){
              $Recipients=','.(string)$request->email;
              $recipients=explode(",",$request->email);
             $name='emails';

         }

        $data = array(
            'name'      =>  $name,
            'message'   =>   $request->message,
            'subject'=>$request->subject
        );
      //  $message=$request->message;
     //$sms=new EbulkSMS();
     $re= explode(",",$Recipients);
    $re[0]="attegift@gmail.com";
     //return $re;

      //for($i=0;$i<count($recipients);++$i){



     Mail::to($re)->send(new SendMail($data));
     return ['success'=> 'Mail sent to '.(count($re)-1)." ". $name];

    }else{
        return ['error'=>"Please Enter email Address"];
    }
    }


    public function createSMS(){
        return view('admin.sms.create');
    }

    public function sendSMS(Request $request, EbulkSMS $sms){
         $Recipients=null;
         $recipients=null;

    if(($request->phone!=null)||($request->type!=null)){


         if($request->type=='students'){
              $recipients=Student::whereNotNull('PhoneNumber')->select('PhoneNumber')->get();
              foreach($recipients as $res){
           $Recipients=$Recipients.',234'.$res->PhoneNumber;
       }

         }
          if($request->type=='parents'){
              $recipients=Parents::whereNotNull('Pphone')->select('Pphone')->get();
              foreach($recipients as $res){
           $Recipients=$Recipients.',234'.$res->Pphone;
       }

         }
       if($request->type=='staff'){
              $recipients=Employee::whereNotNull('phoneNumber')->select('PhoneNumber')->get();
               foreach($recipients as $res){
           $Recipients=$Recipients.',234'.$res->PhoneNumber;
       }

         }
         elseif($request->phone!=null){
              $Recipients=(string)$request->phone;
              $recipients=explode(",",$request->phone);


         }
 // return  $Recipients;
       $message=$request->message;



       $sms->composeMessage($message)
                        ->addRecipients($Recipients)
                        ->send(); //
                         return back()->with('success', 'Message sent sent successfully to '." ". count($recipients)." "."contacts");
    }
    else{
        return back()->withErrors('Please add recipients');

    }}
}
