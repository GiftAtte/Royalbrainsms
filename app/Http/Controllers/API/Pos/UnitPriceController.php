<?php

namespace App\Http\Controllers\API\Pos;

use App\Http\Controllers\API\Traits\AppUtility;
use App\Http\Controllers\Controller;
use App\Http\Requests\UnitPriceRequest;
use Illuminate\Http\Request;
use App\Models\Pos\UnitPrice;

class UnitPriceController extends Controller
{
    use AppUtility;
  
    public function index()
    { 
        $units=UnitPrice::latest()->get();
         return $this->AppResponse(['units'=>$units]);
    }

    public function store(UnitPriceRequest $request)
    {
        $unit=UnitPrice::create($request->validated());
        return $this->AppResponse(['unit'=>$unit]);
    }

    public function show(UnitPrice $unitPrice)
    {
        return$this->AppResponse(['unit'=>$unitPrice]);
    }

    
    public function update(UnitPriceRequest $request, UnitPrice $unitPrice)
    {
       $unitPrice->update($request->all());
       return$this->AppResponse(['message'=>'updated successfully']);
    }

     
    public function destroy( UnitPrice $unitPrice)
    {
        $unitPrice->delete();
        return$this->AppResponse(['message'=>'deleted successfully']);
    }
}
