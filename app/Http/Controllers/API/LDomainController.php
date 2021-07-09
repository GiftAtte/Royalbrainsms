<?php

namespace App\Http\Controllers\API;
use App\Learning_domain;
use App\Assessment;
use App\Report;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LDomainController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {

        return Learning_domain::where('school_id',auth('api')->user()->school_id)
        ->latest()->paginate(10);
    }

    public function store(Request $request)
    {


        $this->validate($request,[
            'type' => 'required',
            'domain' => 'required',


        ]);
        return Learning_domain::create([
            'type' => $request->type,
            'domain' => $request->domain,
            'school_id' => auth('api')->user()->school_id,
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'type' => 'required',
            'domain' => 'required',



        ]);
       $learn_domain=Learning_domain::findOrFail($request->id);
       $learn_domain->update($request->all());
    }


    public function destroy($id)
    {
       $learn_domain=Learning_domain::findOrFail($id);
       $learn_domain->delete();
    }



    public function loadAssessment($arm_id,$report_id,$ld_id){
    $report=Report::findOrFail($report_id);

    return  DB::table('students')->where([['students.class_id',$report->level_id],['students.arm_id',$arm_id]])
      ->leftJoin('assessments', function($join) use($report_id,$ld_id)
      {
          $join->on('assessments.student_id', '=', 'students.id')
          ->where([['assessments.report_id',$report_id],['learning_domain_id',$ld_id]]);
      })
      ->select(DB::raw('CONCAT(students.surname," ", students.first_name)as name,
       students.id as student_id, assessments.grade as grade

       '))->distinct('students.id')
      ->get();

  }


   public function getDomain(){
       return Learning_domain::where('school_id',auth('api')->user()->school_id)->get();
   }



     public function insertAssessment( Request $request){
        $students=$request->number_of_students;
       $report=Report::findOrFail($request->report_id);
      for ($i=0;$i<$students;++$i){
     DB::table('assessments')->updateOrInsert(
          ['report_id' => $request->report_id, 'student_id' => $request->student_id[$i],'learning_domain_id'=>$request->learning_domain_id],
          [
          'student_id'=>$request->student_id[$i],
          'report_id'=>$request->report_id,
          'arm_id'=>$request->arm_id,
          'learning_domain_id'=>$request->learning_domain_id,
          'grade'=>$request->grade[$i],
          'level_id'=>$report->level_id



      ]);

  }
  return  ["success"=>"Assessment created success"];
}

   public function removeAssement(Request $request){
                if($request->has('learning_domain_id')){
                    Assessment::where([
                        ['report_id',$request->report_id],
                        ['arm_id',$request->arm_id],
                        ['learning_domain_id',$request->learning_domain_id]
                    ])->delete();
                }
   }



     }

