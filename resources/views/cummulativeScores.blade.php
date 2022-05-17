
@php
    function getTotal($obj,$subject_id,$term_id){
           $result=null;
        foreach ($obj as  $value) {

           if($value['subject_id']===$subject_id && $value['term_id']===$term_id){
            $result=$value;
            break;
           }


        }
         if(!empty($result) && is_numeric($result['total'])){
                return $result['total'];
            }
             else{
               return '-';
             }

    }
@endphp


<div>
                <table class=" table table-bordered myTable">

                <tr  style="vertical-align: center;background-color:rgb(15, 15, 112); color:rgb(251, 249, 246); text-transform:uppercase">
                <th>S/N</th>
                <th >SUBJECTS</th>
                <th>{{ $report->isCa2?'CA1':'CA' }}<br/>[{{ $report->ca1Percent?($report->ca1Percent.'%'):'' }}]</th>
                @if ($report->isCa2)
                <th>CA2 <br/>[{{ $report->ca2Percent?($report->ca2Percent.'%'):'' }}]</th>
                @endif
                @if ($report->isCa3)
                <th>CA3 <br/>[{{ $report->ca3Percent?($report->ca3Percent.'%'):'' }}]</th>
                @endif
                <th>Exam <br/>[{{ $report->examPercent?($report->examPercent.'%'):'' }}]</th>
                <th>1ST TERM<br>[100%]</th>
                <th>2ND TERM<br>[100%]</th>
                <th>3RD TERM<br>[100%]</th>
                <th>GRAND<br>TOTAL</th>
                <th>CUMM.<br>AVG</th>

                 @if ($report->isMaxScore)
                 <th>HIGHEST<br/>SCORE</th>
                 @endif
                 @if ($report->isMinScore)
                 <th>LOWEST <br/>SCORE</th>
                 @endif

                <th>GRADE</th>
                @if ($report->isSubjectPosition)
                 <th>SUBJECT<br/>POSITION</th>
                @endif
                <th>COMMENT</th>

                </tr>



                 @php
                     $count=1;
                 @endphp
 @foreach($scores as $score)
                <tr>

<td>{{$count}}</td>
<td>{{$score->subjects?$score->subjects->name:'-'}}</td>
<td>{{$score->test1?$score->test1:'-'}}</td>
@if ($report->isCa2)
<td>{{$score->test2?$score->test2:'-'}}</td>
@endif
@if ($report->isCa3)
<td>{{$score->test3?$score->test3:'-'}}</td>
@endif
<td>{{$score->exams?$score->exams:'-'}}</td>

<td>{{ getTotal($Totals,$score->subject_id,1) }}</td>
<td>{{ getTotal($Totals,$score->subject_id,2) }}</td>

<td>{{$score->total?$score->total:'-'}}</td>
<td>{{$score->grand_total?$score->grand_total:'-'}}</td>
<td>{{$score->cummulative_avg?$score->cummulative_avg:'-'}}</td>
 @if ($report->isMaxScore)
<td>{{$score->arm_max_score?$score->arm_max_score:'-'}}</td>
 @endif
@if ($report->isMinScore)
<td>{{$score->arm_min_score?$score->arm_min_score:'-'}}</td>
@endif
<td>{{$score->cummulative_grade?$score->cummulative_grade:'-'}}</td>
@if ($report->isSubjectPosition)
<td>{{$score->arm_subj_position?$score->arm_subj_position:'-'}}</td>
@endif
<td>{{$score->cummulative_narration?$score->cummulative_narration:'-'}}</td>


 @php
                     $count=$count+1;
                 @endphp
</tr>


                @endforeach
@if (count($noneAcademic)>0)
   <td  colspan="14" style="border:none">
    <div class="text-center text-bold text-primary">
         NON ACADEMIC SUBJECTS</div>
</td>


                 @php
                     $count2=1;
                 @endphp
@foreach($noneAcademic as $score)
                <tr>

<td>{{$count2}}</td>
<td>{{$score->subjects?$score->subjects->name:'-'}}</td>
<td>{{$score->test1?$score->test1:'-'}}</td>
@if ($report->isCa2)
<td>{{$score->test2?$score->test2:'-'}}</td>
@endif
@if ($report->isCa3)
<td>{{$score->test3?$score->test3:'-'}}</td>
@endif
<td>{{$score->exams?$score->exams:'-'}}</td>
<td>{{ getTotal($Totals,$score->subject_id,1) }}</td>
<td>{{ getTotal($Totals,$score->subject_id,2) }}</td>

<td>{{$score->total?$score->total:'-'}}</td>
<td>{{$score->grand_total?$score->grand_total:'-'}}</td>
<td>{{$score->cummulative_avg?$score->cummulative_avg:'-'}}</td>
 @if ($report->isMaxScore)
<td>{{$score->arm_max_score?$score->arm_max_score:'-'}}</td>
 @endif
@if ($report->isMinScore)
<td>{{$score->arm_min_score?$score->arm_min_score:'-'}}</td>
@endif
<td>{{$score->cummulative_grade?$score->cummulative_grade:'-'}}</td>
@if ($report->isSubjectPosition)
<td>{{$score->arm_subj_position?$score->arm_subj_position:'-'}}</td>
@endif
<td>{{$score->cummulative_narration?$score->cummulative_narration:'-'}}</td>



</tr>

                @php
                     $count2=$count2+1;
                 @endphp
                @endforeach

@endif
@if (count($subjectDropt)>0)
         <tr>
       <td colspan="15">
       @include('subjectDropt')
       </td>
                    </tr>
                    @endif

                </table>

                </div>
                <div class="pt-2 pb-2">
                @include('summary')
                </div>
