<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\API\Utils\AppUtils;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Pettycash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PettycashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($group_id=null)
    {
             if(!empty($group_id)){
                return Pettycash::where('pettycashes.pettygroup_id',$group_id)
              ->join('pettygroups','pettycashes.pettygroup_id','=','pettygroups.id')
              ->select('pettycashes.*','pettygroups.title as group')
              ->latest()
              ->paginate(20);
             }



       return Pettycash::where('pettycashes.school_id',AppUtils::getSchoolId())
              ->join('pettygroups','pettycashes.pettygroup_id','=','pettygroups.id')
              ->select('pettycashes.*','pettygroups.title as group')
              ->latest()
              ->paginate(20);
             //->get();
    }

    public function store(Request $request)
    {
           $data=$request->validate([
            'amount'=>'required',
            'issued_date'=>'required',
            'pettygroup_id'=>'required'
           ]);

//return$data;
           $lastRecord=Pettycash::where('school_id',AppUtils::getSchoolId())
                                          ->find(DB::table('pettycashes')->max('id'));

            $currentBalance=$request->amount+($lastRecord?$lastRecord->current_balance:0);

                          $data['school_id']=AppUtils::getSchoolId();
                          $data['balance_carried_forward']=$lastRecord?$lastRecord->current_balance:0;
                          $data['current_balance']=$currentBalance;
                          $data['employee_id']=AppUtils::getCurrentEmployeeId();
                          return Pettycash::create($data);
    }


    public function update(Request $request)
    {
       $data=$request->validate([
            'amount'=>'required',
            'issued_date'=>'required'
           ]);


           $pettyCash=Pettycash::findOrFail($request->id);

                          $data['current_balance']=$pettyCash->current_balance-$pettyCash->balance_carried_forward
                                                  +$request->amount;
                          $data['term_id']=$request->term_id;
                          $data['session_id']=$request->session_id;
                          $data['employee_id']=AppUtils::getCurrentEmployeeId();
                          return $pettyCash->update($data);
    }

    public function delete($id)
    {
        //
        Pettycash::destroy($id);
    }

       public function getPettyBalance($group_id=null){
                 $xpense=0;
                 $pettyCash=0;
                  if(!empty($group_id)){
                $pettyCash=Pettycash::where([['school_id',AppUtils::getSchoolId()],['pettygroup_id',$group_id]])->sum('amount');
                $xpense=Expense::where([['school_id',AppUtils::getSchoolId()],['pettygroup_id',$group_id]])->sum('amount');
                  }else{
                $pettyCash=Pettycash::where('school_id',AppUtils::getSchoolId())->sum('amount');
                $xpense=Expense::where('school_id',AppUtils::getSchoolId())->sum('amount');
                  }
                $balance= ($pettyCash?$pettyCash:0) - ($xpense?$xpense:0);
                return[
                    'totalExpenses'=>$xpense,
                    'totalCredit'=>$pettyCash,
                    'totalBalance'=>$balance
                ];
       }

}
