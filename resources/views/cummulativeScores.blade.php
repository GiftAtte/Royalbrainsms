
<div>
                <table class=" table table-bordered text-capitalized myTable">

                <tr class="text-center text-capitalized table-striped" style="vertical-align: center;background-color:rgb(15, 15, 112); color:rgb(251, 249, 246)">
                <td>S/N</td>
                <td>Subject</td>
                <td>{{ $report->isCa2?'CA1':'CA' }}<br/>[{{ $report->ca1Percent?($report->ca1Percent.'%'):'' }}]</td>
                @if ($report->isCa2)
                <td>CA2 <br/>[{{ $report->ca2Percent?($report->ca2Percent.'%'):'' }}]</td>
                @endif
                @if ($report->isCa3)
                <td>CA3 <br/>[{{ $report->ca3Percent?($report->ca3Percent.'%'):'' }}]</td>
                @endif
                <td>Exam <br/>[{{ $report->examPercent?($report->examPercent.'%'):'' }}]</td>
                <td>1st Term<br>[100%]</td>
                <td>2nd Term<br>[100%]</td>
                <td>3rd Term<br>[100%]</td>
                <td>Grand<br>Total</td>
                <td>Cumm.<br>Avg</td>

                 @if ($report->isMaxScore)
                 <td>Highest <br/>Score</td>
                 @endif
                 @if ($report->isMinScore)
                 <td>Lowest <br/>Score</td>
                 @endif
               
                <td>Grade</td>
                @if ($report->isSubjectPosition)
                 <td>Subject<br/>Position</td>
                @endif
                <td>Comment</td>

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

@foreach($Totals as $total)
@if($total['subject_id']===$score->subject_id && $total['term_id']===1)
<td>{{$total['total']>0?$total['total']:'-'}}</td>
@endif
@endforeach

@foreach ($Totals as $total)
@if($total['subject_id']===$score->subject_id && $total['term_id']===2)
<td>{{$total['total']>0?$total['total']:'-'}}</td>
@endif
@endforeach

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
                     $count=$count+1;
                 @endphp
                @endforeach
@if (count($noneAcademic)>0)
   


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
                     $count=$count+1;
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
