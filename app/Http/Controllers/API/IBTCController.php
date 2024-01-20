<?php

namespace App\Http\Controllers\API;

use App\Fee_description;
use App\Fee_group;
use App\Http\Controllers\API\Utils\AppUtils;
use App\Http\Controllers\Controller;
use App\School;
use App\Student;
use App\StudentFees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class IBTCController extends Controller
{
    //


    public function IBTCApi($method,$url,$body) {

      $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'apikey' => "test123",
        'ClientId' => "neovast",
    ])
        ->$method("https://{serverip}:{port}/". $url, $body);
    return $response->json();
    
    }



public function generateAccountNumber(){
    
    $studentIds=Student::whereIn('school_id',[AppUtils::getSchoolId()])
                 ->whereNull('accountNumber')->pluck('id');
    // $bankPrefix=env('BANK_PREFIX_NUMBER');
    // $bank_identifier=School::findOrFail(auth('api')->user()->school_id)->bank_identifier;
  // for ($i=0; $i <count($studentIds) ; $i++){ 
  // //  $accountNumber=$this->accountGenerator($bankPrefix,$bank_identifier,$i);
  // //  $student=Student::where('id',$studentIds[$i])->first();
  // //  if(empty($student->accountNumber))
  // //  $student->accountNumber=$accountNumber;
  // //  $student->save();

  // }
 foreach ($studentIds  as $key => $value) {
  $this->createAnAccount($value);
 }

}



    public function accountGenerator($prefixNumber,$bank_identifier,$count){
      $FirstSixDigits="{$prefixNumber}{$bank_identifier}";
       $lastFiveDigits=str_pad( $count,4,"0",STR_PAD_LEFT);
       return "{$FirstSixDigits}{$lastFiveDigits}";
}




public function accountEnquiry(Request $request){

        
       $accountNumber=$request->accountNumber;
       $student=Student::where('accountNumber',$accountNumber)->first();
       if(empty($student)){
        return response()->json([
                    "accountNumber"=> "",
                    "accountName"=> "",
                    "bvn"=> "",
                    "responseCode"=> 07,
                    "responseDescription"=> "Invalid Account"]);
                     }
       $school=School::findOrFail($student->school_id);

       return response()->json(
        [ "accountName"=>"{$student->surname} {$student->first_name}",
          "accountNumber"=>$student->accountNumber,
          "bvn"=>$student->school_id,
          "responseCode"=>"00",
          "message"=>"success"
      ],200);
    
   

}



// https://thinkschoolapps.co.uk/api/ibtc/name-enquiry


public function onFeePaymentSuccess(Request $request) {
    
    
    $isValidProviderSecret = $request->hasHeader('provider_secret') && $request->header('provider_secret')===env('PROVIDER_ID');
   if ($isValidProviderSecret){

  
  
  foreach($request->all() as  $paymentDetails){
     $student=Student::where('accountNumber',$paymentDetails['customerAccountNumber'])->first();
   if(!empty($paymentDetails['checkSum']) && !empty($student)){ 
       $amountPaid=$paymentDetails['amount'];
      
       $feegroup=Fee_group::where('level_id',$student->class_id)->latest()->first();

        $feeSum=Fee_description::where('feegroup_id',$feegroup->id)->sum('amount');
        $paymentExist=StudentFees::where([['feegroup_id',$feegroup->id],['student_id',$student->id]])->first();
        
        if(empty($paymentExist)){
             if($amountPaid>=$feeSum){
              $this->makeFullPayment($feegroup->id,$student->id,$amountPaid,$paymentDetails['transactionReferenceId']);
             }else{
               $this->makePartialPayment($feegroup->id,$student->id,$amountPaid,$paymentDetails['transactionReferenceId']);    
             }
            
         }
         else{
          $this->updatePartialPayment($feegroup->id,$student->id,$amountPaid,$paymentDetails['transactionReferenceId']);
        
        }
        
       
       
      
          }

      
      
  }
   $data[]=[
            "requestId"=>env('BANK_PREFIX_NUMBER').'-'.$paymentDetails["checkSumId"],
            "accountNumber"=> $student->accountNumber,
            "responseMessage"=> "Successful ",
            "isSuccessful"=> true,
           
         ];
   return response()->json([
            'responseDetails'=>$data,
          "responseCode"=> "00",
        "responseDescription"=> "Approved or completed successfully" 
         
         
         
         ],200); 
}else{
   return response()->json([
          
            "responseDetails"=>[
            "requestId"=>env('BANK_PREFIX_NUMBER'),
            "accountNumber"=> "",
            "responseMessage"=> "Invalid provider id",
            "isSuccessful"=> false 
           
       ]],419);
      }
}
  







