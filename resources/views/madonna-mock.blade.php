
@php
    $index=1;
@endphp
<div>
<table class="table table-bordered" >
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
                <div class="text-center text-bold text-primary container pt-2 ">
                    <h6 class="p-1 text-danger"> RESULTS SUMMARY</h6></div>
                <hr class="text-primary">
                <div>
                <table class="table" width="100%">
                    <tr>
                 <td><b>Total score:&nbsp;{{$summary->total_scores}}</b></td>
                 <td><b>Average score:&nbsp;{{$summary->average_scores	}}</b></td>
                 <td><b>Cummulative Avg score:&nbsp;{{$summary->cummulative_average}}</b></td>
                 <td><b>Grade:&nbsp;{{$summary->grade}}</b></td>

                 </tr>
                </table>
                <hr class="text-primary">
               </div>
