<table class="table  pt-2 pb-2 table-bordered" >
            <thead>
          <tr>
              <th colspan="4" class="text-center" style="vertical-align: center;background-color:rgb(15, 15, 112); color:white">
ATTENDANCE
              </th>

          </tr>


      </thead>
      <tbody>
          <tr>
              <th>Total School Days </th>
              <th>Days Present</th>
              <th>Days Absent </th>
              <th>Next Term Begins</th>
          </tr>

          <tr class="text-center">
              <td>{{ $attendance?$attendance->school_days:'----'}}</td>
              <td>{{ $attendance?$attendance->days_present:'----'}}</td>
              <td>{{ $attendance?$attendance->days_absent:'----'}}</td>
              <td>{{$report->term_start?$report->term_start:'---------'}}</td>

          </tr>

      </tbody>
  </table>
