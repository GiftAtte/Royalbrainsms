              <table class="table table-bordered myTable" style=" font-size:xx-small">
                <tr>
                <th colspan="3" style="vertical-align: center;background-color:rgb(5, 5, 163); color:white">GRADING KEYS</th>

                </tr>
                <tr><th>SCORES</th><th>GRADE</th><th>DEGREE</th></tr>
               @foreach ( $gradings as $grade )
               <tr class="text-capitalized">
          <td>{{$grade->lower_bound}} - {{$grade->upper_bound}}</td>
          <td>{{$grade->grade}}</td><td class="text-capitalized">{{$grade->narration}}</td>
                  </tr>
               @endforeach
              </table>
