<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\API\Utils\AppUtils;
use App\Http\Controllers\Controller;
use App\Model\Pettycash;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($group_id=null)

    {
           //   return $group_id;
        if(!empty($group_id)){
        return Expense::where('pettygroup_id',$group_id)
             ->latest()
              ->paginate(20);
        }else{
  return Expense::where('school_id',AppUtils::getSchoolId())
                       ->latest()->paginate(20);
        }

    }

    public function store(Request $request)
    {
              $request->validate([
                'purpose'=>'required',
                'amount'=>'required|numeric',
                'expense_date'=>'required'
              ]);
              $data=$request->all();
              $data['employee_id']=AppUtils::getCurrentEmployeeId();
              $data['school_id']=AppUtils::getSchoolId();
                $expense= Expense::create($data);


             //   $this->updatePettCash($expense->amount,'new');


                        return $expense;

    }



    public function destroy($id)
    {
      $expense= Expense::findOrFail($id);
      $this->updatePettCash($expense->amount,'delete');


    }


    public function updatePettCash($amount,$type){
        $pettyCash=DB::table('pettycaches')
                        ->where('id', DB::raw("(select max(`id`) from pettycaches)"))
                        ->first();
                        if($type==='delete'){
                         $pettyCash['current_balance'] = $pettyCash['current_balance']+$amount;
                        }else{
                            $pettyCash['current_balance'] = $pettyCash['current_balance']-$amount;
                        }
                      return  $pettyCash->save();

    }
}
