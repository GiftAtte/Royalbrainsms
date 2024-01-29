<?php

use App\Models\Academics\AcademicSession;
use App\Models\Academics\Guardian;
use App\Models\Academics\Level;
use App\Models\Academics\Student;
use App\Models\Academics\Term;
use App\Models\Academics\ZoomAuth;
use App\Models\HRM\Employee;
use App\Models\HRM\School;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// APP RESPONSE
if (!function_exists('appResponse')) {
       function appResponse($data,$reponseCode=200){
        return response()->json(['data'=>$data],$reponseCode);
   }
}

// CURRENT USER "id"

if (!function_exists('currentUser')) {
        function currentUser(){
                return Auth::id();
           }
}
  
// CURRENT SESSION
if (!function_exists('currentSession')) {
        function currentSession(){
                return AcademicSession::where('current',1)->first();
           }
}

// CURRENT TERM
if (!function_exists('currentTerm')) {
        function currentTerm(){
                return Term::where('current',1)->first();
           }
}
// CURRENT AUTHENTICATED STUDENT
if (!function_exists('currentStudent')) {
        function currentStudent(){
                return Student::where('user_id',currentUser())->first();
           }
}


// CURRENT AUTHENTICATED PARENT
if (!function_exists('currentGuardian')) {
        function currentGuardian(){
                return Guardian::with('wards','user')->where('user_id',currentUser())->first();
           }
}

// CURRENT EMPLOYEE
if (!function_exists('currentEmployee')) {
        function currentEmployee(){
                return Employee::where("user_id",currentUser())->first();
           }
}
// ACTIVE STUDENTS
if (!function_exists('activeStudents')) {
        function activeStudents(){
                return Student::where('is_active',1)->all();
           }
}
// ACTIVE EMPLOYEES
// if (!function_exists('activeEmployees')) {
//         function activeEmployees(){
//                 return Employee::where('is_active',1)->first();
//            }
// }



if (!function_exists('currentLevels')) {
        function currentLevels(){
                return Level::where('is_history',0)->latest()->get();
           }

           
}


if (!function_exists('school')) {
        function school(){
                return School::first();
           }

           
}

if (!function_exists('userHasRole')) {
        function userHasRole($role){

                $user=User::find(Auth::id());
                return $user->hasRole($role);
           }

           
}


if (!function_exists('userHasAnyRole')) {
            function userHasAnyRole(array $roles){
                $user=User::find(Auth::id());
                return $user->hasAnyRole(...$roles);
           }
        }

        if(!function_exists('options')){
                function options(){
                        return [
                                ["id"=>0,"option"=>'A'],
                                ["id"=>1,"option"=>'B'],
                                ["id"=>2,"option"=>'C'],
                                ["id"=>3,"option"=>'D'],
                                ["id"=>4,"option"=>'E'],
                                ["id"=>5,"option"=>'F'],
                                ["id"=>6,"option"=>'G'],
                                ["id"=>7,"option"=>'H'],
                                ["id"=>8,"option"=>'I'],
                                ["id"=>7,"option"=>'J'],
                                ["id"=>10,"option"=>'K'],
                                ["id"=>11,"option"=>'L'],
                                ["id"=>12,"option"=>'M'],
                                ["id"=>13,"option"=>'N'],
                                ["id"=>14,"option"=>'O'],
                                ["id"=>15,"option"=>'P'],
                                ["id"=>16,"option"=>'Q'],
                                ["id"=>17,"option"=>'R'],
                                ["id"=>18,"option"=>'S'],
                                ["id"=>19,"option"=>'T'],
                                ["id"=>20,"option"=>'U'],
                                ["id"=>21,"option"=>'V'],
                                ["id"=>22,"option"=>'W'],
                                ["id"=>23,"option"=>'X'],
                                ["id"=>24,"option"=>'Y'],
                                ["id"=>25,"option"=>'Z'],
                                ["id"=>26,"option"=>'NO option SELECTED']
                        ];
                }
                
        }

        if (!function_exists('zoomCredentials')) {
                function zoomCredentials(){  
                        return ZoomAuth::first();
                   }
        
                   
        }