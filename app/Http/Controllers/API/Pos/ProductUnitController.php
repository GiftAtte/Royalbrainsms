<?php

namespace App\Http\Controllers\API\Pos;

use App\Http\Controllers\API\Traits\AppUtility;
use App\Http\Controllers\Controller;
use App\Models\Pos\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductUnitController extends Controller

    { use AppUtility;
   

        public function __construct()
        {
            
         
        }



      
        public function index()
        {
            $product_units=ProductUnit::all();
            return $this->appResponse(['product_units'=>$product_units]);
        }
    

        public function store(Request $request)
        {   
        
           $request->validate([
               'name'=>'required|string' 
            ]);
        $units=explode(',',$request->name);
        $creator=$this->currentUser();
        foreach ($units as $key => $unit) {
            $createdUnits[]= ProductUnit::create([
                'name'=>$unit,
                'created_by'=>$creator,
                'school_id'=>$this->school()
            ]);
        }


            return $this->appResponse(['product_units'=>$createdUnits,'message','Created successfully'],201);;
        }
    


        public function show(ProductUnit $ProductUnit)
        {
            return AppResponse(['ProductUnit'=>$ProductUnit]);
        }
         


        public function update(Request $request, ProductUnit $ProductUnit)
        {
            $request->validate([
                'name'=>'required|string' 
             ]);
            $validatedData['created_by']=$this->currentUser();
            $validatedData['school_id']=$this->school();
            $validatedData['name']=$request->name;
            $ProductUnit->update($validatedData);
            return $this->appResponse(['ProductUnit'=>$ProductUnit,'message'=>'Updated successfully']);
        }
    



   
        public function destroy(ProductUnit $ProductUnit)
        {
            $ProductUnit->delete();
            return $this->appResponse(['message' => 'ProductUnit deleted successfully'],203);
        }
    }
    