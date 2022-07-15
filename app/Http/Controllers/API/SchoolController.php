<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Utils\AppUtils;
use App\School;
use App\User;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Level;
use App\Staff;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;

class SchoolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        //
        return School::latest()->paginate(50);
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
            'contact_address' => 'required|string',
            'short_name' => 'required|string',

        ]);
         if(!empty($request->logo)){
           $name = time().'.' . explode('/', explode(':', substr($request->logo, 0, strpos($request->logo, ';')))[1])[1];
           // $name=$user->portal_id.'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->logo, ';')))[1])[1];
            \Image::make($request->logo)->save(public_path('img/schools/').$name);




        return School::create([
            'name' => $request['name'],
            'contact_address' => $request['contact_address'],
            'state' => $request['state'],
            'country' => $request['country'],
            'website'=>$request['website'],
            'logo' => $name,
            'phone' => $request['phone'],
             'email'=>$request['email'],
             'gateway_key'=>$request['gateway_pk']
        ]);

        }
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
       return School::findOrFail($id);
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

        $school = School::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'contact_address' => 'required|string',
            'short_name' => 'required|string',

        ]);
$currentPhoto=$school->logo;

$name='';
if($request->logo != $currentPhoto){
            $name = time().'.' . explode('/', explode(':', substr($request->logo, 0, strpos($request->logo, ';')))[1])[1];
           // $name=$school->portal_id.'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            \Image::make($request->logo)->save(public_path('img/schools/').$name);
            $request->merge(['logo' => $name]);

            $schLogo = public_path('img/profile/').$currentPhoto;
            if(file_exists($schLogo)){
                @unlink($schLogo);
            }

        }



        if(!empty($request->logo)){
            $school->logo=strval($name);
          //  return $request->all();
        }


        $school->update($request->all());
        return ['message' => 'Updated the school info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        //$this->authorize('isAdmin');

        $school = School::findOrFail($id);
        // delete the user

        $school->delete();

        return ['message' => 'School Deleted'];
    }

    public function search(){

        if ($search = \Request::get('q')) {
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users = User::latest()->paginate(5);
        }

        return $users;

    }
     public function dashboard(){
        //  $this->authorize('isAdmin');
        $user_count=count(User::all());
        $student_count=count(Student::all());
        $staff_count=count(Staff::all());
        $level_count=count(Level::all());
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

 public function setSchool($school_id)
{ $user_id=auth('api')->user()->id;
    $user=User::findOrFail($user_id);
    $user->school_id=$school_id;
    $user->save();
}


public function generateRegLink(Request $request,$id){

                $school=School::findOrFail($id);

                if(!empty($school->admission_link)){
                    return $school->admission_link;
                }
                  else{
                   $url=$request->getSchemeAndHttpHost();
                  //  $linkArr=explode('/',$url);
                //     array_pop($linkArr);
                //    $url =implode('/',$linkArr);
                    $reg_link=AppUtils::generateRandomString(40);;
                     $url=$url.'/'.'register/'.$reg_link;
                    $school['admission_link']=$url;
                    $school->save();
             return $url;
                  }


               //return $request->url();
}


}

