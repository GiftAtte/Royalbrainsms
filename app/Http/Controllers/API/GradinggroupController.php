<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Grading;
use App\Result_config;
use App\Config_status;
use App\Gradinggroup;

class GradinggroupController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {

        return Gradinggroup::where('school_id',auth('api')->user()->school_id)
        ->latest()->paginate(10);
    }

    public function store(Request $request)
    {


        $this->validate($request,[
            'group_name' => 'required',


        ]);
        return Gradinggroup::create([

            'group_name' => $request->group_name,
            'school_id' => auth('api')->user()->school_id,
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'group_name' => 'required',


        ]);
       $grading=Gradinggroup::findOrFail($request->id);
       $grading->update($request->all());
    }


    public function destroy($id)
    {
       $grading=Gradinggroup::findOrFail($id);
       $grading->delete();
    }
    public function getGradinggroup()
    {
       return Gradinggroup::where('school_id',auth('api')->user()->school_id)->get();
    }

}
