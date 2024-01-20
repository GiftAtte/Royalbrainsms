<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CrecheRating;
use App\CrechestudentDomain;
class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }



     public function index()
    {
       return CrecheRating::where('school_id',auth('api')->user()->school_id)->latest()->get();
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'rate' => 'required']);

         return  CrecheRating::create([
                'rate'=>$request->rate,
                'school_id'=>auth('api')->user()->school_id
            ]);

}

public function destroy($id)
{
   $domain= CrecheRating::findOrFail($id);
   $domain->delete();
}



public function update(Request $request)
{
   $domain= CrecheRating::findOrFail($request->id);
   $domain->update([
       'rate'=>$request->rate
   ]);
}


public function createRating(Request $request){
    $students=$request->number_of_students;
    //$report=Report::findOrFail($request->report_id);
   for ($i=0;$i<$students;++$i){
    CrechestudentDomain::where([['report_id',$request->report_id],['student_id',$request->student_id[$i]],['subdomain_id',$request->subdomain_id]])->delete();
    if(!empty($request->rate_id[$i])){
    CrechestudentDomain::create(

       [
       'student_id'=>$request->student_id[$i],
       'report_id'=>$request->report_id,
       'rating_id'=>$request->rate_id[$i],
       'subdomain_id'=>$request->subdomain_id,
       'domain_id'=>$request->domain_id
       ]);}
       
}
return 'success';
}

}
