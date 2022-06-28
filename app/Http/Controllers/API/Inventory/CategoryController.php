<?php

namespace App\Http\Controllers\API\Inventory;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return Category::where('school_id',auth('api')
                   ->user()->school_id)->latest()->get();
    }

    public function createCategory(Request $request){
        
        $data=$request->validate([
            'name'=>'required|string',
            'type'=>'required|string',
        ]);
        $data['school_id']=auth('api')->user()->school_id;
              return Category::create($data);
    }


    public function update(Request $request){
           $request->validate([
            'name'=>'required|string',
            'type'=>'required|string',
        ]);
        return Category::where('id',$request->id)->update($request->all());
    }


    public function delete($id){
        Category::where('id',$id)->delete();
        return;
    }
}
