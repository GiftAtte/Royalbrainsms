<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Student;
use App\Has_arm;
use App\Level_history;
use App\Level;
use App\LoginDetail;
use App\School;
use Illuminate\Support\Facades\DB;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Hash;
use Excel;
use App\Events\studentCreated;
use App\Level_sub;
use App\Mark;
use App\Report;

ini_set('max_execution_time', '300');

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index($level_id=null,$arm_id=null)
    {    $user=auth('api')->user();


        //   $students=User::where([['school_id',$user->school_id],['type','student']])->get();
        //   User::where([['school_id',$user->school_id],['type','student']])->update([
        //       'password'=>Hash::make('654321')
        //     ]);
        //   foreach($students as $student){
        //       LoginDetail::where('student_id',$student->student_id)->update([
        //           'email'=>$student->email,
        //           'password'=>'654321'
        //       ]);
        //   }

        if(!empty($level_id)&&!empty($arm_id)){

            return Student::with(['levels','arm'])->where([['class_id',$level_id],['arm_id',$arm_id]])
            ->join('users','students.id','=','users.student_id')
            ->select('students.*', 'users.id as userId', 'users.isActive as isActive','users.email as email')
            //->latest()->paginate(50)
            ->orderby('surname')->paginate(50);
        }

    $historyLevel=Level::where('is_history',1)->pluck('id');
        if($user->type==='tutor'){
          $arm=Has_arm::where('staff_id',$user->staff_id)->whereNotIn('level_id',$historyLevel)->first();
            return Student::with(['levels','arm'])->where([['school_id',$user->school_id],['class_id',$arm->level_id],['arm_id',$arm->arms_id]])
                ->join('users','students.id','=','users.student_id')
            ->select('students.*', 'users.id as userId', 'users.isActive as isActive','users.email as email')
            //->latest()->paginate(50)
            ->orderby('surname')->paginate(50);
        }
        if($user->type==='admin'||$user->type==='superadmin'){
            return Student::with(['levels','arm'])->where('students.school_id',auth('api')->user()->school_id)
            ->join('users','students.id','=','users.student_id')
            ->select('students.*', 'users.id as userId', 'users.isActive as isActive','users.email as email')
            //->latest()->paginate(50)
            ->orderby('surname')->paginate(50);
        }
else{
    return[];
}
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  $id=null;



       $school=School::findOrFail(auth('api')->user()->school_id);

        $this->validate($request,[
            'surname' => 'required|string|max:191',
            'first_name' => 'required|string|max:191',
            'class_id' => 'required',
        ]);

                $id= Student::updateOrCreate([
                    'surname' => $request['surname'],
                    'first_name' => $request['first_name'],
                    'middle_name' => $request['middle_name'],
                    'gender' => $request['gender'],
                    'phone' => $request['phone'],
                    'dob' => $request['dob'],
                    'class_id' => $request['class_id'],
                    'arm_id' => $request['arm_id'],
                    'school_id' =>$school->id,
                    'is_new'=>$request->is_new,
                ])->id;
                   //  $email=strtolower('stud'.$id.'@'.$school->short_name.'.com');
                    //  $user->name=$request->surname."".$request->first_name;
                    //  $user->student_id=$id;
                    //  $user->email=$email;
                    //  $user->password=Hash::make($password);
                    //  $user->type='student';
                    //  $user->bio='student';
                    //  $user->portal_id='stud'.$id;
                    //  $user->school_id=auth('api')->user()->school_id;
                    //   $user->photo='stud'.$id.'.png';
                    // $check= $user->save();


                  //event(new studentCreated($name,$id,$email,$request->class_id,null,'student',$school->id));

                //   LoginDetail::create([
                //           'name'=>$request->surname." ".$request->first_name,
                //           'email'=> $email,
                //           'password'=>$password,
                //           'school_id'=>$school->id,
                //           'student_id' =>$id,
                //           'portal_id'=>'stud'.$id,
                //           'level_id'=>$request->class_id,
                //   ]);
                // }else{
                //      $student=Student::findOrFail($id);
                //     $user->destroy();
                //     $student->destroy();
                //      return ['error'=>'user exist'];
                // }
                //  return $user;
        }






    public function update(Request $request, $id)
    {

         $this->validate($request,[
            'surname' => 'required|string|max:191',
            'first_name' => 'required|string|max:191',
            'class_id' => 'required',
        ]);

        $student=Student::findOrFail($id);
        $student->update($request->all());

             $student=LoginDetail::where('student_id',$id)->first();

                 $student->update([
                          'name'=>$request->surname." ".$request->first_name.' '.$request->middle_name,
                          'level_id'=>$request->class_id,
                  ]);

    }

    public function profile()
    {
        return auth('api')->user();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return Student::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStudent(Request $request, $id=null)
    {
        $studentInfo=$request->except('users','levels');
        $this->authorize('isAdminOrTutorOrStudent');
        $this->validate($request,[
            'surname' => 'required|string|max:191',
            'first_name' => 'required|string|max:191',
            'class_id' => 'required',
        ]);
        if($id){
        $student = Student::findOrFail($id);
       $student->update($studentInfo);
       return ['student'=>$studentInfo];
        }
        else{
          $id=auth('api')->user()->student_id;
         $student = Student::findOrFail($id);
     $student->update($studentInfo);
     return $studentInfo;
    }}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $this->authorize('isAdmin');
        $studentIDs=$request->studentIds;

        DB::table('users')->whereIn('student_id', $studentIDs)->delete();
        DB::table('students')->whereIn('id', $studentIDs)->delete();
        DB::table('login_details')->whereIn('student_id', $studentIDs)->delete();


        return ['message' => 'Students Deleted'];
    }

    public function search($id=null){

                if(!empty($id)){
                    $report=Report::findOrFail($id);
                    $level_id=$report->level_id;
                    if ($search = \Request::get('q')) {
                        $users = Student::with(['levels','arm'])->where(function($query) use ($search,$level_id){
                            $query->where('class_id',$level_id)
                            ->where('school_id',auth('api')->user()->school_id)
                                    ->where('surname','LIKE',"%$search%")
                                    ->orWhere('first_name','LIKE',"%$search%")
                                    ->orWhere('middle_name','LIKE',"%$search%");
                               })->paginate(25);
                    }else{
                        $users = Student::with(['levels','arm'])
                        ->where('class_id',$level_id)
                        ->where('school_id',auth('api')->user()->school_id)->latest()->paginate(25);
                    }

                }



        if ($search = \Request::get('q')) {
            $users = Student::with('levels')
             ->whereIn('school_id',[auth('api')->user()->school_id])
             ->where(function($query) use ($search){
                $query->where('surname','LIKE',"%$search%")
                        ->orWhere('first_name','LIKE',"%$search%")
                        ->orWhere('middle_name','LIKE',"%$search%");
                   })->paginate(50);
        }else{
            $users = Student::with(['levels','arm'])->where('school_id',auth('api')->user()->school_id)->latest()->paginate(50);
        }

        return $users;

    }
     public function dashboard(){
        //  $this->authorize('isAdmin');
        $user_count=count(User::all());
        return response()->json(
            ['user_count'=>$user_count,
                'message'=>"user counted successfully"]);

    }

 public function info($id=null)
{
if(!empty($id)){
    return Student::with(['levels','users','arm'])->where('id',$id)->first();
}else{
   $user=auth('api')->user();
   return Student::with(['levels','users','arm'])->where('id',$user->student_id)->first();
}
}





    function import(Request $request)
    {

        $students=$request->csv;

        $school=School::findOrFail(auth('api')->user()->school_id);
        foreach($students as $student){
             //$user =new User();

           $data=[

                    'surname' => $student['surname'],
                    'first_name' => $student['first_name'],
                    'middle_name' => $student['middle_name'],
                    'gender' => $student['gender'],
                    'phone' => $student['phone'],
                    'dob' => $student['dob'],
                    'class_id' => $student['class_id'],
                    'arm_id' => $student['arm_id'],
                    'contact_adress'=>$student['contact_address'],
                    'nationality'   =>$student['nationality'],
                              'lga'=>$student['lga'],
                              'state'=>$student['state'],
                              'blood_group'=>$student['blood_group'],
                              'arm_id'     =>$student['arm_id'],
                               'reg_date'=>$student['reg_date'],
                               //'is_new'=>$student['is_new']?$student['is_new']:'',
                               'school_id'=>$school->id,
                ];



                    if($student['id']>0){
                        $studentData=Student::findOrFail($student['id']);
                         $studentData->update($data);
                    }else{
                        Student::create($data);
                    }
            //    $level=Level::findOrFail($student['class_id']);

            //      Level_history::updateOrCreate(['student_id'=>$id, 'level_id'=>$student['class_id']],[
            //      'student_id'=>$id,
            //      'level_id'=>$student['class_id'],
            //      'level_name'=>$level->level_name,
            //  ]);
           //  $name=$student['surname'].', '.$student['first_name'].' '.$student['middle_name'];



            }
             return $students;
            }


    public  function generateRandomString($length = 6) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

