@php
    $index=1;
@endphp
<table class="table table-bordered  table-sm text-capitalize   myTable">
<thead>
<tr class="text-center">
<th>S/N</th>
<th>Subject</th>
<th>Class <br>Work[2%]</th>
<th>Assign<br>[2%]</th>
<th>Class<br>Test[5%]</th>
<th>Notes<br>[1%]</th>
<th>Wted<br>Score[5%]</th>
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
@php
    $index=$index+1;
@endphp
    @endforeach


</tbody>


</table>
 </div>
            <div row>
                    @if (!empty($subjectDropt)>0)
@include('subjectDropt')
                    @endif
                </div>
                </div>
                <div class="pt-2 pb-2">
                @include('summary')
                </div>
