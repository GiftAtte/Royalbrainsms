<?php
namespace App\Http\Controllers\API\Traits;

use App\Models\Pos\Product;
use Illuminate\Support\Arr;

trait InventoryTrait
{

public function updateStock($product_id, $increaseByQty=0,$reduceByQuantity=0){
    
    $reduceByQuantity=$reduceByQuantity*-1;
    $updateQtyquantity=$increaseByQty+$reduceByQuantity;
    $product=Product::find($product_id);
    $product->stock=$product->stock+$updateQtyquantity;
    return $product->save();
    
}

public function getSkuQty($quntity=[]) {
    $updateQuantity=array_reduce($quntity,function($sum,$qtyObj){
           return $sum+($qtyObj['value']*$qtyObj['skuQtyPerUnit']);
    });
    return$updateQuantity;
}



                public function addOrReduceStockBy(){
                    
                }

}