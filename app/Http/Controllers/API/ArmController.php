<?php

namespace App\Http\Controllers\API;
use App\Arm;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Arm::where('school_id',auth('api')->user()->school_id)->latest()->paginate(10);
    }

  
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required']);
            Arm::create([
                'name'=>$request->name,
                'nike_name'=>$request->nike_name,
                'school_id'=>auth('api')->user()->school_id
            ]);


    }

    
    public function update(Request $request, $id)
    {
     $arm=Arm::findOrFail($id);
       $arm->school_id=auth('api')->user()->school_id;
       $arm->name=$request->name;
       $arm->nike_name=$request->nike_name;
       $arm->save();
    }

    public function destroy($id)
    {
        //
        $arm=Arm::findOrFail($id);
        $arm->delete();

    }
}
