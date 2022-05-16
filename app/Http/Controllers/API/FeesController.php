<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Fee_group;
use App\Fee_description;
use App\Student;
use App\Level_history;
use App\StudentFees;
use App\Paystack;
class FeesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index($student_id=null)
    {
        
        
        $user=auth('api')->user();
        
        if($user->type==='student'||!empty($student_id)){
            $current_level=null;
            if(!empty($student_id)){
                $current_level=Student::findOrFail($student_id);
            }else{
                $current_level=Student::findOrFail($user->student_id);
            }

          $history_level=Level_history::where('student_id',$current_level->id)
                           ->pluck('level_id')->toArray();
                           array_push($history_level,$current_level->class_id);


if($user->type==='parent'){
           $siblings=Student::whereIn('parent_id',[$user->parent_id]);
            $current_level=$siblings->pluck('class_id')->toArray();
            $siblingsIds=$siblings->pluck('id')->toArray();
           $history_level=Level_history::whereIn('student_id',$siblingsIds)
                          ->pluck('level_id')->toArray();
                $totalLevels=array_merge($history_level,$current_level);
           return Fee_group::with(['levels','terms','paystacks'])->whereIn('level_id',$totalLevels)
                 ->latest()->paginate(20);
            }



       return Fee_group::with(['levels','terms','paystacks'])->whereIn('level_id',$history_level)
                 ->latest()->paginate(20);


        }
        return Fee_group::with(['levels','terms','paystacks'])->where('school_id',auth('api')->user()->school_id)->latest()->paginate(20);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'tittle' => 'required',
            'session_id' => 'required',
            'term_id' => 'required',
            //'paystack_key' => 'required',
            'fee_type'=>'required'
        ]);
       return Fee_group::create([
           'tittle'=>$request->tittle,
           'term_id'=>$request->term_id,
           'session_id'=>$request->session_id,
           'level_id'=>$request->level_id,
           'due_date'=>$request->due_date,
           'discount'=>$request->discount,
           'fee_type'=>$request->fee_type,
           'paystack_key'=>$request->paystack_key,
           'school_id'=>auth('api')->user()->school_id,
       ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function feeDescriptions($feegroup_id,$student_id=null)

    {  $partial=null;
        if(empty($student_id) &&!empty(auth('api')->user()->student_id)){
          $student_id=auth('api')->user()->student_id;
        }
        $student=Student::with('users')->where('id',$student_id)->first();
         $total=Fee_description::where('feegroup_id',$feegroup_id)->sum('amount');
         $descriptions=Fee_description::with('feegroup')->where('feegroup_id',$feegroup_id)->get();
        $partial_amount=StudentFees::where([['feegroup_id',$feegroup_id],['student_id',$student_id]])->first();
         $feegroup=Fee_group::with(['levels','terms','sessions','paystacks'])->where('id',$feegroup_id)->first();
        $payment_status=StudentFees::where([['feegroup_id',$feegroup_id],['student_id',$student_id]])->first();
        $discounted_amount=$total-($feegroup->discount*$total/100);
        if($partial_amount){

           $balance=$discounted_amount-$partial_amount->amount;
            if($balance>0){
                $discounted_amount=$balance;
            }
            else{
              $discounted_amount=0;
            }
        }
        return ['details'=>$descriptions,'total'=>round($total,2),'payment_details'=>$payment_status,'feegroup'=>$feegroup,'discounted_amount'=>$discounted_amount,
        'amount_paid'=>$partial_amount,'student'=>$student];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function newDescription(Request $request)
    {
        $this->validate($request,[
            'description' => 'required',
            'feegroup_id' => 'required|integer',
            'amount' => 'required',



        ]);
       return Fee_description::create([
           'feegroup_id'=>$request->feegroup_id,
           'description'=>$request->description,
           'amount'=>$request->amount,
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //

        $this->validate($request,[
            'tittle' => 'required|string|max:191',
            // 'level_id' => 'required|integer',
            'term_id' => 'required|integer',
            'session_id' => 'required|integer',
            // 'paystack_key' => 'required',


        ]);
       // $request->all();
        $report=Fee_group::findOrFail($request->id);
        $report->update($request->all());
        return ['message'=>'report updated successfully'];
    }


    public function updateDescription(Request $request)
    {
        //

        $this->validate($request,[
            'description' => 'required',
            'feegroup_id' => 'required|integer',
            'amount' => 'required',



        ]);
        $report=Fee_description::findOrFail($request->id);
        $report->update($request->all());
        return ['message'=>'report updated successfully'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Fee_group::findOrFail($id)->delete();
        Fee_description::where('feegroup_id',$id)->delete();
        return 'success';
    }


    public function deleteDescription($id)
    {
        Fee_description::findOrFail($id)->delete();
        return 'success';
    }

    public function paystack()
    {

        return Paystack::where('school_id',auth('api')->user()->school_id)->latest()->get();
    }

    public function createPaystack(Request $request)
    {
        $this->validate($request,[
            'paystack_key' => 'required',
            'bank' => 'required',






        ]);
       return Paystack::create([
           'paystack_key'=>$request->paystack_key,
           'bank'=>$request->bank,
           'clientId'=>$request->clientId,
           'school_id'=>auth('api')->user()->school_id,

       ]);
    }

    public function deletePaystack($id)
    {
        Paystack::findOrFail($id)->delete();
        return 'success';
    }


    public function updatePaystack(Request $request)
    {
        //

        $this->validate($request,[

            'paystack_key' => 'required|string',
            'bank' => 'required',



        ]);
        $report=Paystack::findOrFail($request->id);
        $report->update($request->all());
        return ['message'=>'report updated successfully'];
    }

}
