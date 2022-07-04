<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\API\Utils\AppUtils;
use App\Items;
use App\Http\Controllers\Controller;
use App\IssuedItem;
use App\ItemPurchase;
use App\Itemstock;
use App\Stock;
use Illuminate\Http\Request;

class ItemsController extends Controller
{

    public function index(){
        return Items::where('school_items.school_id',auth('api')->user()->school_id)
                   ->join('categories','school_items.category_id','=','categories.id')
                   ->select('school_items.id as id','school_items.name as name','categories.name as category','school_items.created_at as date')
                   ->orderBy('category')
                   ->orderBy('date')
                   ->get();
    }

    public function createItem(Request $request){

        $data=$request->validate([
            'name'=>'required|string',
            'category_id'=>'required|string',
        ]);
        $data['school_id']=auth('api')->user()->school_id;
              return Items::create($data);
    }


    public function update(Request $request){
           $request->validate([
            'name'=>'required|string',
            'category_id'=>'required|string',
        ]);
        return Items::where('id',$request->id)->update($request->all());
    }


    public function delete($id){
        Items::where('id',$id)->delete();
        return;
    }




    public function issueItem(Request $request){

            $data= $request->validate([
                 'issued_to'=>'required',
                 'item_id'=>'required',
                 'date_issued'=>'required',
                 'quantity'=>'required'
            ]);

              $data['issued_by']=AppUtils::getCurrentEmployeeId();
              $data['school_id']=AppUtils::getSchoolId();
              $data['return_date']=$request->return_date;



              IssuedItem::create($data);

              $itemStock=Itemstock::where('item_id',$request->item_id)->get();
              if(count($itemStock)>0){
                return $this->updateItemStock($request->item_id);
              }else{
                return $this->addNewItemStock($request->item_id,$request->quantity);
              }


    }
    public function returnItem($id){

        IssuedItem::where('id',$id)->update(['isReturned'=>1]);
       return 'success';
                    }


public function updateItemStock($itemId){

      $itemIssued=IssuedItem::where('item_id',$itemId)
                         ->where('isReturned',1)
                         ->orWhere('type','Running Asset')
                         ->sum('quantity');

         $itemPurchased= ItemPurchase::where('item_id',$itemId)
                       ->sum('quantity');

          $itemStock= Itemstock::where('item_id',$itemId)->first();
  if(count($itemStock)>0){
      $itemStock->update(['quantity'=>$itemPurchased-$itemIssued]);
  }

}


      public function addNewItemStock($itemId,$quantity){

               return Itemstock::create([
                'item_id'=>$itemId,
                'quantity'=>$quantity,
                'school_id'=>AppUtils::getSchoolId()

               ]);
                 }




}



