
 <table class=" table table-bordered myTable">
<tr style=" background-color: #0c018b; color:white">
<th>S/N</th>
<th>COGNITIVES <br/> *SUBJECTS*</th>
@if ($report->ca1Percent)
<th>CA <br/>{{ $report->ca1Percent?'['.$report->ca1Percent.']':'' }}</th>
@else
<th>MAXIMUM <br/>SCORE <br/>{{ $report->ca1Percent?'['.$report->ca1Percent.']':'' }}</th>
@endif
@if ($report->ca1Percent)
<th>EXAMS<br/>{{ $report->examPercent?'['.$report->examPercent.']':'' }}</th>
@else
<th>SCORES</th>
@endif
@if ($report->ca1Percent)
<th>TOTAL <b/>[100%]</th>
@endif
<th>GRADE</th>
<th>REMARKS</th>
</tr>



    @php
        $count=1
    @endphp
    @foreach ($scores as $score)
<tr>
<td>{{$count}}</td>
<td>{{$score->subjects?$score->subjects->name:''}}</td>
@if ($report->ca1Percent)
<td>{{$score->test1?$score->test1:'-'}}</td>
@else
<td>100%</td>
@endif
<td>{{$score->exams?$score->exams:'-'}}</td>
@if ($report->ca1Percent)
<td>{{$score->total?$score->total:'-'}}</td>
@endif
<td>{{$score->grade?$score->grade:'-'}}</td>
<td>{{$score->narration?$score->narration:'-'}}</td>
</tr>

@php
    $count=$count+1;
@endphp
@endforeach

</table>
