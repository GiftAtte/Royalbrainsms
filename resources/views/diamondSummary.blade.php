<style>
    .summary{
      background-color:rgb(111, 1, 89);
      color: white;

    }
</style>

<table class="table myTable table-bordered summary" style="max-width: 60%">

          <tr>
              <th colspan="3" style="text-transform: uppercase;background-color:rgb(61, 19, 57);
                   color:white">
<h6 class="text-white text-center">RESULT SUMMARY</h6>

    </th>
          </tr>

                  <tr style="text-transform: uppercase;background-color:rgb(75, 17, 69);
                   color:white">
                      <th>
                          Total Scores
                      </th>
                      <th>
                          Average Score
                      </th>
                      <th>
                         Final Grade
                      </th>
                  </tr>
                   <tr class="text-center" style=" color:black;background-color:rgba(241, 238, 238, 0.921);">
                     <td> <h6>{{$summary->total_scores}}</h6></td>
                      <td><h6>{{$summary->average_scores}}</h6></td>
                      <td><h6>{{$summary->grade}}</h6></td>
                   </tr>
              </table>
