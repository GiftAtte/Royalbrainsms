<?php

namespace App\Http\Controllers\API\Admission;

use App\Http\Controllers\API\Utils\AppUtils;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\User;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{

public function candidateList() {
   
   return Candidate::with('user')
   ->where('school_id',AppUtils::getSchoolId())->get();
}


public function profile(){
    $id=auth('api')->user()->candidate_id;
    if($id){
 return Candidate::findOrFail();
    }
   return '';

}

   public function store(Request $request){


    $data=$request->validate([
        'first_name'=>'required',
         'surname'=>'required',
          'gender'=>'required',
           'dob'=>'required',
           'class_id'=>'required',
           'phone'=>'required',
    'contact_adress'=>'required',
    'nationality'=>'required',
    'state'=>'required',
    'lga'=>'required',
    'blood_group'=>'required',
    ]);

    $data['school_id']=AppUtils::getSchoolId();

  $id=Candidate::updateOrCreate(['id'=>$request->id],$data)->id;
   User::where('id',auth('api')->user()->id)->update([
    'candidate_id'=>$id
   ]);
return[
    'status'=>'success',
    'status_code'=>200
];
   }






}
