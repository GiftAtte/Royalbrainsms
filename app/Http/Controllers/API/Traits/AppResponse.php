<?php
namespace App\Http\Controllers\API\Traits;

use Illuminate\Support\Facades\Auth;

trait AppResponse{
    public function appResponse($data,$reponseCode=200){
        return response()->json(['data'=>$data],$reponseCode);
   }




// CURRENT USER "id"


   public  function currentUser(){
             return Auth::id();
        }

}

