<?php
namespace App\Http\Controllers\API\Traits;
use App\Level;
use App\Level_history;
use App\Report;
use App\Student;
use Illuminate\Support\Facades\DB;

trait RankingTrait{


  public function Rank($positions=array() ,$student_id){
                      $rank=1;
                   $total=0;
                   $rank2=0;
                   $counter=1;
                     $num=1;
                  //  return[$positions,$student_id] ;
                  foreach($positions as $position) {


                          if($position->total==$total)
                          {    $rank=$rank-$num;$rank2=$rank ;$num=++$num;}


                          if($position->student_id==$student_id){
                            return ['subject_id'=>$position->subject_id,'position'=>$this->ordinal($rank),'student_id'=>$position->student_id];
                            break;
                                }


        $total=$position->total;
        if($rank2==$rank){$rank=$rank+$num;}else{
            $num=1;
            $rank=++$rank;
        }


    }


    }


  public function Rank2($positions=array() ,$id){
                   $rank=1;
                   $total=0;
                   $rank2=0;
                   $counter=1;
                     $num=1;
                  foreach($positions as $position) {


                          if($position->total==$total)
                          {    $rank=$rank-$num;$rank2=$rank ;$num=++$num;}


                          if($position->student_id==$id){
                            return [$position->student_id,$this->ordinal($rank),$position->total];
                            break;
                                }


        $total=$position->total;
        if($rank2==$rank){$rank=$rank+$num;}else{
            $num=1;
            $rank=++$rank;
        }


    }


    }

    // Class and arm positioning ===sorting students
  public function studentPosition($id,$report_id,$arm=true){
  $report=Report::findOrFail($report_id);
  $student=Student::findOrFail($id);
  $is_history=Level::findOrFail($report->level_id)->is_history;
  $students=null;
  if($arm){
      if($is_history>0){
        $students=Level_history::where([['level_id',$report->level_id],['arm_id',$student->arm_id]])->get();
    }else{
        $students=Student::select('id')->where([['class_id',$report->level_id],['arm_id',$student->arm_id]])->get();
    }


  }
  else{
  $students=Student::select('id')->where('class_id',$report->level_id)->get();
  }
  $studentArr=array();
  $studentPosition=array();
  foreach($students as $student){
  $avgScores=DB::table('marks')->whereNotIn('total',[0])
  -> where([['student_id',$student->id],['type','Academic'],
  ['report_id',$report_id]

  ])
  ->select(DB::raw('avg(total) as Total'),'student_id')

   ->orderBy('Total','DESC')
  ->get();

  foreach($avgScores as $avgScore){
  array_push($studentArr,$avgScore);
     rsort($studentArr);
  }}
  // foreach($students as $student){
  //   array_push($studentPosition,  $this->Rank3($studentArr,$id));
  // }
  return $this->Rank3($studentArr,$id);
  }

  // class positionioning/arm positioning Ranking
  public function Rank3($positions=array() ,$id){
                   $rank=1;
                   $total=0;
                    $rank2=0;
                    $counter=1;

                  foreach($positions as $position) {


                          if($position->Total==$total)
                          {    $rank=$rank-$counter; $rank2=$rank;$counter=++$counter;}


                          if($position->student_id==$id){
                            return$this->ordinal($rank);
                            break;
                                }


        $total=$position->Total;
        if($rank2==$rank){$rank=$rank+$counter;}else{
     $rank=++$rank;
     $counter=1;
        }

    }


    }

    public function ordinal($number)
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number . 'th';
        } else {
            return $number . $ends[$number % 10];
        }
    }



public function getRanking($scoresCollection,$total){
     $data = $scoresCollection->sortByDesc('total')->where('total', $total);
         $value = $data->keys()->first() + 1;
    return $this->ordinal($value);
 }
 public function annualRanking($scoresCollection,$total){
     $data = $scoresCollection->sortByDesc('annual_total')->where('annual_total', $total);
         $value = $data->keys()->first() + 1;
    return $this->ordinal($value);
 }


}
