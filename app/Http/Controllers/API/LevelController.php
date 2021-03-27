<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\LevelResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Level;
use App\Arm;
use App\Has_arm;

class LevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $id=auth('api')->user()->school_id;
        return Level::with('staff')->where([['school_id',$id],['is_history',0]])->latest()->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Level::create($request->all());

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check_arm($id)
    {
        $has_arm=false;
        $level=Level::findOrFail($id);
        if($level->has_arm>0){
            $has_arm=true;
        }
         return $has_arm;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function loadArms($id)
    {
        $historyLevel=Level::where('is_history',1)->pluck('id');
         $user=auth('api')->user();
        if($user->type==='tutor'){
           $arm=Has_arm::where(['staff_id',$user->staff_id])->whereNotIn('level_id',$historyLevel)->first();
            return Has_arm::with('arms')->whereNotIn('level_id',$historyLevel)->where([['school_id',$user->school_id],['staff_id',$user->staff_id],
            ['level_id',$arm->level_id]])->get();
        }
        return Has_arm::with('arms')->where('level_id',$id)->get();
    }
public function level_arms()
{

   return Arm::where('school_id',auth('api')->user()->school_id)->get();
    # code...
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
        $this->validate($request,[
        'level_name' => 'required|string|max:191',

    ]);
    $level=Level::find($id);
        $level->update($request->all());
        return response()->json([
            'data' => new LevelResource($level),
            'message' => 'Successfully updated event!',

        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $level = Level::findOrFail($id);
        // delete the user

        $level->delete();

        return ['message' => 'level Deleted'];
    }
    public function delete_arm($id)
    {
        $arm = Has_arm::findOrFail($id);
        // delete the user
         $level_id=$arm->level_id;
          $arm->delete();
          $check =count( Has_arm::where('level_id',$level_id)->get());
          if($check<1){
            $level=Level::findOrFail($level_id);
            $level->has_arm=0;
            $level->save();

          }

        return ['message' => 'arm Deleted'];
    }
    public function getLevels(){
        $user=auth('api')->user();
        if($user->type==='tutor'){
            $arm=Has_arm::where('staff_id',$user->staff_id)->pluck('level_id');
            return Level::where('school_id',$user->school_id)->whereIn('id',$arm)->get();
        }
        return Level::where('school_id',auth('api')->user()->school_id)->get();
    }


    public function arms($id)
    {
        //
        return Has_arm::with(['arms','staff','levels'])->where('level_id',$id)->get();
       // return response()->json(["message"=>"arms added successfull"]);
    }

    public function update_arms(Request $request ,$id)
    {
        //
        $hasArm=Has_arm::where('staff_id',$request->staff_id)->first();
        if( $hasArm){
        $hasArm->staff_id=null;
         $hasArm->save();
        }
       
        $item = Has_arm::findOrFail($id);
        $item->arms_id=$request->arms_id;
        $item->staff_id=$request->staff_id;
       // return $item;
        $item->save();
         return response()->json(["message"=>"arms updated successfull"]);
    }

    public  function add_arms(Request $request){
        $arm=Has_arm::where([['arms_id',$request->arms_id],
        ['level_id',$request->level_id]])->first();
        if($arm){
            return back()->withErrors('Sorry! Arm exist ');
        }
        Has_arm::create($request->all());
        $updated_level = Level::findOrFail($request->level_id);
        if($updated_level->has_arm<=1){
        $updated_level->has_arm=1;
        $updated_level->save();

        }

          return response()->json(["message"=>"arms added successfull"]);
    }
}
