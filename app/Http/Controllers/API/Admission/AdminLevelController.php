<?php

namespace App\Http\Controllers\API\Admission;

use App\Http\Controllers\API\Utils\AppUtils;
use App\Http\Controllers\Controller;
use App\Models\AdmissionLevel;
use Illuminate\Http\Request;

class AdminLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AdmissionLevel::where('school_id',AppUtils::getSchoolId())
               ->latest()->get();
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
            'name'=>'required|string'
        ]);
        $data['school_id']=AppUtils::getSchoolId();

       return AdmissionLevel::create($data);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data=$request->validate([
            'name'=>'required|string'
        ]);
        $data['school_id']=AppUtils::getSchoolId();
            AdmissionLevel::where('id',$request->id)->update($data);
            return [
                'status'=>'success',
                'status_code'=>'201',

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
        AdmissionLevel::destroy($id);
        return;
    }
}
