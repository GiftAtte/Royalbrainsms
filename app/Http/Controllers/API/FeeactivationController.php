<?php

namespace App\Http\Controllers\API;

use App\Bill;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StudentFees;
use App\Fee_description;
use App\Fee_group;
use App\Student;
class FeeactivationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function loadActivation($feegroup_id)
    {
        $Ecopay=new EcopayController();
      $report=Fee_group::findOrFail($feegroup_id);
         $amount=Fee_description::where('feegroup_id',$feegroup_id)->sum('amount');
      $description= \DB::table('students')->where('class_id',$report->level_id)
        ->leftJoin('student_fees', function($join) use($feegroup_id)
        {
            $join->on('student_fees.student_id', '=', 'students.id')
            ->where('student_fees.feegroup_id',$feegroup_id);
        })->select(\DB::raw('CONCAT(students.surname," ", students.first_name)as name, students.id as id,
         students.accountNumber as accountNumber, students.id as student_id, student_fees.activation_status as activation_status,student_fees.amount as amount_paid'))
         ->distinct('students.id')->orderBy('name')
        ->get();

        $bill=Bill::where('feegroup_id',$feegroup_id)->first();

        $accountNumbers=collect($description)
       ->whereNotNull('accountNumber')
       ->pluck('id');
         $walletDetails=[];
         if(count($accountNumbers) > 0){
       for($i=0;$i<count($accountNumbers);++$i){
           $account=$Ecopay->getAccountBalance($accountNumbers[$i]);
            array_push($walletDetails, ['studentId'=> $accountNumbers[$i],'balance'=>$account['balance']]);
        }
    }
//xpectedIncome=Fee_description::whereIn('feegroup_id',[$feegroup_id])->sum('amount');
        $expectedIncome= $amount*count($description);
        return[
        'description'=>$description,
        'amount'=>$amount,
        'report'=>$report,
        'bill'=>$bill,
        'walletInfo'=>$walletDetails,
        "expectedIncome"=>  $expectedIncome
    ];
    }
    public function pay(Request $request)
    {
        $firstpay=0;
        $student_id=null;
        if(empty($request->student_id)){
            $student_id=auth('api')->user()->student_id;
        }else{
            $student_id=$request->student_id;
        }
        //return $request->all();
        $partial=StudentFees::where([['student_id',$student_id],['feegroup_id',$request->feegroup_id]])
        ->first();
        if($partial){
            $firstpay= $partial->amount;
            $partial->delete()    ;
        }

     StudentFees::create(
         [
          'student_id'=>$student_id,
          'feegroup_id'=>$request->feegroup_id,
          'amount'=>($request->amount+$firstpay),
          'activation_status'=>0,
           'transaction_id'=>$request->transaction_id,
           'reference_id'=>$request->reference_id,
           'message'=>$request->message,



      ]);
      $balance=0;
      $feegroup=Fee_group::findOrFail($request->feegroup_id);
      $amount=Fee_description::where('feegroup_id',$request->feegroup_id)->sum('amount');

      $partial=StudentFees::where([['student_id',$student_id],['feegroup_id',$request->feegroup_id]])
      ->first();
      if($feegroup->discount>0){
         $balance= ($amount-($feegroup->discount/100)*$amount)-$partial->amount;

          if($balance<=0)
      {
        $partial->activation_status=1;
        $partial->save();
       }
      }else{
      if(($amount-$partial->amount)<=0)
      {
        $partial->activation_status=1;
        $partial->save();
       }else{
           $partial->activation_status=0;
        $partial->save();
       }

}
   return 'success';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $students=$request->number_of_students;
          //return $request->all();
        for ($i=0;$i<$students;++$i){
       \DB::table('student_fees')->updateOrInsert(
            ['feegroup_id' => $request->feegroup_id, 'student_id' => $request->student_id[$i]],[
            'student_id'=>$request->student_id[$i],
            'feegroup_id'=>$request->feegroup_id,
           // 'amount'=>$request->amount,
            'activation_status'=>$request->activation_status[$i],
            //  'transaction_id'=>$request->transaction_id[$i],
            //  'refernce_id'=>$request->reference_id[$i],
            //  'message'=>$request->message[$i],



        ]);

    }}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivateResults(Request $request)
    {
        $students=$request->csv;
        foreach($students as $student){
            \DB::table('student_fees')->updateOrInsert(
                 ['feegroup_id' => $student['feegroup_id'], 'student_id' => $student['students_id']],[
                 'student_id'=>$student['students_id'],
                 'feegroup_id'=>$student['feegroup_id'],
                 'activation_status'=>0,



             ]);

         }}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
