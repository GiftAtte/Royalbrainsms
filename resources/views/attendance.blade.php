<table class="table  pt-2 pb-2" >
            <thead>
          <tr>
              <th colspan="3" class="text-center" style="border:none">
<h6 >ATTENDANCE</h6>
              </th>

          </tr>


      </thead>
      <tbody>
          <tr>
              <th>Total School Days </th>
              <th>Days Present</th>
              <th>Days Absent </th>
          </tr>

          <tr>
              <td>{{ $attendance?$attendance->school_days:'----'}}</td>
              <td>{{ $attendance?$attendance->days_absent:'----'}}</td>
              <td>{{ $attendance?$attendance->days_present:'----'}}</td>
          </tr>

      </tbody>
  </table>
