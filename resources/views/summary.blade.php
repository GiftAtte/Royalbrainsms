
<style>
    .table th{
   text-transform: uppercase;
    }
</style>
<tr style="border: none">
 <th colspan="8"  style="border: none"><h6 class="text-danger text-center">RESULTS SAMMARY</h6></th>
 </tr>
<table class="table table-bordered table-sm font-weight-bold  myTable" >
 <tbody>
     <tr style="align-items: center;background-color:rgb(15, 15, 112); color:white">

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

<tr>


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

<tr>
    <td colspan="9">
        @if ($report->isAttendance)
@include('attendance')
        @endif

    </td>
</tr>
 </tbody>
 </table>
