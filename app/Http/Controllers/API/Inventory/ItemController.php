<?php

namespace App\Http\Controllers\API\Inventory;
use App\Http\Controllers\API\Utils\AppUtils;
use App\Items;
use App\Http\Controllers\Controller;
use App\IssuedItem;
use App\ItemPurchase;
use App\ItemStock;
use App\Stock;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(){
        return Items::where('school_items.school_id',auth('api')->user()->school_id)
                   ->join('categories','school_items.category_id','=','categories.id')
                   ->select('school_items.*','categories.name as category')
                   ->orderBy('category')
                   ->orderBy('created_at','desc')
                   ->get();
    }

    public function createItem(Request $request){

        $data=$request->validate([
            'name'=>'required|string',
            'category_id'=>'required|string',
            'type'=>'required|string',
        ]);
        $data['school_id']=auth('api')->user()->school_id;
              return Items::create($data);
    }


    public function update(Request $request){
           $request->validate([
            'name'=>'required|string',
            'category_id'=>'required|string',
            'type'=>'required|string',
        ]);
        return Items::where('id',$request->id)->update($request->all());
    }


    public function delete($id){
        Items::where('id',$id)->delete();
        return;
    }




    public function issueItems(Request $request){

            $data= $request->validate([
                 'issued_to'=>'required',
                 'item_id'=>'required',
                 'category_id'=>'required',
                 'type'=>'required',
                 'issued_date'=>'required',
                 'quantity'=>'required',
            ]);

              $data['issued_by']=AppUtils::getCurrentEmployeeId();
              $data['school_id']=AppUtils::getSchoolId();
              $data['return_date']=$request->return_date;



            return  IssuedItem::create($data);



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

          $itemStock= ItemStock::where('item_id',$itemId)->first();
  if(count($itemStock)>0){
      $itemStock->update(['quantity'=>$itemPurchased-$itemIssued]);
  }

}


      public function addNewItemStock($itemId,$quantity){

               return ItemStock::create([
                'item_id'=>$itemId,
                'quantity'=>$quantity,
                'school_id'=>AppUtils::getSchoolId()

               ]);
                 }


public function getIssueItems(){
    return IssuedItem::where('school_items.school_id',AppUtils::getSchoolId())
                      ->join('school_items','issued_items.item_id','=','school_items.id')
                      ->join('staff','issued_items.issued_to','=','staff.id')
                      ->selectRaw('concat(staff.surname," ",staff.first_name) as reciever,school_items.name as item,issued_items.*')
                      ->latest()->paginate(50);
}



public function deleteIssueItems($id){
    IssuedItem::destroy($id);
    return;
}


   public function updateIssueItems(Request $request){

   $data= $request->validate([
                 'issued_to'=>'required',
                 'item_id'=>'required',
                 'category_id'=>'required',
                 'type'=>'required',
                 'issued_date'=>'required',
                 'quantity'=>'required',
            ]);

              $data['issued_by']=AppUtils::getCurrentEmployeeId();
              $data['school_id']=AppUtils::getSchoolId();
              $data['return_date']=$request->return_date;



            return  IssuedItem::where('id',$request->id)->update($data);



    }


        public function returnItems(Request $request){


             IssuedItem::where('id',$request->id)
                         ->update([
                              'isReturned'=>1,
                               'comment'=>$request->comment
                         ]);


                         return[
                            'status_code'=>200,
                            'status'=>'success',
                            'message'=>'updated successfully'
                         ];

        }

   }






