<?php

namespace App\Imports;

use App\Mark;
use App\Report;
use App\Level_sub;
use App\Http\Controllers\API\ScoreController;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class MarksImport implements ToModel, WithHeadingRow

{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
           $scoreController =new ScoreController();
           $report=Report::findOrFail($row['reportid']);
        $total=$scoreController->sum($row['test1'],$row['test2'],['test3'],$row['exams']);
        $gradding=$scoreController->grade($total,$report->gradinggroup_id);
        $subject_type=Level_sub::where([['level_id',$row['level_id']],['subject_id',$row['subject_id']]])->first();
        return new Mark([

             'student_id'=>$row['student_id'],
            'report_id'=>$row['report_id'],
            'level_id'=>$row['level_id'],
            'subject_id'=>$row['subject_id'],
                  'test1'=>$row['test1'],
                  'test2'=>$row['test2'],
                  'test3'=>$row['test3'],
                  'exams'=>$row['exams'],
                  'total'=>$total,
                  'grade'=>$gradding['grade'],
                  'narration'=>$gradding['narration'],
                  'term_id'=>$report->term_id,
                  'arm_id'=>$row['arm_id'],
                  'type'=>$subject_type->type,

            //
        ]);
    }
}
