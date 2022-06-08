<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Parents;
use  App\Http\Controllers\API\StudentController as randomGenerator;
use App\LoginDetail;
use App\Mark;
use App\Report;
use App\Student;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index(){
       return Parents::where([['school_id',auth('api')->user()->school_id],['is_active',1]])->paginate(50);
    }


    public function store(Request $request){



         $data=$request->all();


          $data['school_id']=auth('api')->user()->school_id;
        $newParent= Parents::create($data);

        $random_generator=new randomGenerator();
        $randomPassword=$random_generator->generateRandomString();
        $password=Hash::make($randomPassword);
       $user= User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$password,
            'school_id'=>auth('api')->user()->school_id,
            'parent_id'=>$newParent->id,
            'portal_id'=>'P'.$newParent->id,
            'type'=>'parent'
        ]);
        if(!empty($user)){
  return LoginDetail::create([
                          'name'=>$request->name,
                          'email'=> $user->email,
                          'password'=>$randomPassword,
                          'school_id'=>$user->school_id,
                          'parent_id' =>$user->parent_id,
                          'portal_id'=>'P'.$newParent->id,

                  ]);
        }
      //  return $data;

    }


    public function update(Request $request){
        Parents::where('id',$request->id)->update($request->except('users'));
    }

    public function delete($id){
        Parents::where('id',$id)->update([
            'is_active'=>false
        ]);
         User::where('parent_id',$id)->delete();
    }

 public function siblings($id=null){
     if(!empty($id)){
   $siblings=Student::with(['levels','arm','parents','users'])->where('parent_id',$id)->get();
$parent=Parents::findOrFail($id);
return [
       'parent'=>$parent,
       'siblings'=>$siblings
   ];
}
 $siblings=   Student::with('levels','arm','parents','users')->where('parent_id',auth('api')->user()->parent_id)->get();
   $parent=Parents::findOrFail(auth('api')->user()->parent_id);
   return [
       'parent'=>$parent,
       'siblings'=>$siblings
   ];
    }



 public function addSiblings(Request $request){
   $siblings=$request->siblingsStr;
   if(strlen($siblings)>0){
   $siblingsArr=explode(',',$siblings);


return Student::whereIn('id',$siblingsArr)->update([
        'parent_id'=>$request->parentId
    ]);

}else{
    return "No sibling added";
}

 }

    public function deleteSibling($id){
        Student::where('id',$id)->update([
            'parent_id'=>null
        ]);
    }

    public function getParentById($id=null){
        if(empty($id)){
        return Parents::with('users')->where('id',auth('api')->user()->parent_id)->first();
        }
        return Parents::with('users')->where('id',$id)->first();
    }


public function siblingsResult($studentId=null){
    if(!empty($studentId)){
       // $siblingsIDs=Student::where('parent_id',$studentId)->pluck('id');
$levelIDs=[];
  $currentLevel=Student::where('id',$studentId)->first()->class_id;

 $levelIDs=Mark::whereIn('student_id',[$studentId])
 ->distinct('report_id')->pluck('level_id')
 ->toArray();;
      if(!empty($currentLevel)){
array_push($levelIDs,$currentLevel);
      }
       return Report::whereIn('level_id',$levelIDs)
                  ->with('levels')
                  ->leftJoin('result_activations',function($join) use($studentId){
                      $join->on('result_activations.report_id','=','reports.id')
                      ->where([['result_activations.student_id',$studentId]]);
                  })

         ->select(DB::raw('reports.*, result_activations.activation_status as activation_status'))
         ->distinct('reports.id')  ->orderby('id','desc')->get();

        }

    }

public function importParents(Request $request){

    if($request->has('file')){

      $data=array_map('str_getcsv',file($request->file));
    $headers=$data[0];
      unset($data[0]);
      $parentArr=[];
      foreach($data as $parent ){
          $combinedArr=array_combine($headers,$parent);
          if(!empty($parent[0]))
          array_push($parentArr,$combinedArr);
      }



      $school_id=auth('api')->user()->school_id;
      $siblingsArr=[];
      foreach($parentArr as $parent){

        $parent['school_id']=$school_id;
        $newParent= Parents::create($parent);

        $random_generator=new randomGenerator();
        $randomPassword=$random_generator->generateRandomString();
        $password=Hash::make($randomPassword);
       $user= User::create([
            'name'=>$parent['name'],
            'email'=>$parent['email'],
            'password'=>$password,
            'school_id'=>$school_id,
            'parent_id'=>$newParent->id,
            'portal_id'=>'P'.$newParent->id,
            'type'=>'parent'
        ]);
        if(!empty($user)){
   LoginDetail::create([
                          'name'=>$parent['name'],
                          'email'=> $user->email,
                          'password'=>$randomPassword,
                          'school_id'=>$user->school_id,
                          'parent_id' =>$user->parent_id,
                          'portal_id'=>'P'.$newParent->id,

                  ]);
                  array_push($siblingsArr,[
                      'parent_id'=>$newParent->id,
                      'student_id'=>$parent['student_id']
                  ]);
        }

      }

return $this->updateStudents($siblingsArr);

}


}

  public function updateStudents($siblings=[]){
          if(count($siblings)>0){
         foreach($siblings as $sibling){
          $studentIds=explode(',', $sibling['student_id']);
          Student::whereIn('id',$studentIds)->update([
              'parent_id'=>$sibling['parent_id']
          ]);
    }}
    return 'success';
      }
}



