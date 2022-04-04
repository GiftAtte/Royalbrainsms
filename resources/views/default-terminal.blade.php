
<div>
                <table class=" table table-bordered text-capitalized">

                <tr class="text-center text-capitalized" style="vertical-align: center">
                <td>S/N</td>
                <td>Subject</td>
                <td>CA</td>
                <td>Exam</td>
                @if($report->term_id===3 && $report->isCummulative>0)
                <td>1st Term</td>
                <td>2nd Term</td>
                <td>3rd Term</td>
                <td>Grand<br>Total</td>
                <td>Cumm.<br>Avg</td>

                @else
                <td>Total</td>
                 @endif
                 <td>Lowest <br/>Score</td>
                 <td>Highest <br/>Score</td>
                <td>Class Avg</td>
                <td>Grade</td>
                <td>Comment</td>


                <!-- <th>Narration</th> -->
                <!-- <th class="text-center " v-show="isAHScore"><div><span>HIGHEST<br> SCORE</span></div></th>
                <th class="text-center" v-show="isASLScore"><div><span>LOWEST <br> SCORE</span></div></th> -->


                </tr>



                 @php
                     $count=1;
                 @endphp
 @foreach($scores as $score)
                <tr>

<td>{{$count}}</td>
<td>{{$score->subjects?$score->subjects->name:'-'}}</td>
<td>{{$score->test1?$score->test1:'-'}}</td>
<td>{{$score->exams?$score->exams:'-'}}</td>
 @if($report->term_id===3 && $report->isCummulative>0)
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
<td>{{$score->average?$score->average:'-'}}</td>
<td>{{$score->arm_min_score?$score->arm_min_score:'-'}}</td>
<td>{{$score->arm_max_score?$score->arm__score:'-'}}</td>
<td>{{$score->arm_avg_score?$score->arm_avg_score:'-'}}</td>
<td>{{$score->cummulative_grade?$score->cummulative_grade:'-'}}</td>
<td>{{$score->cummulative_narration?$score->cummulative_narration:'-'}}</td>
@else
<td>{{$score->total?$score->total:'-'}}</td>
 <td>{{$score->arm_min_score?$score->arm_min_score:'-'}}</td>
 <td>{{$score->arm_max_score?$score->arm_max_score:'-'}}</td>
 <td>{{$score->arm_avg_score?$score->arm_avg_score:'-'}}</td>
<td>{{$score->cummulative_grade?$score->cummulative_grade:'-'}}</td>
<td>{{$score->narration?$score->narration:'-'}}</td>
@endif
{{-- <td>{{$score->arm_subj_position?$score->arm_subj_position:'-'}}</td> --}}

</tr>
{{-- <td v-show="!isThird_term ||!isCummulative">{{score.grade?score.grade:'-'}}</td>
<td v-show="!isThird_term ||!isCummulative">{{score.narration?score.narration:'-'}}</td> --}}


{{-- <td v-show="isASLScore">{{score.arm_min_score?score.arm_min_score:'-'}}</td>
<td v-show="isASAScore">{{score.arm_avg_score?score.arm_avg_score:'-'}}</td>
<td v-show="isASPosition">{{score.arm_subj_position?score.arm_subj_position:''}}</td>

<td v-show="isCSPosition">{{score.class_subj_position?score.arm_subj_position:''}}</td>
<td v-show="isCSHScore">{{score.max_class_score?score.max_class_score:''}}</td>
<td v-show="isCSLScore">{{score.min_class_score?score.min_class_score:''}}</td>
<td v-show="isCAScore">{{score.class_avg_score?score.class_avg_score:''}}</td> --}}


                @php
                     $count=$count+1;
                 @endphp
                @endforeach

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

