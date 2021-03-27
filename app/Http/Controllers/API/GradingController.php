<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Grading;
use App\Result_config;
use App\Config_status;
class GradingController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {

        return Grading::with('gradinggroups')->where('school_id',auth('api')->user()->school_id)
        ->latest()->paginate(10);
    }

    public function store(Request $request)
    {


        $this->validate($request,[
            'lower_bound' => 'required',
            'group_id' => 'required',
            'upper_bound' => 'required',
            'grade' => 'required',
            'narration' => 'required',

        ]);
        return Grading::create([
            'lower_bound' => $request->lower_bound,
            'upper_bound' => $request->upper_bound,
            'narration' => $request->narration,
            'grade' => $request->grade,
            'credit_point'=>$request->credit_point,
            'group_id'=>$request->group_id,
            'school_id' => auth('api')->user()->school_id,
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'group_id' => 'required',
            'lower_bound' => 'required',
            'upper_bound' => 'required',
            'grade' => 'required',
            'narration' => 'required',

        ]);
       $grading=Grading::findOrFail($request->id);
       $grading->update($request->all());
    }


    public function destroy($id)
    {
       $grading=Grading::findOrFail($id);
       $grading->delete();
    }

    public function resultConfig(){
        $school_id=auth('api')->user()->school_id;
        $check=count(Config_status::where('school_id',$school_id)->get());
        if($check>0){
        return \DB::table('result_configs')
        ->leftJoin('config_statuses', function($join) use($school_id)
        {
            $join->on('result_configs.id','=','config_statuses.result_config_id')
            ->where('config_statuses.school_id',$school_id);
        })->select(\DB::raw('result_configs.id as id,result_configs.name as name, config_statuses.status as status'))
        ->orderby('id')->get();






    }
    return Result_config::all();
}



    public function saveConfig(Request $request){

       $options= $request->settings;
       foreach ($options as $option) {



   $config=  Config_status::updateOrInsert(['school_id'=>auth('api')->user()->school_id,
                                      'result_config_id'=>$option['id']],
        [
          'status'=>$option['status'],
          'result_config_id'=>$option['id'],
          'school_id'=>auth('api')->user()->school_id
        ]);
    }
    return ['message'=>'configuration saved Successfully'];
}
}
