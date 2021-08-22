<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Staff;
use App\School;
use App\LoginDetail;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Hash;
use Excel;


class EmployeeController extends Controller
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
    public function index()
    {
        $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isUser')) {
            return Staff::with('users')->where('staff.school_id',auth('api')->user()->school_id)
             ->join('users','staff.id','=','users.staff_id')
            ->select('staff.*', 'users.id as userId', 'users.isActive as isActive')->latest()->paginate(50);

       }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { //$user = auth('api')->user();
        $id=null;
        $email='';
     $user =new User();
      $school=School::findOrFail(auth('api')->user()->school_id);
      $this->validate($request,[
    'surname' => 'required|string|max:191',
    'first_name' => 'required|string|max:191',
    'phone' => 'required|string|max:191',
    'gender' => 'required|string|max:191',
    'qualification' => 'required|string|max:191',
    //'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,

]);

try{
        $id= Staff::updateOrCreate([
            'surname' => $request['surname'],
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'],
            'gender' => $request['gender'],
            'phone' => $request['phone'],
            'qualification' => $request['qualification'],
            'school_id'=>auth('api')->user()->school_id



        ])->id;
        $password=$this->generateRandomString();
            $email=strtolower('emp'.$id.'@'.$school->short_name.'.com');
             $user->name=$request->surname." ".$request->first_name;
             $user->staff_id=$id;
             $user->email=$email ;
             $user->password=Hash::make($password);
             $user->type=$request->type;
             $user->bio='Staff';
             $user->portal_id='emp'.$id;
             $user->photo='emp'.$id.'.png';
             $user->school_id=auth('api')->user()->school_id;
          $user->save();
         return
             LoginDetail::create([
               'name'=>$request->surname." ".$request->first_name,
               'staff_id'=>$id,
               'email'=>$email,
               'password'=>$password,
               'portal_id'=>'emp'.$id,
               'school_id'=>auth('api')->user()->school_id
             ]);
         }

        catch(Exception $e){
            Staff::delete('id', $id);
             return $e;
        }

    }


    public function profile($id=null)
    {
          if(!empty($id)){
        return Staff::with('users')->where('id',$id)->first();
    }else{
        $user=auth('api')->user();
        $id=$user->staff_id;
        return Staff::with('users')->where('id',$id)->first();
    }
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
        return Staff::all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

             $this->validate($request,[
    'surname' => 'required|string|max:191',
    'first_name' => 'required|string|max:191',
    'phone' => 'required|string|max:191',
    'gender' => 'required|string|max:191',
    'qualification' => 'required|string|max:191',
    //'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,

]);

        $staff = Staff::findOrFail($request->id);



        $staff->update($request->all());
        $user=User::where('staff_id',$request->id)->first();
     $user->type=$request->type;
     $user->save();
        return ['message' => 'Updated the staff info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

       $isCurrent_user=auth('api')->user()->id;

         $staff = Staff::findOrFail($id);

      $user = User::where('staff_id',$id)->first();
        // delete the user
        if($id===$isCurrent_user){
            return ['error'=>'You cannot delete the current user'];
        }
        $staff->delete();
        $user->delete();
        return ['message' => 'User Deleted'];

    }
    public function search(){

        if ($search = \Request::get('q')) {
            $users = Staff::with('users')->where(function($query) use ($search){
                $query->where('surname','LIKE',"%$search%")
                        ->orWhere('first_name','LIKE',"%$search%")
                        ->orWhere('middle_name','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users = Staff::with('users')->where('school_id',auth('api')->user()->school_id)->latest()->paginate(20);
        }

        return $users;

    }




    function import(Request $request)
    {

 $employees=$request->csv;
 $user =new User();


 //
        //return  $student_id;
        foreach($employees as $employee){
            $email=strtolower($employee['surname'].$employee['first_name'] ."@nns.com");
           $id= Staff::create([

                    'surname' => $employee['surname'],
                    'first_name' => $employee['first_name'],
                    'middle_name' => $employee['middle_name'],
                    'gender' => $employee['gender'],
                    'phone' => $employee['phone'],
                    'dob' => $employee['dob'],
                    'class_id' => $employee['class_id'],
                    'arm_id' => $employee['arm_id'],
                    'contact_adress'=>$employee['contact_address'],
                    'nationality'   =>$employee['nationality'],
                              'lga'=>$employee['lga'],
                              'state'=>$employee['state'],
                              'blood_group'=>$employee['blood_group'],
                              'arm_id'     =>$employee['arm_id'],
                               'qualification'=>$employee['qualification'],
                ])->id;

                $user->name=$employee['surname']."".$employee['first_name'];
                $user->staff_id=$id;
                $user->email=$employee['email'];
                $user->password=Hash::make($this->generateRandomString());
                $user->type=$employee['type'];
                $user->bio='Staff';
                $user->portal_id='emp'.$id;
                $user->school_id=$employee['school_id'];
                $user->save();



             }
             return $employees;
            }
//updating employee info
public function updateEmployee(Request $request, $id=null)
    {
        $employeeInfo=$request->except('users','levels');
        $this->authorize('isAdminOrTutorOrStudent');
        $this->validate($request,[
            'surname' => 'required|string|max:191',
            'first_name' => 'required|string|max:191',
            'gender' => 'required',
            'qualification' => 'required',
        ]);
        if(!empty($id)){
        $employee = Staff::findOrFail($id);
       $employee->update($employeeInfo);
       $user=User::where('staff_id',$id)->first();
         $user->type=$request->type;
           $user->update();
       return ['employee'=>$employeeInfo];
        }
        else{
          $id=auth('api')->user()->staff_id;
         $employee = Staff::findOrFail($id);
     $employee->update($employeeInfo);
     $user=User::where('staff_id',$id)->first();
     $user->type=$request->type;
     $user->update();
     return $employeeInfo;
    }}



public function getEmployee()
{
    return \DB::table('staff')->where('school_id',auth('api')->user()->school_id)->select(\DB::raw('CONCAT(surname," ", first_name)as name,id as id'))->get();
    # code...
}


    public  function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


}

