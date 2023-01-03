<style>
    .text-capitalize{
        text-transform: capitalize !important;
    }
</style>
 <table class=" table table-bordered myTable">
<tr style=" background-color: rgb(5, 5, 163); color:white">
<th>S/N</th>
<th>COGNITIVES <br/> *SUBJECTS*</th>
@if ($report->ca1Percent)
@if($report->isCa2)
<th>CA1 <br/>{{ $report->ca1Percent?'['.$report->ca1Percent.']':'' }}</th>
@else
<th>CA <br/>{{ $report->ca1Percent?'['.$report->ca1Percent.']':'' }}</th>
@endif
@else
<th>MAXIMUM <br/>SCORE <br/>{{ $report->ca1Percent?'['.$report->ca1Percent.']':'' }}</th>
@endif
 @if($report->isCa2)
 <th>CA2 {{ $report->ca2Percent?'['.$report->ca2Percent.']':'' }}</th>
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
<td class="text-capitalize">{{$score->subjects?$score->subjects->name:''}}</td>
@if ($report->ca1Percent)
<td>{{$score->test1?$score->test1:'-'}}</td>
@else
<td>100%</td>
@endif
@if($report->isCa2)
<td>{{ $score->test2 }}</td>
@endif
<td>{{$score->exams?$score->exams:'-'}}</td>
@if ($report->ca1Percent)
<td>{{$score->total?$score->total:'-'}}</td>
@endif
<td>{{$score->grade?$score->grade:'-'}}</td>
<td class="text-capitalize">{{$score->narration?$score->narration:'-'}}</td>
</tr>

@php
    $count=$count+1;
@endphp
@endforeach

</table>
