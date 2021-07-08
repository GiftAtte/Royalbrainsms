<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CrecheDomain;
use App\Crechesubdomain;
class SubDomainController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index( $domain_id=null)
   {
    if(!empty($domain_id)){
        return  Crechesubdomain::with('domains')->where([['school_id',auth('api')->user()->school_id],['domain_id',$domain_id]])->latest()->get();
    }else{
       return Crechesubdomain::with('domains')->where('school_id',auth('api')->user()->school_id)->latest()->get();
    }}

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'domain_id' => 'required'
        ]);

         return  Crechesubdomain::create([
                'name'=>$request->name,
                'domain_id'=>$request->domain_id,
                'school_id'=>auth('api')->user()->school_id
            ]);

}

public function destroy($id)
{
   $domain= Crechesubdomain::findOrFail($id);
   $domain->delete();
}



public function update(Request $request)
{
   $domain= Crechesubdomain::findOrFail($request->id);
   $domain->update([
       'name'=>$request->name
   ]);
}
}
