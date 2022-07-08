<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\API\Utils\AppUtils;
use App\Http\Controllers\Controller;
use App\IssuedItem;
use App\ItemStock;
use Illuminate\Http\Request;

class StockItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // return ItemStock::latest()->paginate(5);
       return ItemStock::where('item_stocks.school_id',AppUtils::getSchoolId())
                   ->join('school_items','item_stocks.item_id','=','school_items.id')
                   ->join('categories','school_items.category_id','=','categories.id')
                   ->join('staff', 'item_stocks.employee_id','=','staff.id')
                   ->selectRaw('item_stocks.*,categories.name as category,school_items.name as item,
                     concat(staff.surname," ",staff.first_name) as added_by')
                   ->orderBy('created_at')
                   ->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $data=$request->validate([
            'quantity'=>'required',
            'item_id'=>'required'
         ]);

         $data['school_id']=AppUtils::getSchoolId();
         $data['employee_id']=AppUtils::getCurrentEmployeeId();

            return ItemStock::create($data);
    }


    public function update(Request $request)
    {

         $data=$request->validate([
            'quantity'=>'required',
            'item_id'=>'required'
         ]);

         $data['school_id']=AppUtils::getSchoolId();
         $data['employee_id']=AppUtils::getCurrentEmployeeId();

            return ItemStock::where('id',$request->id)->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ItemStock::destroy($id);
        return;
    }


   public function getCurrentStock($item_id){

               //$totalIssued=0;
               $totalStock=ItemStock::where('item_id',$item_id)
                                                      ->sum('quantity');
                         $totalIssued=IssuedItem::where([['item_id',$item_id],['isReturned',0]])
                           ->sum('quantity');

                           return$totalStock - $totalIssued;

     }


}
