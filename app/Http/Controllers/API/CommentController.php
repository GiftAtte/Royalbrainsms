<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comment;
class CommentController extends Controller
{

   public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {

        return Comment::where('school_id',auth('api')->user()->school_id)
        ->latest()->paginate(10);
    }

    public function store(Request $request)
    {


        $this->validate($request,[


            'comment' => 'required',

        ]);
        return Comment::create([
            'upper_bound' => $request->upper_bound,
            'upper_bound' => $request->lower_bound,
            'comment' => $request->comment,

            'school_id' => auth('api')->user()->school_id,
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request,[

             'comment' => 'required',


        ]);
       $grading=Comment::findOrFail($request->id);
       $grading->update($request->all());
    }


    public function destroy($id)
    {
       $grading=Comment::findOrFail($id);
       $grading->delete();
    }
}
