<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Student;
use App\LoginDetail;
use App\Staff;
use App\Level;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Hash;
use Excel;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
       // $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isUser')) {


                return User::whereNotIn('type',['superadmin'])->where('school_id',auth('api')->user()->school_id)->latest()->paginate(70);


       }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6'
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => $request['photo'],
            'password' => Hash::make($request['password']),
        ]);


    }


    public function updateProfile(Request $request)
    {
        $user = User::findOrFail($request->id);

        $name=$user->photo;

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);


        $currentPhoto = $name;


        if($request->photo != $currentPhoto){
           // $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            $name=$user->portal_id.'.'.'png';

               $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);

            // $userPhoto = public_path('img/profile/').$currentPhoto;
            // if(file_exists($userPhoto)){
            //     @unlink($userPhoto);
            // }

        }


        if(!empty($request->password)){
            if(!empty($request->student_id)){
              $student=  LoginDetail::where('student_id',$request->student_id)->first();
              $student->password=$request->password;
              $student->email=$request->email;
              $student->save();
              // return LoginDetail::where('student_id',$request->student_id)->first();
            }
            else{
             $employee=  LoginDetail::where('staff_id',$request->staff_id)->first();
              $employee->password=$request->password;
              $employee->email=$request->email;
              $employee->save();
             //return LoginDetail::where('staff_id',$request->staff_id)->first();
            }

            $request->merge(['password' => Hash::make($request['password'])]);




        }
        if(!empty($request->photo)){
            $user->photo=strval($name);
          //  return $request->all();
        }
      //return $user;

        $user->update($request->except('photo'));

        return ['message' => "Success"];
    }


    public function profile($id=null)
    {
        if(!empty($id)){

            $user=User::where('student_id',$id)->first();
            if($user){
                if($user->type=='student')
                return $user;
            }else{
            return User::where('staff_id',$id)->first();
            }
        }else{
        return auth('api')->user();
    }}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id=null)
    {

        $user = User::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6'
        ]);

        $user->update($request->all());
        return ['message' => 'Updated the user info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('isSuperadministrator');

        $user = User::findOrFail($id);
        // delete the user

        $user->delete();

        return ['message' => 'User Deleted'];
    }

    public function search(){

        if ($search = \Request::get('q')) {
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%");
            })->paginate(50);
        }else{
            $users = User::where('school_id',auth('api')->user()->school_id)->latest()->paginate(50);
        }

        return $users;

    }
     public function dashboard($id){
        //  $this->authorize('isAdmin');
        $user_count=count(User::where('school_id',$id)->get());
        $student_count=count(Student::where('school_id',$id)->get());
        $staff_count=count(Staff::where('school_id',$id)->get());
        $level_count=count(Level::where('school_id',$id)->get());
        return response()->json(
            ['user_count'=>$user_count,
            'student_count'=>$student_count,
            'staff_count'=>$staff_count,
            'level_count'=>$level_count,
            'message'=>"user counted successfully"]);

    }

    function import(Request $request)
    {

       // return $request->all();
    $this->authorize('isAdmin');
     $this->validate($request, [
    'selected_file'  => 'required|mimes:xls,xlsx,csv'
     ]);
   // return $request->all();



   return response()->json( Excel::import(new UsersImport, $request->file('selected_file')));

}
}

