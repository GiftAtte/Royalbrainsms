<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SMS;

class SmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function deleteMessageApi($id)
    {
       SMS::findOrFail($id)->delete();
        return 'success';
    }

    public function MessageApi()
    {

        return ['sms'=>SMS::where('school_id',auth('api')->user()->school_id)->latest()->get(),
                'is_active'=>SMS::where('school_id',auth('api')->user()->school_id)->where('is_active',1)->first()
    ];
    }

    public function createMessageApi(Request $request)
    {
        $this->validate($request,[
            'api_key' => 'required',
            'email' => 'required',
        ]);
       return SMS::create([
           'api_key'=>$request->api_key,
           'email'=>$request->email,
           'is_active'=>$request->is_active?$request->is_active:0,
           'school_id'=>auth('api')->user()->school_id,

       ]);
    }


    public function updateMessageApi(Request $request)
    {
        //

        $this->validate($request,[

            'api_key' => 'required|string',
            'email' => 'required',



        ]);
        $report=SMS::findOrFail($request->id);
        $report->update($request->all());
        return ['message'=>'report updated successfully'];
    }

}
