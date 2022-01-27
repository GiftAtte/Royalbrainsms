<?php

namespace App\Http\Controllers\API;

use App\Bill;
use App\Ecopay;
use App\Fee_description;
use App\Fee_group;
use App\Http\Controllers\Controller;
use App\Paystack;
use App\Student;
use App\StudentFees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EcopayController extends Controller
{

    public function Neovast($method, $url, $body = [])
    {
        $merchant = Paystack::whereNotNull('clientId')
            ->where('school_id', auth('api')->user()->school_id)->first();
        $body['merchant_id'] = $merchant->clientId;
        $body['merchantId'] = $merchant->clientId;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'apikey' => env('NEOVAST_API_KEY'),
            'ClientId' => env('NEOVAST_CLIENT_ID'),
        ])
            ->$method(env('NEOVAST_URL') . $url, $body);
        return $response->json();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Ecopay::where('school_id', auth('api')->user->school_id)->paginate(50);
    }

    public function generateBulkAccount(Request $request)
    { //return $request->accountDetails;
        $merchant = Paystack::whereNotNull('clientId')
            ->where('school_id', auth('api')->user()->school_id)->first();

        $studentIds = explode(',', $request->accountDetails);
        $students = Student::with('schools')->whereIn('id', $studentIds)->get();
        foreach ($students as $student) {

            $this->generateAccount($student, $merchant->clientId);
        }

        return ['message' => "students accounts updated successfully"];
    }

    public function getAccount(Request $req)
    {
        $method = 'post';
        $body = [
            'email' => $req->email,
            'address' => $req->address,
            'full_name' => $req->full_name,
            'city' => $req->city,
            'phone' => $req->phone,


        ];

        $url = 'newAccounts.php';
        $res = $this->Neovast($method, $url, $body);

        return Student::where('id', $req->studentId)->update([
            "accountNumber" => $res['virtualAccountNumber'],
            "bankName" => $res['bankName']
        ]);
    }

    public function generateAccount($student, $merchantId)
    {
        $url = "newAccounts.php";
        $method = 'post';
        $body = [
            'email' => $student->femail ? $student->femail : 'attegift@gmail.com',
            'address' => $student->schools->name . ', ' . $student->schools->contact_address,
            'full_name' => $student->surname . ', ' . $student->first_name,
            'city' => $student->schools->state,
            'phone' => $student->fphone ? $student->fphone : '080636311790',
            'merchant_id' => $merchantId,

        ];
        $res = $this->Neovast($method, $url, $body);
        $student->accountNumber = $res['virtualAccountNumber'];
        $student->bankName = $res['bankName'];
        $student->save();
        return;
    }





    public function getAccountBalance($id = null)
    {
        $method = "post";
        $url = "fetchBalance.php";

        $accountNumber = null;
        if (empty($id)) {
            $student = Student::findOrFail(auth('api')->user()->student_id);
            $accountNumber = $student->accountNumber;
        } else {
            $student = Student::findOrFail($id);
            $accountNumber = $student->accountNumber;
        }
        $body = [
            'account_number' => $accountNumber,
        ];



        return $this->Neovast($method, $url, $body);
    }



    public function getTransactions($accountNumber)
    {
        $method = "post";
        $url = "fetchTransactions.php";
        $body = [
            'account_number' => $accountNumber,
        ];

        return $this->Neovast($method, $url, $body);
    }

    public function createBills(Request $req)
    {
        $res =  $this->Neovast(
            "post",
            "createBills.php",
            [
                'bill_title' => $req->title,
                'amount' => $req->amount,
            ]
        );

        return  Bill::Create([
            "title" => $req->title,
            "feegroup_id" => $req->feegroup_id,
            "amount" => $req->amount,
            "neovastId" => $res['billId']
        ]);
    }

    public function getBillAmount($id)
    {
        return Fee_description::where('feegroup_id', $id)->sum('amount');
    }

    public function getAllBills()
    {
        return Bill::all();
    }


    public function billAccounts(Request $req)
    {
        //return $req->all();
        $group = Fee_group::findOrFail($req->feegroup_id);
        $accountNumbers = Student::whereIn('class_id', [$group->level_id])
            ->whereNotNull('accountNumber')
            ->pluck('accountNumber');
        //return $accountNumbers[0];
        for ($i = 0; $i < count($accountNumbers); ++$i) {

            $this->billAccount(
                $accountNumbers[$i],
                $req->neovastId,
                $req->amount,
                $req->feegroup_id,
            );
        }
        return 'success';
    }



    public function billAccount($accountNumber, $billId, $amount, $feegroup_id)
    {

        $res =  $this->Neovast(
            "post",
            "billAccounts.php",
            [
                'virtualAccountNumber' => $accountNumber,
                'billId' => $billId
            ]
        );
        if ($res['responseCode'] === "001") {
            $student = Student::where('accountNumber', $accountNumber)->first();
            $student_id = $student->id;
            StudentFees::create(
                [
                    'student_id' => $student_id,
                    'feegroup_id' => $feegroup_id,
                    'amount' => $amount,
                    'activation_status' => 1,
                    'transaction_id' => $res['transactionId'],
                    'reference_id' => $res['transactionId'],
                    'message' => 'complete',



                ]
            );
        }
        return $res;
    }





    public function deleteBill($billId)
    {

        $bill = Bill::findOrFail($billId);

        $response = $this->Neovast(
            'post',
            "deleteBill.php",
            ['billId' => $bill->neovastId]
        );
        if ($response) {
            $bill->delete();
        }
    }
}
