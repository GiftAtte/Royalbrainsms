
@php
    $index=1;
@endphp

<table class="table table-bordered">
<thead>
<tr>
<th>S/N</th>
<th>Subject</th>
<th>1st Term<br>[30%]</th>
<th>2nd Term<br>[30%]</th>
<th>3rd Term<br>[40%]</th>
<th>Total<br>[100%]</th>
<th>Class Average</th>
<th>Teacher's<br> Comment</th>

<th>Position</th>
</tr>
</thead>

<tbody>
    @foreach ($scores as $score)
<tr>
<td>{{$index+1}}</td>
<td>{{$score->subjects?$score->subjects->name:''}}</td>
@foreach($Totals as $total)
@if($total['subject_id']===$score->subject_id && $total['term_id']===1)
<td>{{$total['total']?$total['total']:'-'}}</td>
@endif
@endforeach
@foreach($Totals as $total)
@if($total['subject_id']===$score->subject_id && $total['term_id']===2)
<td>{{$total['total']?$total['total']:'-'}}</td>
@endif
@endforeach
</td>
<td>{{$score->annual_score}}</td>
<td>{{$score->annual_total}}</td>
<td>{{$score->annual_average}}</td>
<td>{{$score->annual_grade}}</td>
<td>{{$score->annual_position}}</td>
</tr>
@endforeach
</tbody>
</table>
