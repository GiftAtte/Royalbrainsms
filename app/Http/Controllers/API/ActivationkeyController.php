<?php

namespace App\Http\Controllers\API;

use App\Activation_key;
use App\Http\Controllers\Controller;
use App\Result_activation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ActivationkeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {//return $request->all();
         $this->validate($request,[
        'report_id' => 'required',
         'number_of_keys'=>'required',

    ]);

      $school_id=auth('api')->user()->school_id;
    for ($i=0;$i<$request->number_of_keys;++$i){
        Activation_key::create([
             'report_id'=>$request->report_id,
             'school_id'=>$school_id,
             'activation_key'=>$this->generateRandomString(6),


        ]);

    }
    $activation_keys=$this->exportActivation($request->report_id,$request->number_of_keys);
    return response()->json(['activation_key'=>$activation_keys]);
    }


public function exportActivation($report_id,$number){

   $activation_keys= Activation_key::with(['report','school'])->where('report_id',$report_id)
   ->limit($number)->latest()->get();

    return $activation_keys;
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function activateResult(Request $request)
    {   $already_used=false;
        $invalid_key=false;
      $activation_key=Activation_key::where('activation_key',$request->activation_key)->first();
      if($activation_key){

        $activation_status=Result_activation::where('activation_id',$activation_key->id)->first();
        if($activation_status){
            $already_used=true;
            return ['already_used'=>$already_used,'invalid_key'=>$invalid_key];
        }else{
            Result_activation::create([
                'student_id'=>auth('api')->user()->student_id,
                'report_id'=>$request->report_id,
                'activation_status'=>1,
                'activation_id'=>$activation_key->id,
                     'comment'=>'activated'


            ]);
            return ['success'=>'result activated successfully'];
        }
      }else{
          $invalid_key=true;
          return['already_used'=>$already_used,'invalid_key'=>$invalid_key];
      }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public  function generateRandomString($length = 10) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
