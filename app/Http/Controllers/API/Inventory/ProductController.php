<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Product;
use App\Purchase;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Inventory\StockController;
use App\Stock;

class ProductController extends Controller
{



    public function index(){
        return Product::where('products.school_id',auth('api')->user()->school_id)
                   ->join('categories','products.category_id','=','categories.id')
                   ->select('products.*','categories.name as category')
                   ->orderBy('category')
                   ->orderBy('created_at')
                   ->get();
    }

    public function createProduct(Request $request){

         $request->validate([
            'name'=>'required|string',
            'category_id'=>'required|integer',
        ]);
        $data=$request->all();
        $data['school_id']=auth('api')->user()->school_id;
              return Product::create($data);
    }


    public function update(Request $request){
         $request->validate([
            'name'=>'required|string',
            'category_id'=>'required|integer',
        ]);
        return Product::where('id',$request->id)->update($request->all());
    }


    public function delete($id){
        Product::where('id',$id)->delete();
        return;
    }


    public function purchasedProduct(Request $request){

      $request->validate([
            'product_id'=>'required',
            'quantity'=>'required',
            'total_cost'=>'required',
            'purchased_date'=>'required|date',

        ]);
          $data=$request->all();
          $data['unit_cost']=round((floatval($request->total_cost)/floatval($request->quantity)),2);
          $data['school_id']=auth('api')->user()->school_id;

                     $stockManager=new StockController();
                    $purchase=Purchase::updateOrCreate(['id'=>$request->id,'product_id'=>$request->product_id],$data);
                      $check=Stock::where('product_id',$request->product_id)->first();

                      if(!empty($check )){
                    return $stockManager->updateStock($request->id,$request->product_id,$request->quantity);
                    }
                      $newPurchase=$purchase->toArray();
                      $newPurchase['purchase_id']=$purchase->id;
                     // return 'new here';
                    return  $stockManager->addNewProduct($newPurchase);
    }


     public function getPurchases(){

        return Purchase::where('purchases.school_id',auth('api')->user()->school_id)
                   ->join('products','purchases.product_id','=','products.id')
                    ->join('categories','products.category_id','=','categories.id')
                   ->select('purchases.*','categories.name as category','products.name as product')
                   ->orderBy('purchased_date')
                   ->orderBy('category')
                   ->get();
     }


      public function deletePurchased($id){
        Purchase::where('id',$id)->delete();

        return;
    }
}
