<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\API\StudentController;
use App\School;
use Illuminate\Support\Facades\Auth;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Milon\Barcode\Facades\DNS1DFacade;
class BiometricController extends Controller
{
    

public function downloadBarcode($sudentId)  {
    

$school=School::findOrFail(auth()->user()->school_id);

$student=Student::with('users','levels')->where('id',$sudentId)->first();

$data=[
    'id'=>$student->id,
    'name'=>"{$student->surname} {$student->first_name}",
    'level'=>$student->levels->level_name,
    'level_id'=>$student->class_id,
    'arm_id'=>$student->arm_id,
    'school_id'=>$student->school_id,
    "gender"=>$student->gender,
    'img'=>$student->users->photo,
    
  ];
$barcode= base64_encode(QrCode::format('svg')->generate(collect($data)->__toString()));
$barcode=base64_encode(DNS1DFacade::getBarcodeSVG($student->id, 'C39'));
$user=Auth::user();
view()->share(
    ['barcode'=>$barcode,
    'school'=>$school,
    'user'=>$user,
    'student'=>$student,
    'accountNumber'=>$student->accountNumber
]);
$pdf = PDF::loadView('biometric.student-bio');


return  $pdf->download($student->first_name.' '.$student->surname.'.pdf');

}


}
