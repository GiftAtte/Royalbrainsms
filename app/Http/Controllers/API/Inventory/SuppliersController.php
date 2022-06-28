<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Supplier;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
 
    public function index(){
        return Supplier::where('school_id',auth('api')->user()->school_id)
                        ->orderBy('name')
                        ->get();
    }

    public function createSupplier(Request $request){
        
          $request->validate([
            'name'=>'required|string',
            'phone'=>'required|string',
            'products'=>'required|string',
        ]);
        $data=$request->all();
        $data['school_id']=auth('api')->user()->school_id;
              return Supplier::create($data);
    }


    public function update(Request $request){
        $request->validate([
            'name'=>'required|string',
            'phone'=>'required|string',
            'products'=>'required|string',
        ]);
        return Supplier::where('id',$request->id)->update($request->all());
    }


    public function delete($id){
        Supplier::where('id',$id)->delete();
        return;
    }
}
