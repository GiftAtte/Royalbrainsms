
<style>
    .table th{
   text-transform: uppercase;
    }
</style>
<tr >

 <th colspan="8" >
     <div class=" pt-2" style="width:100%;  height:10px; border-radius:25px; background-color:rgb(61, 19, 57)">
<h6 class="text-white text-center">RESULTS SUMMARY</h6>
    </div>
    </th>
 </tr>
<table class="table table-bordered table-sm font-weight-bold  myTable" >
 <tbody>
     <tr class="text-center" style="background-color:rgb(61, 19, 57); color:white">

         <th>Total score</th>
         <th>Average <br/>score</th>
        @if ($report->term_id>2 &&$report->isCummulative)
            <th>
             Cummulative <br/>Avg score
         </th>
        @endif

         <th>Grade</th>
         <th>Narration</th>
         <th>Students In Class</th>
         @if ($report->isClassPosition)
        <th>Student <br/>Position</th>
         @endif

         @if ($report->isProgressStatus)
       <th>Progress status</th>
         @endif


     </tr>

<tr class="text-center">


<td>{{$summary->total_scores}}</td>

<td>{{$summary->average_scores}}</td>

@if ($report->term_id>2 && $report->isCummulative)
<td>{{$summary->cummulative_average	}}</td>
@endif


<td>{{$summary->grade}}</td>


 <td>{{$summary->narration}}</td>

 <td>{{$summary->total_students}}</td>
 @if ($report->isClassPosition)
<td>{{$summary->class_position?$summary->class_position:'----'}}</td>
 @endif
@if ($report->isProgressStatus)
<td><b>{{$summary->progress_status?$summary->progress_status:'---------------'}}</b></td>
@endif




 </tr>

 </tbody>
 </table>

    @if ($report->isAttendance)
     @include('attendance')
     @endif