public function makeFullPayment($feegroup_id,$student_id,$amount,$transctionId)  {

  return StudentFees::create([
    'feegroup_id'=>$feegroup_id,
    'student_id'=>$student_id,
    'amount'=>$amount,
    'activation_status'=>1,
    'message'=>'Payments completed',
    'transaction_id'=>$transctionId,
     'reference_id'=>$transctionId,
]);
    
}


public function makePartialPayment($feegroup_id,$student_id,$amount,$transctionId)  {
  
     
  return StudentFees::create([
    'feegroup_id'=>$feegroup_id,
    'student_id'=>$student_id,
    'amount'=>$amount,
    'activation_status'=>0,
    'message'=>'Payments completed',
    'transaction_id'=>$transctionId,
     'reference_id'=>$transctionId,
]);
}


public function updatePartialPayment($feegroup_id,$student_id,$amount,$transctionId)  {
  $initialPayment=StudentFees::where([['feegroup_id',$feegroup_id],['student_id',$student_id]])->first(); 
   $totalAmount=$initialPayment->amount+$amount;
   $initialPayment->delete();
   return $this->makeFullPayment($feegroup_id,$student_id,$totalAmount,$transctionId);

}





public function verifyTransaction($transctionId) {
  $url="";
  $body=[
    "transactionId"=>$transctionId
  ];
return $this->IBTCApi("post",$url,$body);

}








public function nenerateNewAccoutNumber(){



  
}





public function createAnAccount($id){
   $count=Student::where('school_id',AppUtils::getSchoolId())->count()+1;

   $prefix=env('BANK_PREFIX_NUMBER');
   $bank_identifier=School::findOrFail(auth('api')->user()->school_id)->bank_identifier;
   $accountNumber= $this->accountGenerator($prefix,$bank_identifier,$count);

      while($this->accountExist($accountNumber)){
        $count=$count+1;
        $accountNumber= $this->accountGenerator($prefix,$bank_identifier,$count);
      }
        
      Student::where('id',$id)->update(['accountNumber'=>$accountNumber]);

      return Student::findOrFail($id);
}



public function accountExist($accountNumber){
  $student=Student::where('accountNumber',$accountNumber)->first();
  if(empty($student)){
    return false;
  }
  return true;
}


 public function getPayments(){


  $feeGroupIds=Fee_group::where('school_id',AppUtils::getSchoolId())->pluck('id');
 return  DB::table('student_fees')->whereIn('student_fees.feegroup_id',$feeGroupIds)
   ->leftJoin('students','student_fees.student_id','=','students.id')
   ->join('levels','students.class_id','=','levels.id')
   ->join('arms','students.arm_id','=','arms.id')
   ->join('fee_groups','student_fees.feegroup_id','=','fee_groups.id')
   ->select(DB::raw("CONCAT(students.surname,' ', students.first_name)as name,students.accountNumber,student_fees.amount,student_fees.created_at,
   levels.level_name as level,fee_groups.tittle,fee_groups.term_id,CONCAT(levels.level_name,'-',arms.name) as arm"))
  ->orderBy('created_at','desc')->get();
 }

}



 
