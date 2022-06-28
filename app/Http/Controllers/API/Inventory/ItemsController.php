<?php

namespace App\Http\Controllers\API\Inventory;

use App\Items;
use App\Http\Controllers\Controller;
use App\Stock;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
   
    public function index(){
        return Items::where('school_items.school_id',auth('api')->user()->school_id)
                   ->join('categories','school_items.category_id','=','categories.id')
                   ->select('school_items.id as id','school_items.name as name','categories.name as category','school_items.created_at as date')
                   ->orderBy('category')
                   ->orderBy('date')
                   ->get();
    }

    public function createItem(Request $request){
        
        $data=$request->validate([
            'name'=>'required|string',
            'category_id'=>'required|string',
        ]);
        $data['school_id']=auth('api')->user()->school_id;
              return Items::create($data);
    }


    public function update(Request $request){
           $request->validate([
            'name'=>'required|string',
            'category_id'=>'required|string',
        ]);
        return Items::where('id',$request->id)->update($request->all());
    }


    public function delete($id){
        Items::where('id',$id)->delete();
        return;
    }
}
