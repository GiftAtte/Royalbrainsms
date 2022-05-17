<tr>
<th  colspan="14" >
   <div class="text-center text-bold text-primary">
         NON ACADEMIC SUBJECTS</div>

</th>
</tr>
@php
    $count=1;

@endphp
@foreach($noneAcademic as $score)
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
 @if($report->term_id===3 && $report->isCummulative>0)
@foreach($Totals as $total)
@if($total['subject_id']===$score->subject_id && $total['term_id']===1)
<td>{{$total['total']?$total['total']:'-'}}</td>
@endif
@endforeach
@foreach ($Totals as $total)
@if($total['subject_id']===$score->subject_id && $total['term_id']===2)
<td>{{$total['total']?$total['total']:'-'}}</td>
@endif
@endforeach
<td>{{$score->total?$score->total:'-'}}</td>
<td>{{$score->grand_total?$score->grand_total:'-'}}</td>
<td>{{$score->average?$score->average:'-'}}</td>
<td>{{$score->arm_min_score?$score->arm_min_score:'-'}}</td>
<td>{{$score->arm_max_score?$score->arm__score:'-'}}</td>
<td>{{$score->arm_avg_score?$score->arm_avg_score:'-'}}</td>
<td>{{$score->cummulative_grade?$score->cummulative_grade:'-'}}</td>
<td>{{$score->cummulative_narration?$score->cummulative_narration:'-'}}</td>
@else
<td>{{$score->total?$score->total:'-'}}</td>
@if ($report->isMinScore)
<td>{{$score->arm_min_score?$score->arm_min_score:'-'}}</td>
@endif

 @if ($report->isMaxScore)
<td>{{$score->arm_max_score?$score->arm_max_score:'-'}}</td>
 @endif

 <td>{{$score->arm_avg_score?$score->arm_avg_score:'-'}}</td>
<td>{{$score->grade?$score->grade:'-'}}</td>
@if ($report->isSubjectPosition)
<td>{{$score->arm_subj_position?$score->arm_subj_position:'-'}}</td>
@endif
<td>{{$score->narration?$score->narration:'-'}}</td>
@endif

</tr>

                @php
                     $count=$count+1;
                 @endphp
                @endforeach
