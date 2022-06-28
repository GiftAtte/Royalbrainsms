<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Sales;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Inventory\StockController;
use App\Http\Controllers\API\Utils\AppUtils ;
use App\SaleDetails;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{

    public function index(){
        return DB::table('sales')
                    ->where('sales.school_id',auth('api')->user()->school_id)
                    ->join('students','sales.student_id','=','students.id')
                    ->selectRaw('concat(students.surname ," ", students.first_name) as name, sales.*')
                    ->latest()
                    ->get();
    }

    public function createSales(Request $request){
          // return $request->all();
      $appUtils=new AppUtils();
        $data=$request->validate([
            'total_amount'=>'required',
            'paid_amount'=>'required',
            'student_id'=>'required',
            'payment_method'=>'required'
        ]);
        $products=$request->products;
        $balance=$request->total_amount-$request->paid_amount;
        $transactionId=$appUtils->generateRandomString(20);
        $now=Carbon::now();
         $data['sell_date']=$now;
         $data['discount']=$request->discount;
         $data['products_count']=count($request->products);
         $data['school_id']=$appUtils->school_id();
         $data['employee_id']=$appUtils->employee_id();
         $data['transaction_id']=$transactionId;
          $data['payment_balance']=$balance;
         $data['payment_status']=$balance<=0?'Cleared':'Not Cleared';
          $sales_id=Sales::create($data)->id;
          $stock=new StockController();
            if($sales_id>0){
    //return $products;
             foreach($products as $product){
            $details=[];
    $details['transaction_id']=$transactionId;
    $details['sales_id']=$sales_id;
    $details['product_id']=$product['id'];
    $details['quantity']=$product['quantity'];
    $details['unit_price']=$product['price'];
    $details['amount']=$product['amount'];


                 SaleDetails::create($details);
                 $stock->makeSale($product['id'],$product['quantity']);

             }


          return [
            'status'=>'success',
            'status_code'=>201,
             'products'=>$products

          ];

        }
    }

    public function update(Request $request){
           $request->validate([
            'name'=>'required|string',
            'type'=>'required|string',
        ]);
        return Sales::where('id',$request->id)->update($request->all());
    }


    public function delete($id){
        Sales::where('id',$id)->delete();
        return;
    }
}
