<?php

namespace App\Http\Controllers\API\Pos;

use App\Http\Controllers\API\Traits\AppUtility;
use App\Http\Controllers\API\Traits\InventoryTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\SalesRequest;
use App\Models\Pos\Product;
use App\Models\Pos\Sales;
use App\Models\Pos\SuspendedSale;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{use AppUtility, InventoryTrait;
   


    public function __construct()
    {
        // Apply the 'check.permissions' middleware to specific controller methods
        $this->middleware('check.permissions:create-sales', ['only' => ['store']]);
        $this->middleware('check.permissions:update-sales', ['only' => ['update']]);
        $this->middleware('check.permissions:delete-sales', ['only' => ['delete']]);
        $this->middleware('check.permissions:view-sales', ['only' => ['show','index']]);
    }


     /**
        * @OA\Get(
        * path="/api/sales",
        * operationId="Get All sales",
        * tags={"Sales"},
        * summary="Get All sales",
        * security={{ "bearer_token": {} }},
        * description="Get all sales", 
        *      @OA\Response(response=200,description="success", @OA\JsonContent()),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function index()
    {
        $sales=Sales::with('salesAgent')->latest()->paginate('1000');
        return appResponse(['sales'=>$sales,'message'=>'success']);
    }

   

    
    /**
        * @OA\Post(
        * path="/api/sales",
        * operationId="Make sales",
        * tags={"Sales"},
        * summary="Make New sales",
        * security={{ "bearer_token": {} }},
        * description="Make New sales",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"product_details","payment_type","amount"},
        *               @OA\Property(property="product_details", type="[{id:number,name:text,pieces_qty:number,bulk_qty:number,amount:number}]"),
        *               @OA\Property(property="customer_name", type="text"),
        *               @OA\Property(property="amount", type="number"),
        *               @OA\Property(property="payment_type", type="text"),
        *               @OA\Property(property="discount", type="number"),

        *              
        *            ),
        *        ),
        *    ),
        *      @OA\Response(response=201,description="success",@OA\JsonContent()),
        *      @OA\Response(response=200,description="success", @OA\JsonContent()),
        *      @OA\Response(response=422,description="Unprocessable Entity", @OA\JsonContent()),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function store(SalesRequest $request)
    {
   DB::beginTransaction();
   try{
    $productDetails=$request->product_details;
    $productDetailsCollection=new Collection($productDetails);
    $validatedData=$request->validated();

    $validatedData['sales_agent']=currentUser();
    $validatedData['invoice_number']=$this->generateInvoice();
    $validatedData['customer_name']=$request->customer_name??'Walk In Customer';
    $salesId=Sales::create($validatedData)->id;
    $this->reduceStock($productDetailsCollection);
     $salesData=Sales::with('salesAgent')->where('id',$salesId)->first();
    $this->deleteSuspendedSale($request->suspended_sale);
     DB::commit();
     return appResponse(['sales'=>$salesData,'message'=>'sales completed successfully'],201);
   }
   catch(Exception $e){
      return appResponse(['errorMessage'=>$e->getMessage()],500);
   }

    }


    /**
        * @OA\Get(
        * path="/api/sales/{id}",
        * operationId="Get sale by Id",
        * tags={"Sales"},
        * summary="Get sale by id",
        * security={{ "bearer_token": {} }},
        * description="Get sale by id",
        * @OA\Parameter(
        *    name="id",
        *    in="path",
        *    description="sale id to fetch",
        *    required=true,
        *      ),
        *      @OA\Response(response=201,description="success",@OA\JsonContent()),
        *      @OA\Response(response=200,description="success", @OA\JsonContent()),
        *      @OA\Response(response=422,description="Unprocessable Entity", @OA\JsonContent()),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */ 
    public function show(Sales $sale)
    {
        
        return appResponse(['sales'=>$sale,'message'=>'success']);
    }

   
    /**
        * @OA\Put(
        * path="/api/sales",
        * operationId="Update sales",
        * tags={"Sales"},
        * summary="update sales",
        * security={{ "bearer_token": {} }},
        * description="Update  sales",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"product_details","payment_type","amount"},
        *               @OA\Property(property="product_details", type="[{id:number,name:text,pieces_qty:number,bulk_qty:number,amount:number}]"),
        *               @OA\Property(property="customer_name", type="text"),
        *               @OA\Property(property="amount", type="number"),
        *               @OA\Property(property="payment_type", type="text"),
        *               @OA\Property(property="discount", type="number"),

        *              
        *            ),
        *        ),
        *    ),
        *      @OA\Response(response=201,description="success",@OA\JsonContent()),
        *      @OA\Response(response=200,description="success", @OA\JsonContent()),
        *      @OA\Response(response=422,description="Unprocessable Entity", @OA\JsonContent()),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function update(SalesRequest $request,Sales $sale)
    {
           
        DB::beginTransaction();
        try{
         $productDetails=$request->product_details;
         $productDetailsCollection=new Collection($productDetails);
        $this->salesUpdateStockBalance($sale,$productDetailsCollection);
                    $data=[
                            'payment_type'=>$request->payment_type,
                            'product_details'=> $productDetails,
                            'sales_agent'=>$this->currentUser(),
                            'store_id'=>$this->currentStore(),
                            'customer_name'=>$request->customer_name
                         ];
                      $sale->update($data);

             
        //   $result=$this->balanceStock($productDetailsCollection);
          
          DB::commit();
          return $this->appResponse(['sales'=>$sale,'message'=>'sales updated successfully'],500);
        }
        catch(Exception $e){
           return appResponse(['errorMessage'=>$e->getMessage()],500);
        }
     
    }






    

    /**
        * @OA\Delete(
        * path="/api/sales/{id}",
        * operationId="Delete sale",
        * tags={"Sales"},
        * summary="Delete sale by id",
        * security={{ "bearer_token": {} }},
        * description="Delete sale by id",
        * @OA\Parameter(
        *    name="id",
        *    in="path",
        *    description="sale id to delete",
        *    required=true,
        *      ),
        *      @OA\Response(response=201,description="success",@OA\JsonContent()),
        *      @OA\Response(response=200,description="success", @OA\JsonContent()),
        *      @OA\Response(response=422,description="Unprocessable Entity", @OA\JsonContent()),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */ 
    public function destroy(Sales $sale)
    { 
        DB::beginTransaction();
        try{
        $productDetails=collect($sale->product_details);

        // UPDATE STOCK BEFORE DELETE
        foreach($productDetails as $product){
            $qty=$this->getSkuQty($product['id'],$product['bulk_qty'],$product['pieces_qty']);
            $this->updateStock($product['id'],$qty);

                     }
                     $sale->delete();
      DB::commit();
         return appResponse(['message'=>"Sales deleted successfully"]);
          }catch(Exception $e){
          return appResponse(['errorMessage'=>$e->getMessage()]);
        
        }
    }






    
    /**
        * @OA\Post(
        * path="/api/sales/suspend-sale",
        * operationId="Suspend sale",
        * tags={"Sales"},
        * summary="Suspend sale",
        * security={{ "bearer_token": {} }},
        * description="Suspend sale",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"product_details","payment_type","amount"},
        *               @OA\Property(property="product_details", type="[{id:number,name:text,pieces_qty:number,bulk_qty:number,amount:number}]"),
        *               @OA\Property(property="customer_name", type="text"),
        *               @OA\Property(property="amount", type="number"),
        *               @OA\Property(property="payment_type", type="text"),
        *               @OA\Property(property="discount", type="number"),

        *              
        *            ),
        *        ),
        *    ),
        *      @OA\Response(response=201,description="success",@OA\JsonContent()),
        *      @OA\Response(response=200,description="success", @OA\JsonContent()),
        *      @OA\Response(response=422,description="Unprocessable Entity", @OA\JsonContent()),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */

    public function suspendSale(SalesRequest $request){
        $validatedData=$request->validated();
        $validatedData['sales_agent']=currentUser();
       $sale= SuspendedSale::create($validatedData);
        return appResponse(['sale'=>$sale,'message'=>'Sales suspended successfullu']);
     }
    




  /**
        * @OA\Get(
        * path="/api/sales/suspend-sale",
        * operationId="Get All suspended  sales",
        * tags={"Sales"},
        * summary="Get All suspended sales",
        * security={{ "bearer_token": {} }},
        * description="Get allsuspended  sales", 
        *      @OA\Response(response=200,description="success", @OA\JsonContent()),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */



public function getSuspendedSales(){
    
$sales=SuspendedSale::latest()
        ->get();

  return appResponse(['sales'=>$sales]);

}


    public function generateInvoice(){
        $salesCount=Sales::count()+1;
        return str_pad($salesCount,8,'0',STR_PAD_LEFT);
    }

    public function getTotal(Collection $productDetails) {
       $total= $productDetails->reduce(function($sum,$product){
            return $sum+$product['amount'];
        });
        return $total;
    }



    public function reduceStock(Collection $productDetailsCollection){
        foreach($productDetailsCollection as $product){
            $this->updateStock($product['id'],0,$product['skuQty']);
          }
          return true;
    }



    public function salesUpdateStockBalance(Sales $sale,Collection $productDetailsCollection){

        foreach($productDetailsCollection as $product){
            $quantitySold=0;
            $qtyDiff=0;
            $currentQty=$product['skuQty'];  
           $soldProduct=collect($sale->product_details)->where('id',$product['id'])->first();
             if(!empty($soldProduct)){
                 $quantitySold= $soldProduct['skuQty'];
                 $qtyDiff=$quantitySold-$currentQty;
                if($qtyDiff>=0){
                    $this->updateStock($product['id'],$qtyDiff);
                }
                else{
                   $this->updateStock($product['id'],0,$qtyDiff*-1);   
                }
            }
            else{
                 $this->updateStock($product['id'],0, $currentQty);
            }
        }
        return true;
  }
    


public function deleteSuspendedSale($id=null){
    if(!empty($id)){
        SuspendedSale::destroy($id);
        return appResponse(['message'=>'Suspended sale deleted cuccessfully']);
    }
}

}
