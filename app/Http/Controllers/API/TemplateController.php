<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\School;
use App\SchoolTemplate;
use Illuminate\Http\Request;
use App\Template;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return Template::all();
    }




    public function store(Request $request)
    {
        Template::create($request->all());
    }




    public function update(Request $request)
    {
        Template::where('id',$request->id)->update($request->all());
    }


    public function destroy($id)
    {
        Template::where('id',$id)->delete();
    }


    public function loadSchoolTemplates($school_id=null){
        if(!empty($school_id)){
        $templates=DB::table('school_templates')->where('school_id',$school_id)
                     ->join('templates', 'school_templates.template_id','=','templates.id')
                     ->select('school_templates.id as id','templates.name as name')
                     ->get();

          $school=School::findOrFail($school_id);
                     return ['templates'=>$templates,
                              'school' =>$school];
    }

return DB::table('school_templates')->where('school_id',auth('api')->user()-> school_id)
                     ->join('templates', 'school_templates.template_id','=','templates.id')
                     ->select('school_templates.id as id','templates.name as name')
                     ->get();
}

    public function createSchoolTemplate(Request $request){


           foreach($request->templateIds as $template){

        $check=SchoolTemplate::where([['template_id',$template['id']],['school_id',$request->school_id]])
              ->first();

              if(empty($check)){
                 SchoolTemplate::create([
                     'school_id'=>$request->school_id,
                     'template_id'=>$template['id']
                 ]);
              }
}}


public function deleteSchoolTemplate($id){
        SchoolTemplate::where('id',$id)->delete();


                  return '';
}


}
