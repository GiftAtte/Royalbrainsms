
@php
    $index=1;
@endphp
<div>
<table class="table myTable table-bordered" >
<thead class="text-center">
<tr>
<th>S/N</th>
<th>Subject</th>
<th>TOTAL</th>
<th> CLASS AVERAGE<br>SCORE</th>
<th>GRADE</th>
<th>POSITION</th>
</tr>
</thead>

<tbody>
@foreach ($scores as $score)
<tr>
<td>{{$index+1}}</td>
<td>{{$score->subjects?$score->subjects->name:''}}</td>
<td>{{$score->exams}}</td>
<td>{{$score->arm_avg_score}}</td>
<td>{{$score->grade}}</td>
<td>{{$score->arm_subj_position}}</td>
</tr>
    @endforeach
</tbody>
</table>
 </div>
 <br/>
 <div class="container">
@include('summary')
 </div>

