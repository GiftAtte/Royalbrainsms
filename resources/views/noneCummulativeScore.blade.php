<style>
    .table th{
   text-transform: uppercase;
   font-weight: bold
    }
</style>
<div>
                <table class="table table-bordered myTable table-capitalize">

                <tr class="text-center text-capitalized table-striped" style="vertical-align: center;background-color:rgb(15, 15, 112); color:rgb(251, 249, 246)">
                <th>S/N</th>
                <th>Subject</th>
                <th>{{ $report->isCa2?'CA1':'CA' }}<br/>[{{ $report->ca1Percent?($report->ca1Percent.'%'):'' }}]</th>
                @if ($report->isCa2)
                <th>CA2 <br/>[{{ $report->ca2Percent?($report->ca2Percent.'%'):'' }}]</th>
                @endif
                @if ($report->isCa3)
                <th>CA3 <br/>[{{ $report->ca3Percent?($report->ca3Percent.'%'):'' }}]</th>
                @endif
                <th>Exam <br/>[{{ $report->examPercent?($report->examPercent.'%'):'' }}]</th>
                @if($report->term_id===3 && $report->isCummulative>0)
                <th>1st Term</th>
                <th>2nd Term</th>
                <th>3rd Term</th>
                <th>Grand<br>Total</th>
                <th>Cumm.<br>Avg</th>

                @else
                <th>Total<br/>[100%]</th>
                 @endif
                 @if ($report->isMinScore)
                 <th>Lowest <br/>Score</th>
                 @endif

                 @if ($report->isMaxScore)
                 <th>Highest <br/>Score</th>
                 @endif

                <th>Class Avg</th>
                <th>Grade</th>
                @if ($report->isSubjectPosition)
                 <th>Subject<br/>Position</th>
                @endif
                <th>Comment</th>

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
<td>{{$score->total?$score->total:'-'}}</td>
@if ($report->isMaxScore)
<td>{{$score->arm_max_score?$score->arm_max_score:'-'}}</td>
 @endif
@if ($report->isMinScore)
<td>{{$score->arm_min_score?$score->arm_min_score:'-'}}</td>
@endif
<td>{{$score->arm_avg_score?$score->arm_avg_score:'-'}}</td>
<td>{{$score->grade?$score->grade:'-'}}</td>
@if ($report->isSubjectPosition)
<td>{{$score->arm_subj_position?$score->arm_subj_position:'-'}}</td>
@endif
<td>{{$score->narration?$score->narration:'-'}}</td>

</tr>
                @php
                     $count=$count+1;
                 @endphp
                @endforeach
@if (count($noneAcademic)>0)
<td  colspan="14" style="border:none">
    <div class="text-center text-bold text-primary">
         NON ACADEMIC SUBJECTS</div>
</td>

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
<td>{{$score->total?$score->total:'-'}}</td>
@if ($report->isMaxScore)
<td>{{$score->arm_max_score?$score->arm_max_score:'-'}}</td>
 @endif
@if ($report->isMinScore)
<td>{{$score->arm_min_score?$score->arm_min_score:'-'}}</td>
@endif
<td>{{$score->arm_avg_score?$score->arm_avg_score:'-'}}</td>
<td>{{$score->grade?$score->grade:'-'}}</td>
@if ($report->isSubjectPosition)
<td>{{$score->arm_subj_position?$score->arm_subj_position:'-'}}</td>
@endif
<td>{{$score->narration?$score->narration:'-'}}</td>

</tr>
                @php
                     $count=$count+1;
                 @endphp
                @endforeach

@endif
                </table>
                <div row>
                    @if (!empty($subjectDropt)>0)
@include('subjectDropt')
                    @endif
                </div>
                </div>
                <div class="pt-2 pb-2">
                @include('summary')
                </div>
