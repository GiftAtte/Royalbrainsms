            @php
                     $count=1;
                 @endphp

<table class="table myTable table-bordered" style="width: 100%">
    <thead>
        <tr>
            <th colspan="14"><h6 class="text-primary"><div class="text-center text-bold text-primary">
         SUBJECTS NOT OFFERED THIS TERM</div></th>
        </tr>
    </thead>
    <tbody>
<tr>
    <th colspan="2">S/N</th>
    <th colspan="4">Subject</th>
    <th colspan="4">Total Scores</th>
    <th colspan="4">Average Score</th>
</tr>
@foreach ($subjectDropt as $score)
<tr>
     <td colspan="2">{{ $count }}</td>
    <td colspan="4">{{ $score['subject'] }}</td>
     <td colspan="4">{{ $score['grandTotal'] }}</td>
      <td colspan="4">{{ $score['average'] }}</td>
</tr>
      @php
                     $count=$count+1;
                 @endphp
@endforeach
    </tbody>

</table>
