<?php

namespace App\Http\Controllers;

use App\SchoolTemplate;
use App\Template;
use Illuminate\Http\Request;
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

    public function getSchoolTemplate(){
        $templates=DB::table('school_templates')-> where('school_id',auth('api')->user()->school_id)
                     ->join('templates', 'school_templates.template_id','=','templates.id')
                     ->selec('templates.id as id','templates.name as name')
                     ->get();
                     return $templates;
    }


    public function createSchoolTemplate(Request $request){
              $this->validate($request,[
            'template_id' => 'required|integer',
        ]);

        $chool_id=auth('api')->user()->school_id;
           foreach($request->templates as $template_id){

        $check=SchoolTemplate::where('template_id',$request->template_id)
              ->first();

              if(empty($check)){
                 SchoolTemplate::create([
                     'school_id'=>$chool_id,
                     'template_id'=>$template_id
                 ]);
              }
}}


public function deleteSchoolTemplate($id){
        SchoolTemplate::where([['template_id',$id],['school_id',auth('api')->user()->school_id]])
                  ->delete();

                  return '';
}
}


