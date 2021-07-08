<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CrecheDomain;
class DomainController extends Controller
{  public function __construct()
    {
        $this->middleware('auth:api');
    }



     public function index()
    {
       return CrecheDomain::where('school_id',auth('api')->user()->school_id)->latest()->get();
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required']);

         return   CrecheDomain::create([
                'name'=>$request->name,
                'school_id'=>auth('api')->user()->school_id
            ]);

}

public function destroy($id)
{
   $domain= CrecheDomain::findOrFail($id);
   $domain->delete();
}



public function update(Request $request)
{
   $domain= CrecheDomain::findOrFail($request->id);
   $domain->update([
       'name'=>$request->name
   ]);
}
}
