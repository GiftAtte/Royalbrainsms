<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CrecheRating;
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
}
