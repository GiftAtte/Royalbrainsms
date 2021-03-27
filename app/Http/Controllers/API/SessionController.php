<?php

namespace App\Http\Controllers\API;
use App\Sessions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SessionController extends Controller
{
 
     public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        //
        return Sessions::where('school_id',auth('api')->user()->school_id)->latest()->paginate(10);
    }

    public function store(Request $request)
    {
        return Sessions::create([
          'name'=>$request->name,
          'school_id'=>auth('api')->user()->school_id
      ]);
    }

  
    public function update(Request $request)
    {

          $sessions=Sessions::findOrFail($request->id);
          $sessions->update($request->all());
    }

    public function destroy($id)
    {
        $sessions=Sessions::findOrFail($id);
        $sessions->delete();
    }
}
