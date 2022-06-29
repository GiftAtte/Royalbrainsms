<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Purchase;
use App\SaleDetails;
use App\Sales;
use App\Stock;
use Illuminate\Http\Request;


class StockController extends Controller
{




     public function updateStock($purchased_id,$product_id,$quantity){
             // return $product_id;
             $product=Stock::where([['product_id',$product_id],['purchase_id',$purchased_id]])->first();
             if(!empty($product)){
             $quantityDiff=$product['last_added_quantity']-$quantity;
             // Decuction
            if($quantityDiff>0){

                 // $product['available_quantity']=$product['available_quantity']-abs($quantityDiff);
                  $product['update_type']='DEDUCTION_UPDATE';
                  $product['last_added_quantity']=$quantity;
                }else{
               // $product['available_quantity']=$product['available_quantity']-$quantityDiff;
                $product['update_type']='INCREASE_UPDATE';
                 $product['last_added_quantity']=$quantity;
            }
        }
            else{
                $product=Stock::where('product_id',$product_id)->first();
                $product['update_type']='NEW_STOCK_ADDED';

        }

                $currentStock=$this->getCurrentStock($product_id);
                $product['available_quantity']=$currentStock;
                $product['status']=$currentStock>0?'IN STOCK':'OUT OF STOCK';
                $product['last_added_quantity']=$quantity;
                $product['employee_id']=$this->employee_id();
                return $product->save();

     }
     public function addNewProduct($purchase=[]){
            //return$purchase;
         if(count($purchase)>0){
            //return$purchase['quantity'];
             $quantity=$purchase['quantity'];
             $purchase['available_quantity']=$quantity;
             $purchase['last_added_quantity']=$quantity;
             $purchase['employee_id']=$this->employee_id();
             $purchase['school_id']=$this->school_id();
             $purchase['update_type']='NEW_STOCK_ADDED';
             return Stock::create($purchase);
         }
        return ['error'=>'Empty stock is not allowed'];
     }
     public function makeSale($product_id,$quantitySold){
          $product=Stock::where('product_id',$product_id)->first();
           $product['available_quantity']=$product['available_quantity']-$quantitySold;
           $product['last_quantity_sold']=$quantitySold;
           $product['employee_id']=$this->employee_id();
           $product['update_type']='SALES_UPDATE';
           $product->save();
        //Stock::where('product_id',$product_id)->update($product);
     }
     public function getAllStock(){

        return Stock::where('stocks.school_id',$this->school_id())
                     ->join('products','stocks.product_id','=','products.id')
                     ->join('product_prices','stocks.product_id','=','product_prices.product_id')
                     ->select('stocks.*','products.name','product_prices.selling_price as price')
                     ->get();
     }
     public function getStockById($id){
        return Stock::findOrFail($id);
     }
     public function getStockByProduct($product_id){
        return Stock::where('product_id',$product_id)->first();
     }

     public function employee_id (){
        return auth('api')->user()->employee_id?auth('api')->user()->employee_id:0;
     }
     public function school_id (){
        return auth('api')->user()->school_id;
     }



     public function getCurrentStock($product_id){
               $totalSales=SaleDetails::where('product_id',$product_id)
                           ->sum('quantity');

                $totalPurchase=Purchase::where('product_id',$product_id)
                           ->sum('quantity');
                           return $totalPurchase-$totalSales;

     }
}
