<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function Register(){

    }
      public function login(Request $request){
        $loginData=$request->validate([
             'email'=>'email|required',
             'password'=>'required'
         ]);
         if(!auth()->attempt($loginData)){
return response(['message'=>'invalid credentials']);

         }else{
             $access_token=auth()->user()->createToken('authToken')->accessToken;
             return response(['user'=>auth()->user(),'access_token'=>$access_token]);
         }
    }
}
