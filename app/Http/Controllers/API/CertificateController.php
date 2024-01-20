<?php

namespace App\Http\Controllers\API;

use App\Certificate;
use App\Http\Controllers\API\Utils\AppUtils;
use App\Http\Controllers\Controller;
use App\Student;
use App\StudentCertificate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Certificate::with('user')->where('school_id',AppUtils::getSchoolId())->latest()->get();
    }

   
    public function store(Request $request)
    {
        
        $request->validate([
            'title'=>'required',
            'description'=>'required'
          ]);
   

          $data=$request->all();
          $data['created_by']=auth('api')->user()->id;
          $data['school_id']=AppUtils::getSchoolId();
       return Certificate::create($data);
    }

    public function show(Certificate $certificate)
    {
        return $certificate;
    }

   
    public function update(Request $request)
    {
          $request->validate([
            'title'=>'required',
            'description'=>'required'
          ]);
   
  
          $data=$request->all();
          $data['created_by']=auth('api')->user()->id;
          $data['school_id']=AppUtils::getSchoolId();
        Certificate::where('id',$request->id)->update($data);
        return ['message'=>'updated successfully'];
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return;
    }



public function loadCertifications(){
    return StudentCertificate::with('student')->latest()->get();
}





public function assignCertificates(Request $request){
    $certificateId=$request->certificate_id;
    $students=$request->students;
    foreach ($students as  $student) {
     $this->assignCertifiCateTo($student['id'],$student['certificateNumber'],$certificateId);
    }
    return ['message'=>'success'];
}



    public function assignCertifiCateTo($studentId,$certificateNumber,$certificateId){
   if(!empty($certificateId)){
    StudentCertificate::create([
        'certificate_id'=>$certificateId,
        'student_id'=>$studentId,
        'certificate_number'=>$certificateNumber,
        'school_id'=>AppUtils::getSchoolId(),
        'created_by'=>auth('api')->user()->id
    ]);
   }
    }




    public function updateCertification(Request $request)  {
       $request->validate([
            'certificate_id'=>'required',
            'certificate_number'=>'required',
        ]);

        $data=$request->all();
        $data['created_by']=auth('api')->user()->id;
        $data['school_id']=AppUtils::getSchoolId();
        StudentCertificate::where('id',$request->id)->update($data);
        return ['message'=>'updated successfully'];

    }


    public function deleteCertification($id)  {
        StudentCertificate::where('id',$id)->delete();
        return ['message'=>'done'];
    }


    public function loadStudents() {
        return Student::where('school_id',AppUtils::getSchoolId())->latest()->get();
    }


    public function verifyCertificate($certificateNumber){
       $certificate=StudentCertificate::with('student','certificate')
                                        ->where('certificate_number',$certificateNumber)
                                        ->first();
        if(!empty($certificate)){
            return  response()->json([
                 'name'=>"{$certificate->student->surname} {$certificate->student->first_name}",
                 'certificate_name'=>$certificate->certificate->title,
                 'certificate_number'=>"{$certificate->certificate_number}",
                 'created_at'=>Carbon::parse( $certificate->created_at)->format('Y-m-d')
                
            ]);
        }
        else{
            return  response()->json([
                'errorMessage'=>"Invalid certificate number"
            ],404);
        }
    }
}
