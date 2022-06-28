<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use App\ProductPrice;



class PriceController extends Controller
   {
    
    public function index(){
   
        return Product::where('products.school_id',auth('api')->user()->school_id)
                   ->join('categories','products.category_id','=','categories.id')
                   ->join('product_prices','product_prices.product_id','=','products.id')
                   ->select('products.*','product_prices.cost_price as cost_price','product_prices.selling_price as selling_price','categories.name as category')
                   ->orderBy('name')
                   ->get();
    }

    public function createPrice(Request $request){
        
        $request->validate([
            'product_id'=>'required',
            'selling_price'=>'required',
        ]);
        $data=$request->all();
        $data['school_id']=auth('api')->user()->school_id;
              return ProductPrice::create($data);
    }


    public function update(Request $request){
           $request->validate([
            'product_id'=>'required',
            'selling_price'=>'required',
        ]);
        return ProductPrice::where('id',$request->id)->update($request->all());
    }


    public function delete($id){
        ProductPrice::where('id',$id)->delete();
        return;
    }
}
