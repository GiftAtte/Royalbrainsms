@php
    $index=1;
@endphp
<table class="table table-bordered">
<thead>
<tr class="text-center">
<th>S/N</th>
<th>A:Cognitive<br>Subject</th>
<th>Class<br> Work[2]</th>
<th>Assignment<br>[2]</th>
<th>Class<br>Test[5]</th>
<th>Notes<br>[1]</th>
<th>Weighted <br>Score [5]</th>
<th>Exams<br>[20]</th>
<th>Total<br>[25]</th>
<th class="text-center" ><div><span>Average<br> Score</span></div></th>
<th class="text-center" ><div><span>Position</span></div></th>
<th>Teacher's<br>Comments</th>
</tr>
</thead>

<tbody >
    @foreach ($scores as $score)

    <tr>
<td>{{$index+1}}</td>
<td>{{$score->subjects->name}}</td>
<td >{{$score->test1}}</td>
<td>{{$score->test2}}</td>
<td>{{$score->test3}}</td>
<td>{{$score->note}}</td>
<td>{{$score->weighted_score}}</td>
<td>{{$score->exams}}</td>
<td>{{$score->total}}</td>
<td >{{$score->arm_avg_score}}</td>
<td >{{$score->arm_subj_position}}</td>
<td>{{$score->grade}}</td>
</tr>
    @endforeach


</tbody>


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