public function exportLogin(){




   $student_login = DB::table('students')
                ->where('students.school_id',auth('api')->user()->school_id)
                ->whereNotNull('students.arm_id')
                ->leftJoin('arms', 'students.arm_id', '=', 'arms.id')
                ->join('levels', 'students.class_id', '=', 'levels.id')
               // ->where('levels.is_history',1)
                ->join('login_details', 'students.id', '=', 'login_details.student_id')
                ->crossJoin('users', 'students.id', '=', 'users.student_id')
                ->select(
                    'students.id',
                    'students.surname',
                    'students.first_name',
                    'students.middle_name',
                    'login_details.email',
                    'login_details.password',
                    'students.class_id',
                    'levels.level_name',
                    'users.portal_id',
                    'users.photo',
                    'login_details.created_at',
                    'arms.name as arm',
                    'users.id as userId',
                    'users.isActive as isActive'
                )
                ->orderby('students.surname')
                ->orderby('levels.level_name')
                ->orderby('arm')

                ->get();
   $staff_login=DB::table('login_details')->whereNotNull('staff_id')->where('school_id',auth('api')->user()->school_id)
    ->select('name','email','password','staff_id','portal_id','created_at')->orderby('level_id')->get();
    $num_students=count($staff_login);
    $num_staff=count($staff_login);

     $parent_login=DB::table('login_details')->whereNotNull('parent_id')->where('school_id',auth('api')->user()->school_id)
             ->select('name','email','password','parent_id','portal_id','created_at')->orderby('name')->get();

   return response()->json(
       ['student_login'=>$student_login,
       'staff_login'=>$staff_login,
       'num_student'=>$num_students,
       'num_staff'=>$num_staff,
        'parent_login'=>$parent_login
    ]);
        }

        public function exportLogins($level_id,$arm_id,$isAccountNumbers=null){
        $student_login=null;

               if(!empty($isAccountNumbers)){
            $student_login = DB::table('students')->where([['students.class_id', $level_id], ['students.arm_id', $arm_id]])
                ->leftJoin('arms', 'students.arm_id', '=', 'arms.id')
                ->join('levels', 'students.class_id', '=', 'levels.id')
                ->join('login_details', 'students.id', '=', 'login_details.student_id')
                ->crossJoin('users', 'students.id', '=', 'users.student_id')
                ->select(
                    'students.id',
                    'students.surname',
                    'students.first_name',
                    'students.middle_name',
                     'students.bankName',
                    'students.accountNumber',

                    'levels.level_name',

                )
                ->orderby('students.surname')
                ->orderby('levels.level_name')

                ->get();
               }
else{
            $student_login = DB::table('students')->where([['students.class_id', $level_id], ['students.arm_id', $arm_id]])
                ->leftJoin('arms', 'students.arm_id', '=', 'arms.id')
                ->join('levels', 'students.class_id', '=', 'levels.id')
                ->join('login_details', 'students.id', '=', 'login_details.student_id')
                ->crossJoin('users', 'students.id', '=', 'users.student_id')
                ->select(
                    'students.id',
                    'students.surname',
                    'students.first_name',
                    'students.middle_name',
                    'login_details.email',
                    'login_details.password',
                    'students.class_id',
                    'levels.level_name',
                    'users.portal_id',
                    'users.photo',
                    'login_details.created_at',
                    'arms.name as arm',
                    'users.id as userId',
                    'users.isActive as isActive'
                )
                ->orderby('students.surname')
                ->orderby('levels.level_name')
                ->orderby('arm')

                ->get();
}

            $staff_login=DB::table('login_details')->whereNotNull('staff_id')->where('school_id',auth('api')->user()->school_id)
             ->select('name','email','password','staff_id','portal_id','created_at')->orderby('level_id')->get();
             $num_students=count($staff_login);
             $num_staff=count($staff_login);

             $parent_login=DB::table('login_details')->whereNotNull('parent_id')->where('school_id',auth('api')->user()->school_id)
             ->select('name','email','password','parent_id','portal_id','created_at')->orderby('name')->get();



            return response()->json(
            ['student_login'=>$student_login,
            'staff_login'=>$staff_login,
            'num_student'=>$num_students,
            'num_staff'=>$num_staff,
            'parent_login'=>$parent_login
        ]);
                 }




        public function updatePassword(){
                 $students=User::whereNotNull('student_id')->where('school_id',auth('api')->user()->school_id)->pluck('student_id');
                   foreach($students as $student){
                       $password=$this->generateRandomString(6);

                        User::where('student_id',$student)->update(['password'=>Hash::make($password)]);
                        LoginDetail::where('student_id',$student)->update(['password'=>$password]);
                        $password='';
                    }
                // DB::table($userTable)->where('age', '<', 18)->update(array('under_18' => 1));
        }



public function importBio(Request $request){
    if($request->has('file')){

   $data=array_map('str_getcsv',file($request->file));
    $header=$data[0];
    unset($data[0]);


    }
}

}


