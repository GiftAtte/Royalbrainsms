<?php

namespace App\Http\Controllers\API;

use App\FeeItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeeItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FeeItem::where('school_id',auth('api')->user()
              ->school_id)->get();
    }

  
    public function store(Request $request)
    {
        $requestItems=$request->all();
        $requestItems['school_id']=auth('api')->user()->school_id;
       $feeItem=FeeItem::create($requestItems);
       return[
            'status_code'=>'200',
            'status'=>'success',
            'feeItem'=>$feeItem
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        //
    }

    public function update(Request $request)
    {
      FeeItem::where('id',$request->id)->update($request->all());

       return[
            'status_code'=>'200',
            'status'=>'success',

        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FeeItem::where('id',$id)->delete();
        return[
            'status_code'=>'200',
            'status'=>'success'
        ];
    }
}
