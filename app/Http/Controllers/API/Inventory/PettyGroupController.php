<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\API\Utils\AppUtils;
use App\Http\Controllers\Controller;
use App\Models\Pettygroup;
use Illuminate\Http\Request;

class PettyGroupController extends Controller
{

    public function index()
    {
        //return 'here';
        return Pettygroup::where('pettygroups.school_id',AppUtils::getSchoolId())
                        ->join('sessions','pettygroups.session_id','=','sessions.id')
                        ->join('terms','pettygroups.term_id','=','terms.id')
                        ->select('pettygroups.*','sessions.name as session','terms.name as term')
                         ->paginate(2);
    }

    public function store(Request $request)
    {

        $data=$request->validate([
            'session_id'=>'required',
            'term_id'=>'required',
            'title'=>'required'
        ]);

         //return $data;
             $data['school_id']=AppUtils::getSchoolId();
              return Pettygroup::create($data);

    }

    public function show($id)
    {
        //
    }

    public function update(Request $request)
    {

         $data=$request->validate([
            'session_id'=>'required',
            'title'=>'required'
        ]);

           Pettygroup::where('id',$request->id)->update($request->all());
    }

    public function destroy($id)
    {
        Pettygroup::destroy($id);
    }


    public function getAllGroup(){
        return Pettygroup::where('school_id',AppUtils::getSchoolId())
                           ->latest()->get();
    }
}
