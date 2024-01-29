<?php

namespace App\Http\Controllers\API\Pos;

use App\Http\Controllers\API\Traits\AppUtility;
use App\Http\Controllers\API\Traits\InventoryTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockRequest;
use App\Models\Pos\Product;
use App\Models\Pos\Stock;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller

    {use AppUtility,InventoryTrait;




    
        public function index()
        {
            $stocks=Stock::with('product')->latest()->get();
            return $this->appResponse(['stocks'=>$stocks]);
        }
    


        public function store(StockRequest $request)
        {   
          
     
            
            try{
                DB::beginTransaction();
                $validatedData=$request->validated();
                $validatedData['created_by']=$this->currentUser();
                $validatedData['school_id']=$this->school();
                $qty=$this->getSkuQty($request->quantity);
                $stock=null;
                if($request->has('destock')){
                    $validatedData['quantity']=$validatedData['quantity']*-1;
                    Stock::create($validatedData); 
                    $this->updateStock($request->product_id,0,abs($qty));
                    $this->updatePrice($request->product_id,$request->unit_price);
                }else{
                    Stock::create($validatedData);
                    $this->updateStock($request->product_id,$qty);
                    $this->updatePrice($request->product_id,$request->unit_price);

                }
                DB::commit();
                return $this->appResponse(['stock'=>$stock,'message'=>'created successfullly'],201);;
            }catch(Exception $e){
                return $this->appResponse(['message'=>$e->getMessage()],500);;
            }
           
           
        }
    



        public function show(Stock $stock)
        {
            return $this->appResponse(['stock'=>$stock]);
        }
    

       
        public function update(StockRequest $request, Stock $stock)
        {  
            //  $validatedData=$request->validated();
        //     $validatedData['created_by']=$this->currentUser();
        //     $validatedData['expiring_date']=Carbon::create($request->expiring_date); 
        //     $validatedData['quantity']=$this->getPiecesQtyOf($request->product_id,$request->bulk_qty,$request->piece_qty);
           
           
        DB::beginTransaction();
        try{
            $validatedData=$request->validated();
            $validatedData['created_by']=currentUser();
          
             $qty=$this->getSkuQty($request->quantity);
            $stk=null;
            if($request->has('destock')){
                $validatedData['quantity']=$validatedData['quantity']*-1;
                $stock->update($validatedData); 
                $this->updateStock($request->product_id,0,abs($qty));
                $this->updatePrice($request->product_id,$request->unit_price);
            }else{
                $stock->update($validatedData);
                $this->updateStock($request->product_id,$qty);
                $this->updatePrice($request->product_id,$request->unit_price);

            }
            DB::commit();
            return $this->ppResponse(['stock'=>$stock,'message'=>'created successfullly'],201);;
        }catch(Exception $e){
            return $this->ppResponse(['message'=>$e->getMessage()],500);;
        }
       
            
        }

         
        public function destroy(Stock $stock)
        {   
            
            $stock->delete();   
            return $this->appResponse(['message' => 'Stock deleted successfully'],203);   
            // DB::beginTransaction();
            // try{
            //     $this->updateStock($stock->product_id,0,$stock->quantity);
            //     $stock->delete();
            //     DB::commit();
            //    return $this->appResponse(['message' => 'Stock deleted successfully'],203);
            // }
            //     catch(Exception $e){
            //   return $this->AppResponse(['message'=>$e->getMessage()],500);;
            //     } 

        }
    


    // function getActualQuantity($bulkQty=0,$pieceQty=0,$stock_id) {
    //                $actualtity=$pieceQty;
    //                $stock=stock::find($stock_id);
    //                $qtyPerBulk=$stock->pieces_per_bulk;
    //                if($bulkQty>0){
    //                 $actualtity=($qtyPerBulk*$bulkQty)+$pieceQty;
    //                }
    //                return $actualtity;

    // }
    

         
         public function getAllStocks()  {
            
             $products=Product::all();
             $productsCollection=collect($products);
             
            $availableStock=$productsCollection-> where('stock','>',0)->sortKeys()->all();
             $outOfStock=$productsCollection->where('stock','<=',0)->sortKeys()->all();
              return $this->appResponse([
                'availableStock'=>$availableStock,
                'outOfStock'=>$outOfStock,
                'message'=>'success'
         ]);

         }


         
         public function getExpiredStock(){
           
            $currentDate = Carbon::now();
            $expired_drugs=Stock::with('product')->whereDate('expiring_date','<=',$currentDate)->get();
            return $this->appResponse(['expired_drugs'=>$expired_drugs]);
         }




public function updatePrice($product_id,$unitPrice =[]){

if(count($unitPrice)>0){
    return Product::where('id',$product_id)->update([
        'unit_price'=>$unitPrice
    ]);
}
    }



}
    