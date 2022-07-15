<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\API\Utils\AppUtils;
use App\Http\Controllers\Controller;
use App\Model\Candidate;
use App\Providers\RouteServiceProvider;
use App\School;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;


      public function showRegistrationForm( Request $request,$url)
    {
        //return$request->url();
        if(!empty($url)){
           $school= School::where('admission_link',$request->url())->first();
            return view('auth.registerSchool',['school'=>$school]);
          }

        return view('auth.registerSchool');
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {


        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }



    public function admissionRegistration(Request $request){

         $request->validate([
            'first_name'=>'required|string|max:191',
            'surname'=>'required|string|max:191',
            'email'=>'required|email',
            'password' =>'required|string|min:6|confirmed',
            'school_id'=>'required'

         ]);


        $data=[];
      // $student =Candidate::create($data);
      $lastRegNo=User::where('school_id',$request->school_id)
             ->max('reg_number') ;
             if($lastRegNo>0){
                if($lastRegNo<10){
                    $data['portal_id']='PST-0'.$lastRegNo;
                    $data['reg_number']=$lastRegNo+1;
                }else{
                     $data['portal_id']='PST-'.$lastRegNo;
                     $data['reg_number']=$lastRegNo+1;
                }
             }else{
                 $data['portal_id']='PST-01'.$lastRegNo;
                 $data['reg_number']=1;
             }

        $data['name']=$request->surname.' '.$request->first_name;
        $data['email']=strtolower($request->email);
        $data['password']=Hash::make(($request->password));
        $data['type']='candidate';
        $data['phone']=$request->phone;
        $data['school_id']=$request->school_id;
       // return $data;
        $user=User::create($data);
        if($user){
         return  redirect(route('login'))->with('message','user created successfully');
        }

          return back()->with('message','there was error,please your inputs ');
}


}
