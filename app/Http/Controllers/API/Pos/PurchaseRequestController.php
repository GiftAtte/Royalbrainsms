<?php

namespace App\Http\Controllers\API\Pos;

use App\Http\Controllers\API\Traits\AppUtility;
use App\Http\Controllers\Controller;
use App\Models\Pos\PurchaseRequest;
use App\Http\Requests\PurchaseRequest as Request;
use App\Models\Account\Expense;

use Illuminate\Support\Facades\Validator;

class PurchaseRequestController extends Controller
{
    use AppUtility;
    

    public function __construct()
    {
        
    }
   
        public function index()
        {
            $expenses=PurchaseRequest::with('store','department','createdBy')->latest()->get();
            return $this->appResponse(['request'=>$expenses,'message'=>'success']);
        }
     
        public function store(Request $request)
        {


            $validatedData=$request->validated();
            $validatedData['created_by']=$this->currentUser();
            $purchaseRequest=PurchaseRequest::create($validatedData);
            return $this->appResponse(['purchaseRequest'=>$purchaseRequest,'message'=>'PurchaseRequest created successfully'],201);
        }
    
     
        public function show(PurchaseRequest $purchaseRequest)
        {
            return $this->appResponse(['purchase$purchaseRequest'=>$purchaseRequest]);
        }
    
      
        public function update(Request $request, PurchaseRequest $purchaseRequest)
        {  
            $validatedData=$request->validated();
            $validatedData['created_by']=$this->currentUser();
            $purchaseRequest->update($validatedData);
            return $this->appResponse(['expense'=>$purchaseRequest,'message'=>'updated successfully']);
        } 
    
        
        public function destroy(PurchaseRequest $expense)
        {
            $expense->delete();
            return $this->appResponse(['message'=>'deleted successfully']);
        }
    }
    