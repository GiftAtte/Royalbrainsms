@php
    $index=1;
@endphp
<table class="table table-bordered  table-sm text-capitalize   text-nowrap">
<thead>
<tr class="text-center">
<th>S/N</th>
<th>Subject</th>
<th>Class <br>Work[2%]</th>
<th>Assignment<br>[2%]</th>
<th>Class<br>Test[5%]</th>
<th>Notes<br>[1%]</th>
<th>Weighted<br>Score[5%]</th>
<th>Mid-Term<br>[25%]</th>
<th>Exams<br>[70%]</th>
<th>Total<br>[100%]</th>
<th class="text-center"> Average <br>Score</th>
<th class="text-center">Position</th>
<th>Teacher's<br>Comment</th>
</tr>
</thead>

<tbody>
    @foreach ($scores as $score)
<tr>
<td>{{$index+1}}</td>
<td>{{$score->subjects?$score->subjects->name:''}}</td>
<td>{{$score->test1}}</td>
<td>{{$score->test2}}</td>
<td>{{$score->test3}}</td>
<td>{{$score->note}}</td>
<td>{{$score->weighted_score}}</td>
<td>{{$score->mid_term}}</td>
<td>{{$score->exams}}</td>
<td>{{$score->total}}</td>
<td>{{$score->arm_avg_score}}</td>
<td>{{$score->arm_subj_position}}</td>
<td>{{$score->grade}}</td>
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
